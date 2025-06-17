<?php

class MedicalAssistantController
{
    private $apiKey;
    private $apiUrl;
    private $model;
    private $systemMessage;
    private $db;

    public function __construct()
    {
        $this->apiKey = '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';
        $this->apiUrl = 'https://api.groq.com/openai/v1/chat/completions';
        $this->model = 'meta-llama/llama-4-scout-17b-16e-instruct';
        $this->systemMessage = 'Kamu adalah asisten medis cerdas. Berdasarkan hasil lab yang diberikan, berikan analisis kemungkinan penyakit atau kondisi pasien dan saran tindakan medis awal. Jangan memberikan diagnosis pasti, hanya analisis dan saran profesional.';
        
        $this->initializeSession();
    }
    
    /**
     * Main method to handle the request
     */
    public function handleRequest()
    {
        $this->setJsonHeader();
        
        try {
            $hasilLabId = $this->getInputData();
            $userMessage = $this->validateMessage($hasilLabId);

            $messages = $this->buildMessages($userMessage);
            $spkAnalysis = $this->performSPKAnalysis($userMessage);
            
            $aiResponse = $this->callLLMAPI($messages);
            $this->saveConversation($userMessage, $aiResponse);
            
            $this->sendSuccessResponse($spkAnalysis, $aiResponse);
            
        } catch (Exception $e) {
            $this->sendErrorResponse($e->getMessage());
        }
    }
    
    /**
     * Initialize session for conversation history
     */
    private function initializeSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['conversation'])) {
            $_SESSION['conversation'] = [];
        }
    }
    
    /**
     * Set JSON content type header
     */
    private function setJsonHeader()
    {
        header('Content-Type: application/json');
    }
    
    /**
     * Get and decode input data
     */
    private function getInputData()
    {
    $rawInput = file_get_contents("php://input");
    $input = json_decode($rawInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input');
    }

    if (!isset($input['hasil_lab_id'])) {
        throw new Exception('hasil_lab_id tidak ditemukan');
    }

    return $input['hasil_lab_id'];
}

    
    /**
     * Validate user message
     */
    private function validateMessage($hasilLabId)
{
    // Ambil data hasil_lab
    $stmt = $this->db->prepare("
        SELECT hl.*, jp.nama as jenis_pemeriksaan, p.nik, p.alamat, p.tanggal_lahir
        FROM hasil_lab hl
        JOIN pasien p ON hl.pasien_id = p.id
        JOIN jenis_pemeriksaan jp ON hl.jenis_id = jp.id
        WHERE hl.id = ?
    ");
    $stmt->execute([$hasilLabId]);
    $labData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$labData) {
        throw new Exception("Data hasil lab tidak ditemukan");
    }

    // Ambil semua parameter
    $stmt2 = $this->db->prepare("
        SELECT pp.nama_parameter, hp.nilai, pp.satuan
        FROM hasil_parameter hp
        JOIN parameter_pemeriksaan pp ON hp.parameter_id = pp.id
        WHERE hp.hasil_lab_id = ?
    ");
    $stmt2->execute([$hasilLabId]);
    $params = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Susun sebagai pesan ke AI
    $message = "Berikut adalah hasil pemeriksaan lab pasien:\n";
    $message .= "- NIK: {$labData['nik']}\n";
    $message .= "- Tanggal Lahir: {$labData['tanggal_lahir']}\n";
    $message .= "- Jenis Pemeriksaan: {$labData['jenis_pemeriksaan']}\n";
    $message .= "- Catatan Dokter: {$labData['catatan']}\n\n";
    $message .= "Hasil Parameter:\n";

    foreach ($params as $p) {
        $message .= "- {$p['nama_parameter']}: {$p['nilai']} {$p['satuan']}\n";
    }

    return $message;
}

    
    /**
     * Build messages array for API call
     */
    private function buildMessages($userMessage)
    {
        $messages = [
            ["role" => "system", "content" => $this->systemMessage]
        ];
        
        // Add conversation history
        foreach ($_SESSION['conversation'] as $msg) {
            $messages[] = $msg;
        }
        
        // Add current user message
        $messages[] = ["role" => "user", "content" => $userMessage];
        
        return $messages;
    }
    
    /**
     * Perform SPK (Decision Support System) analysis using SAW method
     */
    private function performSPKAnalysis($userMessage)
    {
        $labValues = $this->extractLabValues($userMessage);
        
        if (!$this->hasRequiredLabValues($labValues)) {
            return '';
        }
        
        return $this->calculateSAWScore($labValues);
    }
    
    /**
     * Extract lab values from user message
     */
    private function extractLabValues($userMessage)
    {
        $values = [];
        
        preg_match("/Gula Darah Puasa.*?(\d+)/", $userMessage, $gdp);
        preg_match("/Gula Darah 2 Jam PP.*?(\d+)/", $userMessage, $gd2j);
        preg_match("/HbA1c.*?(\d+(\.\d+)?)/", $userMessage, $hba1c);
        
        $values['gdp'] = isset($gdp[1]) ? (int)$gdp[1] : null;
        $values['gd2j'] = isset($gd2j[1]) ? (int)$gd2j[1] : null;
        $values['hba1c'] = isset($hba1c[1]) ? (float)$hba1c[1] : null;
        
        return $values;
    }
    
    /**
     * Check if all required lab values are present
     */
    private function hasRequiredLabValues($values)
    {
        return !empty($values['gdp']) && !empty($values['gd2j']) && !empty($values['hba1c']);
    }
    
    /**
     * Calculate SAW (Simple Additive Weighting) score
     */
    private function calculateSAWScore($values)
    {
        $minValues = ['gdp' => 70, 'gd2j' => 110, 'hba1c' => 4.5];
        $weights = ['gdp' => 0.4, 'gd2j' => 0.3, 'hba1c' => 0.3];
        
        $score = (
            ($minValues['gdp'] / $values['gdp']) * $weights['gdp'] +
            ($minValues['gd2j'] / $values['gd2j']) * $weights['gd2j'] +
            ($minValues['hba1c'] / $values['hba1c']) * $weights['hba1c']
        );
        
        return $this->formatSPKResult($values, $score);
    }
    
    /**
     * Format SPK analysis result
     */
    private function formatSPKResult($values, $score)
    {
        $analysis = "**Analisis SPK (Metode SAW):**\n";
        $analysis .= "- Gula Darah Puasa: {$values['gdp']} mg/dL\n";
        $analysis .= "- Gula Darah 2 Jam PP: {$values['gd2j']} mg/dL\n";
        $analysis .= "- HbA1c: {$values['hba1c']}%\n";
        $analysis .= "**Skor Prioritas**: " . round($score, 3) . " (semakin tinggi, semakin prioritas)\n";
        
        $analysis .= $this->getPriorityMessage($score);
        $analysis .= "\n---\n\n";
        
        return $analysis;
    }
    
    /**
     * Get priority message based on score
     */
    private function getPriorityMessage($score)
    {
        if ($score > 0.95) {
            return "🚨 *Pasien perlu penanganan segera.*\n";
        } elseif ($score > 0.85) {
            return "⚠️ *Pasien perlu pemantauan ketat.*\n";
        } else {
            return "✅ *Masih dalam batas aman, tetap evaluasi berkala.*\n";
        }
    }
    
    /**
     * Call LLM API
     */
    private function callLLMAPI($messages)
    {
        $payload = [
            "model" => $this->model,
            "messages" => $messages,
            "temperature" => 0.7,
            "max_tokens" => 1000
        ];
        
        $ch = $this->initializeCurl($payload);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception('CURL Error: ' . $error);
        }
        
        curl_close($ch);
        
        return $this->processAPIResponse($response, $httpCode);
    }
    
    /**
     * Initialize CURL with proper settings
     */
    private function initializeCurl($payload)
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->apiKey}"
        ]);
        
        return $ch;
    }
    
    /**
     * Process API response
     */
    private function processAPIResponse($response, $httpCode)
    {
        $responseData = json_decode($response, true);
        
        if ($httpCode !== 200) {
            throw new Exception('API Error: HTTP ' . $httpCode . ' - ' . json_encode($responseData));
        }
        
        if (!isset($responseData['choices'][0]['message']['content'])) {
            throw new Exception('No content in API response: ' . json_encode($responseData));
        }
        
        return $responseData['choices'][0]['message']['content'];
    }
    
    /**
     * Save conversation to session
     */
    private function saveConversation($userMessage, $aiResponse)
    {
        $_SESSION['conversation'][] = ["role" => "user", "content" => $userMessage];
        $_SESSION['conversation'][] = ["role" => "assistant", "content" => $aiResponse];
        
        // Keep only last 20 messages to prevent session bloat
        $_SESSION['conversation'] = array_slice($_SESSION['conversation'], -20);
    }
    
    /**
     * Send success response
     */
    private function sendSuccessResponse($spkAnalysis, $aiResponse)
    {
        $response = [
            'success' => true,
            'content' => $spkAnalysis . $aiResponse,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        echo json_encode($response);
    }
    
    /**
     * Send error response
     */
    private function sendErrorResponse($errorMessage)
    {
        $response = [
            'error' => $errorMessage
        ];
        
        echo json_encode($response);
    }
public function handleRequestFromHasilLab($hasilLabId)
{
    try {
        $userMessage = $this->validateMessage($hasilLabId); // ambil data dari DB
        $response = $this->askToLlm($userMessage);           // kirim ke AI
        $content = $this->formatResponse($response, $userMessage); // gabungkan hasil AI + SPK

        echo json_encode([
            'success' => true,
            'content' => $content,
            'timestamp' => date("Y-m-d H:i:s")
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Gabungkan hasil AI dan analisis SPK (jika ada)
 */
private function formatResponse($aiResponse, $userMessage)
{
    $spkAnalysis = $this->performSPKAnalysis($userMessage);
    return $spkAnalysis . $aiResponse;
}

}

// Usage - instantiate and handle request
$controller = new MedicalAssistantController();
$controller->handleRequest();

?>
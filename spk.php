<?php
header('Content-Type: application/json');

// Ambil data dari JavaScript
$input = json_decode(file_get_contents("php://input"), true);
$message = $input["message"] ?? "";

// Dummy parsing manual nilai gula darah
// Di dunia nyata, parsing ini bisa diganti regex atau AI/NLP jika kompleks
preg_match("/Gula Darah Puasa.*?(\d+)/", $message, $gdp);
preg_match("/Gula Darah 2 Jam PP.*?(\d+)/", $message, $gd2j);
preg_match("/HbA1c.*?(\d+(\.\d+)?)/", $message, $hba1c);

$gdpVal = isset($gdp[1]) ? (int)$gdp[1] : null;
$gd2jVal = isset($gd2j[1]) ? (int)$gd2j[1] : null;
$hba1cVal = isset($hba1c[1]) ? (float)$hba1c[1] : null;

// Nilai normal minimum (cost type)
$min = [
    'gdp' => 70,
    'gd2j' => 110,
    'hba1c' => 4.5
];

$bobot = [
    'gdp' => 0.4,
    'gd2j' => 0.3,
    'hba1c' => 0.3
];

if ($gdpVal && $gd2jVal && $hba1cVal) {
    $skor = (
        ($min['gdp'] / $gdpVal) * $bobot['gdp'] +
        ($min['gd2j'] / $gd2jVal) * $bobot['gd2j'] +
        ($min['hba1c'] / $hba1cVal) * $bobot['hba1c']
    );

    $respon = "**Analisis SPK (Metode SAW):**\n\n";
    $respon .= "- Gula Darah Puasa: $gdpVal mg/dL\n";
    $respon .= "- Gula Darah 2 Jam PP: $gd2jVal mg/dL\n";
    $respon .= "- HbA1c: $hba1cVal%\n\n";
    $respon .= "**Skor Prioritas**: " . round($skor, 3) . " (semakin tinggi, semakin prioritas)\n";

    if ($skor > 0.95) {
        $respon .= "\n🚨 *Pasien ini perlu ditangani segera karena hasil sangat tidak normal.*";
    } elseif ($skor > 0.85) {
        $respon .= "\n⚠️ *Pasien perlu pemantauan ketat dan intervensi ringan.*";
    } else {
        $respon .= "\n✅ *Pasien dalam batas yang masih aman, tapi perlu evaluasi lanjutan.*";
    }

    echo json_encode([
        "success" => true,
        "content" => $respon,
        "timestamp" => date('d-m-Y H:i')
    ]);
} else {
    echo json_encode([
        "success" => false,
        "error" => "Data gula darah tidak ditemukan atau tidak lengkap dalam pesan.",
        "response" => $input
    ]);
}

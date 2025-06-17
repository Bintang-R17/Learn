<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Hasil Lab</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>🔬 Analisis Hasil Lab</h3>

    <!-- Simulasi hasil lab -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Parameter</th>
                <th>Nilai</th>
                <th>Satuan</th>
                <th>Nilai Normal</th>
            </tr>
        </thead>
        <tbody id="hasilLab">
            <tr><td>Hemoglobin (Hb)</td><td>11.8</td><td>g/dL</td><td>12.0-15.5 g/dL</td></tr>
            <tr><td>Leukosit (WBC)</td><td>13.000</td><td>ribu/uL</td><td>4.5-11.0 ribu/uL</td></tr>
            <tr><td>Trombosit (Plt)</td><td>180.000</td><td>ribu/uL</td><td>150-450 ribu/uL</td></tr>
            <tr><td>Eritrosit (RBC)</td><td>4.2</td><td>juta/uL</td><td>4.0-5.2 juta/uL</td></tr>
            <tr><td>Hematokrit (Hct)</td><td>35</td><td>%</td><td>36-46%</td></tr>
            <tr><td>Gula Darah Puasa (GDP)</td><td>90</td><td>mg/dL</td><td>70-110 mg/dL</td></tr>
            <tr><td>Gula Darah 2 Jam PP</td><td>20</td><td>mg/dL</td><td>< 140 mg/dL</td></tr>
            <tr><td>HbA1c</td><td>7.5</td><td>%</td><td>< 5.7%</td></tr>
        </tbody>
    </table>

    <div class="row mt-3">
        <div class="col-md-8">
            <textarea class="form-control" id="catatanDokter" rows="3" placeholder="Tambahkan catatan atau pertanyaan spesifik tentang kondisi pasien..."></textarea>
        </div>
        <div class="col-md-4">
            <button class="btn btn-success w-100" id="btnAnalisa">
                <span id="btnText"> Analisis dengan AI</span>
                <span id="btnLoading" class="d-none">
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    Menganalisis...
                </span>
            </button>
        </div>
    </div>

    <div class="mt-4" id="hasilAI">
        <h5>💡 Hasil Analisis AI:</h5>
        <div class="alert alert-info" id="hasilContent">
            <em>Klik tombol "Analisis dengan AI" untuk mendapatkan interpretasi hasil lab...</em>
        </div>
    </div>
</div>

<script>
document.getElementById('btnAnalisa').addEventListener('click', async () => {
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');
    const hasilContent = document.getElementById('hasilContent');
    
    // Show loading state
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');
    hasilContent.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><br>Sedang menganalisis hasil lab...</div>';
    
    try {
        // Ambil data dari tabel
        const rows = document.querySelectorAll('#hasilLab tr');
        let message = "Berikut hasil pemeriksaan laboratorium pasien:\n\n";
        
        rows.forEach(row => {
            const cols = row.querySelectorAll('td');
            if (cols.length >= 4) {
                message += `• ${cols[0].innerText}: ${cols[1].innerText} ${cols[2].innerText} (Normal: ${cols[3].innerText})\n`;
            }
        });

        const catatan = document.getElementById('catatanDokter').value.trim();
        if (catatan) {
            message += `\nCatatan tambahan: ${catatan}`;
        }
        
        message += "\n\nMohon berikan analisis profesional terhadap hasil lab ini, termasuk interpretasi nilai yang abnormal dan rekomendasi tindakan medis yang diperlukan.";

        console.log('Sending message:', message); // Debug

        const response = await fetch('analisis_ai.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ hasil_lab_id:1 })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Response data:', data); // Debug
        
        if (data.success && data.content) {
            // Format output dengan styling yang lebih baik
            const formattedContent = data.content
                .replace(/\n\n/g, '</p><p>')
                .replace(/\n/g, '<br>')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>');
            
            hasilContent.innerHTML = `
                <div class="border-start border-primary border-4 ps-3">
                    <p>${formattedContent}</p>
                </div>
                <small class="text-muted">
                    <i class="fas fa-clock"></i> Dianalisis pada: ${data.timestamp || new Date().toLocaleString('id-ID')}
                </small>
            `;
            hasilContent.className = 'alert alert-success';
        } else if (data.error) {
            hasilContent.innerHTML = `
                <strong>❌ Error:</strong> ${data.error}
                ${data.response ? `<br><small>Details: ${JSON.stringify(data.response)}</small>` : ''}
            `;
            hasilContent.className = 'alert alert-danger';
        } else {
            hasilContent.innerHTML = '<strong>⚠️ Tidak ada respons dari AI.</strong>';
            hasilContent.className = 'alert alert-warning';
        }
        
    } catch (error) {
        console.error('Error:', error);
        hasilContent.innerHTML = `<strong>❌ Terjadi kesalahan:</strong> ${error.message}`;
        hasilContent.className = 'alert alert-danger';
    } finally {
        // Reset button state
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
    }
});
</script>
</body>
</html>
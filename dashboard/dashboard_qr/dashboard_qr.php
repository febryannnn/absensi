<?php

// function generateDailyCode1() {
//     // Mengambil tanggal saat ini sebagai angka (YYYYMMDD)
//     $today = date('Ymd');
    
//     // Mengambil 6 digit terakhir dari hash tanggal hari ini
//     $hash = crc32($today);  // Menghasilkan hash angka berbasis tanggal
//     $code = substr($hash, -6);  // Mengambil 6 digit terakhir
    
//     // Pastikan kode selalu 6 digit (tambahkan 0 di depan jika kurang dari 6 digit)
//     $code = str_pad($code, 6, '0', STR_PAD_LEFT);
    
//     return $code;
// }

function generateAbsenCode() {
    $today = date('Y-m-d');
    $seed = crc32($today);
    mt_srand($seed);
    
    $numbers = [];
    while (count($numbers) < 6) {
        $number = mt_rand(0, 9);
        if (!in_array($number, $numbers)) {
            $numbers[] = $number;
        }
    }
    
    return implode('', $numbers);
}

// echo generateAbsenCode();


require_once 'vendor/autoload.php';
use PHPQRCode\QRcode;

// Kode yang akan di-generate menjadi QR
$kode = generateAbsenCode();

require 'vendor/autoload.php';

use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;

// Buat QR Code
$qr = EndroidQrCode::create($kode);

// Generate sebagai PNG
$writer = new PngWriter();
$result = $writer->write($qr);
$result->saveToFile('qrcode.png');
// Tampilkan di browser
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code & Entry Code</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #07d4ae, #0453a1);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin: 0;
}

.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 40px;
    width: 100%;
    height:100%;
    text-align: center;
}

.card h2 {
    font-size: 2rem;
    margin-bottom: 15px;
}

.card h3 {
    font-size: 3rem;
    margin-top:50px;
}


.qr-code {
    margin-bottom: 20px;
}

.qr-img {
    width: 400px;
    height: 400px;
    object-fit: cover;
}

form label {
    font-size: 1rem;
    margin-bottom: 10px;
    display: block;
}

form input {
    width: 80%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

form button {
    width: 100%;
    padding: 10px;
    margin:50px 0;
    background-color:rgb(232, 241, 232);
    color: black;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight:600;
    transition: background-color 0.3s;
}

form button:hover {
    background-color:rgb(13, 62, 16);
    color:white;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>QR Code</h2>
            <div class="qr-code">
                <!-- Ganti URL dengan gambar QR Code yang sesuai -->
                <img src="qrcode.png?v=<?php echo time(); ?>" alt="QR Code" class="qr-img">
            </div>
        </div>
    </div>

    <form action="../dashboard.php">
            <button>Back to Dashboard</button>
    </form>
</body>
</html>
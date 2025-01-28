<?php



// function generateDailyCode() {
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

// header('Content-Type: ' . $result->getMimeType());
// echo $result->getString();

// Atau simpan sebagai file
// $result->saveToFile('qrcode.png');


?>

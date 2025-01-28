<?php
echo realpath('tcpdf/tools/tcpdf.php');
// require_once('./tcpdf/tcpdf.php');
// Path relatif ke folder tcpdf
include "../../service/database.php";
session_start();

class AbsensiPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 15);
        $this->Cell(0, 15, 'Laporan Data Absensi Karyawan', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
    
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Halaman '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C');
    }
}

// Inisialisasi PDF
$pdf = new AbsensiPDF('L', 'mm', 'A4');
$pdf->SetCreator('Sistem Absensi');
$pdf->SetAuthor($_SESSION["username"]);
$pdf->SetTitle('Laporan Absensi');

// Add halaman
$pdf->AddPage();

// Set font untuk konten
$pdf->SetFont('helvetica', '', 11);

// Ambil parameter dari URL
$username = $_SESSION["username"];
$tipe_absen = isset($_GET['tipe_absen']) ? $_GET['tipe_absen'] : "hadir";
$date = isset($_GET["tanggal"]) ? $_GET["tanggal"] : '';
$month = isset($_GET["bulan"]) ? $_GET["bulan"] : '';
$year = isset($_GET['tahun']) ? $_GET['tahun'] : '';

// Gunakan query yang sama dengan halaman utama
$sql = "";
switch($tipe_absen) {
    case "izin":
        $sql = "SELECT nama_karyawan AS 'Nama Karyawan', jenis_izin AS Status, 
               tanggal_masuk AS 'Tanggal', waktu_masuk AS Waktu 
               FROM izin WHERE nama_karyawan = '$username'";
        if($date) {
            $sql .= " AND DATE(tanggal_masuk) = '$year-$month-$date'";
        }
        break;
    
    case "sakit":
        $sql = "SELECT nama_karyawan AS 'Nama Karyawan', jenis_izin AS Status, 
               tanggal_masuk AS 'Tanggal', waktu_masuk AS Waktu 
               FROM izin WHERE nama_karyawan = '$username' AND jenis_izin = 'sakit'";
        if($date) {
            $sql .= " AND DATE(tanggal_masuk) = '$year-$month-$date'";
        }
        break;
    
    case "absen_pulang":
        $sql = "SELECT nama_karyawan AS 'Nama Karyawan', 'Hadir Pulang' AS Status, 
               tanggal_masuk AS 'Tanggal', waktu_masuk AS Waktu 
               FROM absensi_pulang WHERE nama_karyawan = '$username'";
        if($date) {
            $sql .= " AND DATE(tanggal_masuk) = '$year-$month-$date'";
        }
        break;
    
    case "semua":
        $sql = "WITH table_union AS (
                SELECT nama_karyawan, 'Hadir Masuk' AS status, tanggal_masuk, waktu_masuk
                FROM absensi WHERE nama_karyawan = '$username'
                UNION
                SELECT nama_karyawan, jenis_izin AS status, tanggal_masuk, waktu_masuk
                FROM izin WHERE nama_karyawan = '$username'
                UNION
                SELECT nama_karyawan, 'Hadir Pulang' AS STATUS, tanggal_masuk, waktu_masuk
                FROM absensi_pulang WHERE nama_karyawan = '$username')
                SELECT * FROM table_union";
        if($date) {
            $sql .= " WHERE DATE(tanggal_masuk) = '$year-$month-$date'";
        }
        $sql .= " ORDER BY tanggal_masuk DESC";
        break;
    
    default: // hadir
        $sql = "SELECT nama_karyawan AS 'Nama Karyawan', 'Hadir Masuk' AS Status, 
               tanggal_masuk AS 'Tanggal', waktu_masuk AS Waktu 
               FROM absensi WHERE nama_karyawan = '$username'";
        if($date) {
            $sql .= " AND DATE(tanggal_masuk) = '$year-$month-$date'";
        }
        $sql .= " ORDER BY tanggal_masuk DESC";
}

$result = $db->query($sql);

// Header Tabel
$header = array('Nama Karyawan', 'Status', 'Tanggal', 'Waktu');
$w = array(60, 40, 40, 40); // width masing-masing kolom

// Colors for rows
$pdf->SetFillColor(240, 240, 240);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(128, 0, 0);
$pdf->SetLineWidth(0.3);

// Header
$pdf->SetFont('helvetica', 'B', 10);
for($i = 0; $i < count($header); $i++) {
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
}
$pdf->Ln();

// Data
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(255, 255, 255);
$fill = false;

while($row = $result->fetch_assoc()) {
    $pdf->Cell($w[0], 6, $row['Nama Karyawan'], 'LR', 0, 'L', $fill);
    $pdf->Cell($w[1], 6, $row['Status'], 'LR', 0, 'L', $fill);
    $pdf->Cell($w[2], 6, $row['Tanggal'], 'LR', 0, 'C', $fill);
    $pdf->Cell($w[3], 6, $row['Waktu'], 'LR', 0, 'C', $fill);
    $pdf->Ln();
    $fill = !$fill;
}
$pdf->Cell(array_sum($w), 0, '', 'T');

// Output PDF
$pdf->Output('laporan_absensi.pdf', 'D');
?>
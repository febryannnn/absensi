<?php
include "../../service/database.php";
session_start();

// include "dashboard_absensi.html";

$username = $_SESSION["username"];

$sql_DEFAULT = "SELECT nama_karyawan AS 'Nama Karyawan', 'Hadir Masuk' 
        AS Status, tanggal_masuk AS 'Tanggal', waktu_masuk AS Waktu 
        FROM absensi WHERE nama_karyawan = '$username' ORDER BY tanggal_masuk DESC" ;
$result = $db->query($sql_DEFAULT);

$tipe_absen = isset($_POST['tipe_absen']) ? $_POST['tipe_absen'] : "hadir";


    if ($tipe_absen == "izin") {
        $sql_izin = "SELECT nama_karyawan AS 'Nama Karyawan', jenis_izin 
        AS Status, tanggal_masuk AS 'Tanggal', waktu_masuk 
        AS Waktu FROM izin WHERE nama_karyawan = '$username'";
        $result = $db->query($sql_izin);
        
        if (isset($_POST["cari"])) {
            $date = isset($_POST["tanggal"]) ? $_POST["tanggal"] : '';
            $month = isset($_POST["bulan"]) ? $_POST["bulan"] : '';
            $year = isset($_POST['tahun']) ? $_POST['tahun'] : '';
            

            $sql_izin .= " AND DATE(tanggal_masuk) = '$year-$month-$date'";
            
            
            $result = $db->query($sql_izin);

        }
    }

    else if ($tipe_absen == "hadir") {
        // $sql_hadir = "SELECT * FROM absensi WHERE nama_karyawan LIKE '%$nama%'";
        $sql_hadir = "SELECT nama_karyawan AS 'Nama Karyawan', 'Hadir Masuk' 
        AS Status, tanggal_masuk AS 'Tanggal', waktu_masuk AS Waktu 
        FROM absensi WHERE nama_karyawan = '$username' ORDER BY tanggal_masuk DESC";
        $result = $db->query($sql_hadir);

        if (isset($_POST["cari"])) {
            $date = isset($_POST["tanggal"]) ? $_POST["tanggal"] : '';
            $month = isset($_POST["bulan"]) ? $_POST["bulan"] : '';
            $year = isset($_POST['tahun']) ? $_POST['tahun'] : '';
            
            // Menambahkan kondisi berdasarkan input
                // Jika tanggal, bulan, dan tahun diberikan
            $sql_hadir .= " AND DATE(tanggal_masuk) = '$year-$month-$date'";
            
            
            $result = $db->query($sql_hadir);

        }
    } 

    else if ($tipe_absen == "absen_pulang") {
        // $sql_hadir = "SELECT * FROM absensi WHERE nama_karyawan LIKE '%$nama%'";
        $sql_hadir = "SELECT nama_karyawan AS 'Nama Karyawan', 'Hadir Pulang' 
        AS Status, tanggal_masuk AS 'Tanggal', waktu_masuk AS Waktu 
        FROM absensi_pulang WHERE nama_karyawan = '$username' ORDER BY tanggal_masuk DESC";
        $result = $db->query($sql_hadir);

        if (isset($_POST["cari"])) {
            $date = isset($_POST["tanggal"]) ? $_POST["tanggal"] : '';
            $month = isset($_POST["bulan"]) ? $_POST["bulan"] : '';
            $year = isset($_POST['tahun']) ? $_POST['tahun'] : '';
            
            // Menambahkan kondisi berdasarkan input
                // Jika tanggal, bulan, dan tahun diberikan
            $sql_hadir .= " AND DATE(tanggal_masuk) = '$year-$month-$date'";
            
            
            $result = $db->query($sql_hadir);

        }
    } 

    else if ($tipe_absen == "sakit") {
        $sql_sakit = "SELECT nama_karyawan AS 'Nama Karyawan', jenis_izin 
        AS Status, tanggal_masuk AS 'Tanggal', waktu_masuk AS Time FROM izin
        WHERE nama_karyawan ='$username' AND jenis_izin = 'sakit'"; 
        // $sql_hadir = "SELECT * FROM izin WHERE jenis_izin = 'sakit' AND nama_karyawan LIKE '%$nama%'";
        $result = $db->query($sql_sakit);

        if (isset($_POST["cari"])) {
            $date = isset($_POST["tanggal"]) ? $_POST["tanggal"] : '';
            $month = isset($_POST["bulan"]) ? $_POST["bulan"] : '';
            $year = isset($_POST['tahun']) ? $_POST['tahun'] : '';
            

            $sql_sakit .= " AND DATE(tanggal_masuk) = '$year-$month-$date'";
            
            
            $result = $db->query($sql_sakit);

        }
    } 

    else if ($tipe_absen == "semua") {
        $sql_all = "WITH table_union AS (SELECT nama_karyawan, 'Hadir Masuk' AS status, tanggal_masuk, waktu_masuk
                    FROM absensi WHERE nama_karyawan = '$username'
                    UNION
                    SELECT nama_karyawan, jenis_izin AS status, tanggal_masuk, waktu_masuk
                    FROM izin WHERE nama_karyawan = '$username'
                    UNION
                    SELECT nama_karyawan, 'Hadir Pulang' AS STATUS, tanggal_masuk, waktu_masuk
                    FROM absensi_pulang WHERE nama_karyawan = '$username')
                    SELECT * FROM table_union ORDER BY tanggal_masuk DESC";
        $result = $db->query($sql_all);

        if (isset($_POST["cari"])) {

            
                $date = isset($_POST["tanggal"]) ? $_POST["tanggal"] : '';
                $month = isset($_POST["bulan"]) ? $_POST["bulan"] : '';
                $year = isset($_POST['tahun']) ? $_POST['tahun'] : '';
                if ($date) {

                    $sql_all .= "WHERE DATE(tanggal_masuk) = '$year-$month-$date'";
                    $result = $db->query($sql_all);
                }
            

        }
    }

    else {
        
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="container">
        <h1>Data Absensi Saya</h1>
        <div class="fitur">
        <div class="download-btn">
    <a href="download_pdf.php?tipe_absen=<?php echo $tipe_absen; ?>&tanggal=<?php echo isset($_POST['tanggal']) ? $_POST['tanggal'] : ''; ?>&bulan=<?php echo isset($_POST['bulan']) ? $_POST['bulan'] : ''; ?>&tahun=<?php echo isset($_POST['tahun']) ? $_POST['tahun'] : ''; ?>" class="btn-download">Download PDF</a>
    </div>
            <form action="dashboard_absen_saya.php" method="post" id="absenForm">
                <div>
                    
                    <label for="tipe_absen">Pilh Tipe Absensi:</label>
                    <select id="tipe_absen" name="tipe_absen" onchange="submitForm()">
                        <option value="default" <?php if ($tipe_absen === 'default') echo 'selected '; ?>>Pilih</option>
                        <option value="hadir" <?php if ($tipe_absen === 'hadir') echo 'selected '; ?>>Hadir</option>
                        <option value="izin" <?php if ($tipe_absen === 'izin') echo 'selected '; ?>>Izin</option>
                        <option value="sakit" <?php if ($tipe_absen === 'sakit') echo 'selected '; ?>>Sakit</option>
                        <option value="absen_pulang" <?php if ($tipe_absen === 'absen_pulang') echo 'selected '; ?>>Absen Pulang</option>
                        <option value="semua" <?php if ($tipe_absen === 'semua') echo 'selected '; ?>>Semua</option>
                    </select>
                    <label for="tanggal">Tanggal:</label>
                    <input type="text" id="tanggal" name="tanggal" placeholder="Enter Date">
                    <label for="bulan">Bulan:</label>
                    <select name="bulan" id="bulan">
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                    <label for="tahun">Tahun:</label>
                    <select name="tahun" id="tahun">
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                    </select>
                    <button  name="cari" id="cari">cari</button>
                </div>
            </form>
        </div>
        <div class="table-container">
            <?php
            
            // Cek apakah ada hasil
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr>";
                // Menampilkan header tabel (nama kolom)
                while ($field_info = $result->fetch_field()) {
                    echo "<th>" . htmlspecialchars($field_info->name) . "</th>";
                }
                echo "</tr>";

                // Menampilkan semua baris data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $column) {
                        echo "<td>" . htmlspecialchars($column) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='no-data'>Data tidak ditemukan.</p>";
            }
            ?>
        </div>
        <a href="../dashboard_user.php" class="home">Back to Home</a>
    </div>
    <script>
        function submitForm() {
        document.getElementById("absenForm").submit();
    }
    </script>
</body>
</html>
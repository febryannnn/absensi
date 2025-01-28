<?php
include "../../service/database.php";
session_start();

// include "dashboard_absensi.html";

$username = $_SESSION["username"];
$nama = isset($_POST['search']) ? $_POST['input_string'] : '';

$sql_DEFAULT = "SELECT id_karyawan AS 'ID Karyawan', nama_karyawan AS 'Nama Karyawan','Hadir Masuk' 
        AS Status, tanggal_masuk AS 'Tanggal', waktu_masuk AS Waktu 
        FROM absensi WHERE nama_karyawan LIKE '%$nama%' ORDER BY tanggal_masuk DESC";
$result = $db->query($sql_DEFAULT);

$tipe_absen = isset($_POST['tipe_absen']) ? $_POST['tipe_absen'] : "hadir";


    if ($tipe_absen == "izin") {
        $sql_izin = "SELECT id_karyawan AS 'ID Karyawan',nama_karyawan AS 'Nama Karyawan', jenis_izin 
        AS Status, tanggal_masuk AS 'Tanggal', waktu_masuk 
        AS Waktu FROM izin WHERE nama_karyawan LIKE '%$nama%' ";
        $result = $db->query($sql_izin);
        
        if (isset($_POST["cari"])) {
            $date = isset($_POST["tanggal"]) ? $_POST["tanggal"] : '';
            $month = isset($_POST["bulan"]) ? $_POST["bulan"] : '';
            $year = isset($_POST['tahun']) ? $_POST['tahun'] : '';
            

            $sql_izin .= "AND DATE(tanggal_masuk) = '$year-$month-$date' ORDER BY tanggal_masuk DESC";
            
            
            $result = $db->query($sql_izin);

        } else {
            $sql_izin .= "ORDER BY tanggal_masuk DESC";
        }
    }

    else if ($tipe_absen == "hadir") {
        // $sql_hadir = "SELECT * FROM absensi WHERE nama_karyawan LIKE '%$nama%'";
        $sql_hadir = "SELECT id_karyawan AS 'ID Karyawan', nama_karyawan AS 'Nama Karyawan', 'Hadir Masuk' 
        AS Status, tanggal_masuk AS 'Tanggal', waktu_masuk AS Waktu 
        FROM absensi WHERE nama_karyawan LIKE '%$nama%'";
        $result = $db->query($sql_hadir);

        if (isset($_POST["cari"])) {
            $date = isset($_POST["tanggal"]) ? $_POST["tanggal"] : '';
            $month = isset($_POST["bulan"]) ? $_POST["bulan"] : '';
            $year = isset($_POST['tahun']) ? $_POST['tahun'] : '';
            
            // Menambahkan kondisi berdasarkan input
                // Jika tanggal, bulan, dan tahun diberikan
            $sql_hadir .= " AND DATE(tanggal_masuk) = '$year-$month-$date' ORDER BY tanggal_masuk DESC";
            
            
            $result = $db->query($sql_hadir);

        } else {
            $sql_hadir .= " ORDER BY tanggal_masuk DESC";
        }
    } 

    else if ($tipe_absen == "sakit") {
        $sql_sakit = "SELECT id_karyawan AS 'ID Karyawan', nama_karyawan AS 'Nama Karyawan', jenis_izin 
        AS Status, tanggal_masuk AS 'Tanggal', waktu_masuk AS Time FROM izin
        WHERE jenis_izin = 'sakit' AND nama_karyawan LIKE '%$nama%'"; 
        // $sql_hadir = "SELECT * FROM izin WHERE jenis_izin = 'sakit' AND nama_karyawan LIKE '%$nama%'";
        $result = $db->query($sql_sakit);

        if (isset($_POST["cari"])) {
            $date = isset($_POST["tanggal"]) ? $_POST["tanggal"] : '';
            $month = isset($_POST["bulan"]) ? $_POST["bulan"] : '';
            $year = isset($_POST['tahun']) ? $_POST['tahun'] : '';
            

            $sql_sakit .= " AND DATE(tanggal_masuk) = '$year-$month-$date' ORDER BY tanggal_masuk DESC";
            
            
            $result = $db->query($sql_sakit);

        } else {
            $sql_sakit .= " ORDER BY tanggal_masuk DESC";
        }
    } 

    else if ($tipe_absen == "semua") {
        $sql_all = "WITH table_union AS (SELECT id_karyawan AS 'ID Karyawan',nama_karyawan, 'Hadir Masuk' AS status, tanggal_masuk, waktu_masuk
                    FROM absensi
                    UNION
                    SELECT id_karyawan AS 'ID Karyawan', nama_karyawan, jenis_izin AS status, tanggal_masuk, waktu_masuk
                    FROM izin
                    UNION
                    SELECT id_karyawan AS 'ID Karyawan', nama_karyawan, 'Hadir Pulang' AS STATUS, tanggal_masuk, waktu_masuk
                    FROM absensi_pulang)
                    SELECT * FROM table_union WHERE nama_karyawan LIKE '%$nama%'";
        $result = $db->query($sql_all);

        if (isset($_POST["cari"])) {

            
                $date = isset($_POST["tanggal"]) ? $_POST["tanggal"] : '';
                $month = isset($_POST["bulan"]) ? $_POST["bulan"] : '';
                $year = isset($_POST['tahun']) ? $_POST['tahun'] : '';
                if ($date) {

                    $sql_all .= "AND DATE(tanggal_masuk) = '$year-$month-$date' ORDER BY tanggal_masuk DESC";
                    $result = $db->query($sql_all);
                }
            

        } else {
            $sql_all .= "ORDER BY tanggal_masuk DESC";
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
    <link rel="stylesheet" href="style.css?v=1">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
</head>
<body>
    <div class="container">
        <h1>Data Absensi Saya</h1>
        <div class="fitur">
            <form action="dashboard_absen.php" method="post" id="absenForm" class="forms">
                <div class="search-group">
                    <div class="filter-group">
                        <input type="text" 
                               placeholder="Cari nama karyawan" 
                               name="input_string" 
                               value="<?php echo isset($_POST['input_string']) ? htmlspecialchars($_POST['input_string']) : ''; ?>">
                        <button type="submit" name="search" id="search">Cari</button>

                        <div class="select-group">
                            <label for="tipe_absen">Tipe Absensi:</label>
                            <select id="tipe_absen" name="tipe_absen" onchange="submitForm()">
                                <option value="default" <?php if ($tipe_absen === 'default') echo 'selected '; ?>>Pilih</option>
                                <option value="hadir" <?php if ($tipe_absen === 'hadir') echo 'selected '; ?>>Hadir</option>
                                <option value="izin" <?php if ($tipe_absen === 'izin') echo 'selected '; ?>>Izin</option>
                                <option value="sakit" <?php if ($tipe_absen === 'sakit') echo 'selected '; ?>>Sakit</option>
                                <option value="semua" <?php if ($tipe_absen === 'semua') echo 'selected '; ?>>Semua</option>
                            </select>
                        </div>

                        <div class="date-group">
                            <div class="input-date">
                                <label for="tanggal">Tanggal:</label>
                                <input type="text" id="tanggal" name="tanggal" placeholder="Enter Date">
                            </div>

                            <div class="select-date">
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
                            </div>

                            <div class="select-date">
                                <label for="tahun">Tahun:</label>
                                <select name="tahun" id="tahun">
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" name="cari" id="cari">Cari</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-container">
                <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <?php while ($field_info = $result->fetch_field()): ?>
                                <th><?php echo htmlspecialchars($field_info->name); ?></th>
                            <?php endwhile; ?>
                            <th>Delete / Update</th> <!-- New column for actions -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <?php foreach ($row as $column): ?>
                                    <td><?php echo htmlspecialchars($column); ?></td>
                                <?php endforeach; ?>
                                <td class="action-buttons">
                                    <!-- Assuming you have an ID field in your table -->
                                    <!-- <a href="update.php?id=<?php echo htmlspecialchars($row['id']); ?>"  -->
                                    <a href="#"
                                    class="btn btn-update">Update</a>
                                    <a href="#" 
                                    class="btn btn-delete" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="no-data">Data tidak ditemukan.</p>
                <?php endif; ?>
        </div>

        <a href="../dashboard.php" class="home">Back to Home</a>
    </div>

    <script>
        function submitForm() {
            document.getElementById("absenForm").submit();
        }
    </script>
</body>
</html>


<?php
include_once("../../../config/conn.php");
session_start();

if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}
$id_pasien = $_SESSION['id'];
$no_rm = $_SESSION['no_rm'];
$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$id_poli = $url[count($url) - 1];

if ($akses != 'pasien') {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}
?>

<?php
$title = 'Poliklinik | Tambah Jadwal Periksa';

// Breadcrumb Section
ob_start();?>
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="<?=$base_pasien;?>">Home</a></li>
  <li class="breadcrumb-item"><a href="<?=$base_pasien . '/poli';?>">Poli</a></li>
  <li class="breadcrumb-item active">Detail Poli</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start();?>
Detail Poli
<?php
$main_title = ob_get_clean();
ob_flush();

// Content Section
ob_start();?>
<div class="card">
  <div class="card-body">
  <?php
                    $poli = $pdo->prepare("SELECT d.nama_poli as poli_nama,
                                                  c.nama as dokter_nama, 
                                                  e.nama as pasien_nama, 
                                                  b.hari as jadwal_hari, 
                                                  b.jam_mulai as jadwal_mulai, 
                                                  b.jam_selesai as jadwal_selesai,
                                                  a.no_antrian as antrian,
                                                  a.id as poli_id

                                                  FROM daftar_poli as a

                                                  INNER JOIN jadwal_periksa as b
                                                    ON a.id_jadwal = b.id
                                                  INNER JOIN dokter as c
                                                    ON b.id_dokter = c.id
                                                  INNER JOIN poli as d
                                                    ON c.id_poli = d.id
                                                  INNER JOIN pasien as e
                                                    ON a.id_pasien = e.id
                                                  WHERE a.id = $id_poli");
                    $poli->execute();
                    $no = 0;
                    if ($poli->rowCount() == 0) {
                      echo "Tidak da data";
                    } else {
                      while($p = $poli->fetch()) {
                    ?>
                      <button id="printButton" class="btn btn-primary">Cetak NO Antrian</button>
                      <div id="nota">
                      <div class="card text-center">
                          <div class="card-header d-flex">
                            <div class="card-header">
                              <strong>Poliklinik UDINUS</strong>
                            </div>
                            <a href="#" class="btn btn-warning text-white"><strong>No Antrian <?= $p['antrian']?></strong></a>
                          </div>
                          <div class="col">
                            <div class="row gx-5 mt-2">
                              <h5 class="card-title col">Nama Poli</h5>
                              <h5 class="card-title col mb-2">:</h5>
                              <h5 class="card-title col mb-2"> <?= $p['poli_nama']?></h5>
                            </div>
                            <div class="row gx-5">
                              <h5 class="card-title col mb-2">Nama Dokter</h5>
                              <h5 class="card-title col mb-2">:</h5>
                              <h5 class="card-title col mb-2"><?= $p['dokter_nama']?></h5>
                            </div>
                            <div class="row gx-5">
                              <h5 class="card-title col mb-2">Nama Pasien</h5>
                              <h5 class="card-title col mb-2">:</h5>
                              <h5 class="card-title col mb-2"><?= $p['pasien_nama']?></h5>
                            </div>
                            <div class="row gx-5">
                              <h5 class="card-title col mb-2">Hari</h5>
                              <h5 class="card-title col mb-2">:</h5>
                              <h5 class="card-title col mb-2"><?= $p['jadwal_hari']?></h5>
                            </div>
                            <div class="row gx-5">
                              <h5 class="card-title col mb-2">Mulai</h5>
                              <h5 class="card-title col mb-2">:</h5>
                              <h5 class="card-title col mb-2"><?= $p['jadwal_mulai']?></h5>
                            </div>
                            <div class="row gx-5">
                              <h5 class="card-title col mb-2">Selesai</h5>
                              <h5 class="card-title col mb-2">:</h5>
                              <h5 class="card-title col mb-2"><?= $p['jadwal_selesai']?></h5>
                            </div>
                          </div>
                          <div class="card-footer text-body-secondary">
                            Semoga Lekas Sembuh :)
                          </div>
                      </div>
                      </div>
                   

                    <?php
                      }
                    }
                    ?>
  </div>
</div>
<script>
    // Fungsi untuk mencetak nota
    function cetakNota() {
        // Dapatkan elemen dengan id 'nota'
        var notaElement = document.getElementById('nota');

        // Salin konten nota untuk dicetak
        var notaContent = notaElement.outerHTML;

        // Buka jendela cetak baru
        var printWindow = window.open('', '_blank');
        
        // Tambahkan konten nota ke jendela cetak
        printWindow.document.write('<html><head><title>Nota Cetak</title></head><body>');
        printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">'); // Tambahkan link ke file Bootstrap jika diperlukan
        printWindow.document.write(notaContent);
        printWindow.document.write('</body></html>');

        // Tutup jendela cetak setelah mencetak
        printWindow.document.close();
        printWindow.print();
    }

    // Tangani klik pada tombol cetak
    document.getElementById('printButton').addEventListener('click', cetakNota);
</script>
<?php
$content = ob_get_clean();
ob_flush();
?>

<?php include_once "../../../layouts/index.php";?>
<?php
include_once "../../../config/conn.php";
session_start();

if (isset($_SESSION['login'])) {
    $_SESSION['login'] = true;
} else {
    echo "<meta http-equiv='refresh' content='0; url=..'>";
    die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];
$id_dokter = $_SESSION['id'];

if ($akses != 'dokter') {
    echo "<meta http-equiv='refresh' content='0; url=..'>";
    die();
}

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$id = $url[count($url) - 1];

$jadwal = query("SELECT * FROM jadwal_periksa WHERE id = $id")[0];

// Input data to db
if (isset($_POST["submit"])) {
    // Cek validasi

    
    // if (empty($_POST["hari"]) || empty($_POST["jam_mulai"]) || empty($_POST["jam_selesai"])) {
              // cek apakah data berhasil di tambahkan atau tidak
        if (updateJadwalPeriksa($_POST, $id) > 0) {
            echo "
            <script>
                alert('Data berhasil ditambahkan');
                document.location.href = '../';
            </script>
        ";
        echo "
          <script>
              alert('Data tidak boleh kosong');
              document.location.href = '../jadwal_periksa/edit.php';
          </script>
      ";
        die;
    } else {
        // cek apakah data berhasil di tambahkan atau tidak
        if (updateJadwalPeriksa($_POST, $id) > 0) {
            echo "
            <script>
                alert('Data berhasil ditambahkan');
                document.location.href = '../';
            </script>
        ";
        } else {
            echo "
            <script>
                alert('Data Gagal ditambahkan');
                document.location.href = '../';
            </script>
        ";
        }
    }
}
?>

<?php
$title = 'Poliklinik | Edit Jadwal Periksa';

// Breadcrumb Section
ob_start();?>
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="<?=$base_dokter;?>">Home</a></li>
  <li class="breadcrumb-item"><a href="<?=$base_dokter . '/jadwal_periksa';?>">Jadwal Periksa</a></li>
  <li class="breadcrumb-item active">Edit Jadwal Periksa</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start();?>
Edit Jadwal Periksa
<?php
$main_title = ob_get_clean();
ob_flush();

// Content Section
ob_start();?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Tambah Jadwal Periksa</h3>
  </div>
  <div class="card-body">
    <form action="" id="tambahJadwal" method="POST">
      <input type="hidden" name="id_dokter" value="<?=$id_dokter?>">
      <div class="form-group">
        <label for="hari">Hari</label>
        <select name="hari" id="hari" class="form-control" disabled>
          <option hidden>-- Pilih Hari --</option>
          <?php
            $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            foreach ($hari as $h): ?>
            <?php if ($h == $jadwal['hari']): ?>
              <option value="<?=$h?>" selected><?=$h?></option>
            <?php else: ?>
              <option value="<?=$h?>"><?=$h?></option>
            <?php endif;?>
        <?php endforeach;?>
        </select>
      </div>
      <div class="form-group">
        <label for="jam_mulai">Jam Mulai</label>
        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="<?= date('H:i', strtotime($jadwal['jam_mulai'])) ?>" disabled>
      </div>
      <div class="form-group">
        <label for="jam_selesai">Jam Selesai</label>
        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="<?=date('H:i', strtotime($jadwal['jam_selesai']))?>" disabled>
      </div>
      <div class="form-group ml-3">
        <label for="jam_selesai">Status</label>
        <br/>
        <!-- <input type="" name="status" id="jam_selesai" class="form-control"> -->
        <input class="form-check-input" type="radio" name="status" id="flexRadioDefault1" value="Y">
        <label class="form-check-label" for="flexRadioDefault1">
           Y
        </label>
        <br/>
        <input class="form-check-input" type="radio" name="status" id="flexRadioDefault2" value="N" checked>
         <label class="form-check-label" for="flexRadioDefault2">
         N
        </label>
      </div>
      <div class="d-flex justify-content-end">
        <button type="submit" name="submit" id="submitButton" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>
<?php
$content = ob_get_clean();
ob_flush();

// JS Section
ob_start();?>
<script>
  let jam_mulai = $('#jam_mulai');
  let jam_selesai = $('#jam_selesai');

  $('#tambahJadwal').submit(function (e) {
    if (jam_mulai.value >= jam_selesai.value) {
      e.preventDefault();
      alert('Jam mulai tidak boleh lebih dari jam selesai');
    }
  });

</script>
<?php
$js = ob_get_clean();
ob_flush();
?>

<?php include_once "../../../layouts/index.php";?>
<?php
include_once("../../../config/conn.php");
session_start();

if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=../auth/login.php'>";
  die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

if ($akses != 'admin') {
  echo "<meta http-equiv='refresh' content='0; url=../..'>";
  die();
}
?>
<?php
$title = 'Poliklinik | Poli';
// Breadcrumb section
ob_start();?>
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="<?= $base_admin; ?>">Home</a></li>
  <li class="breadcrumb-item active">Obat</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start();?>
Kelola Data Poli
<?php
$main_title = ob_get_clean();
ob_flush();

// Content section
ob_start();
?>
<form class="form col" method="POST" action="" name="myForm" onsubmit="return(validate());">
        <?php
        $nama_poli = '';
        $keterangan = '';
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM poli WHERE id = :id");
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $nama_poli = $row['nama_poli'];
                    $keterangan = $row['keterangan'];
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        ?>
        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
        <?php
        }
        ?>
        <div class="row mt-3">
            <label for="namaPoli" class="form-label fw-bold">
                Nama Poli
            </label>
            <input type="text" class="form-control" name="nama_poli" id="namaPoli" placeholder="Nama Poli" value="<?php echo $nama_poli ?>">
        </div>
        <div class="row mt-3">
            <label for="Keterangan" class="form-label fw-bold">
                Keterangan
            </label>
            <input type="text" class="form-control" name="keterangan" id="Keterangan" placeholder="Keterangan" value="<?php echo $keterangan ?>">
        </div>


        <div class="row mt-3">
        <div class="row d-flex mt-3 mb-3 mr-3 ml-1">
          <button type="submit" class="btn btn-success" style="width: 3cm;" name="simpan">Save</button>
        </div>
        <div class="row d-flex mt-3 mb-3">
          <a href="<?= $base_admin.'/poli' ?>">
            <button class="btn btn-danger ml-2" style="width: 3cm;">Reset</button>
           </a>
        </div>
        </div>
</form>


<div class="card">
  <div class="card-body">
    <table id="example1" class="table table-striped">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama Poli</th>
          <th scope="col">Keterangan</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $pdo->query("SELECT * FROM poli");
        $no = 1;
        while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
              <td><?php echo $no++ ?></td>
              <td><?php echo $data['nama_poli'] ?></td>
              <td><?php echo $data['keterangan'] ?></td>
              <td>
                  <a class="btn btn-success" href="index.php?page=poli&id=<?php echo $data['id'] ?>">Ubah</a>
                  <a class="btn btn-danger" href="index.php?page=poli&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
              </td>
          </tr>
      <?php
      }
      ?>
      </tbody>
    </table>
    <?php
        if (isset($_POST['simpan'])) {
        if (isset($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE poli SET 
                                    nama_poli = :nama_poli,
                                    keterangan = :keterangan
                                    WHERE
                                    id = :id");

            $stmt->bindParam(':nama_poli', $_POST['nama_poli'], PDO::PARAM_STR);
            $stmt->bindParam(':keterangan', $_POST['keterangan'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $stmt->execute();

            header('Location:index.php');

        } else {
            $stmt = $pdo->prepare("INSERT INTO poli(nama_poli, keterangan) 
                                    VALUES (:nama_poli, :keterangan)");

            $stmt->bindParam(':nama_poli', $_POST['nama_poli'], PDO::PARAM_STR);
            $stmt->bindParam(':keterangan', $_POST['keterangan'], PDO::PARAM_STR);
            $stmt->execute();

            header('Location:index.php');
        }
    }
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $stmt = $pdo->prepare("DELETE FROM poli WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();

    header('Location:index.php');
    
    }
    ?>
  </div>
</div>
<?php
$content = ob_get_clean();
ob_flush();
?>

<?php include '../../../layouts/index.php'; ?>
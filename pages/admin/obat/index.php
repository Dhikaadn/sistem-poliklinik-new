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
$title = 'Poliklinik | Obat';
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
Kelola Data Obat
<?php
$main_title = ob_get_clean();
ob_flush();

// Content section
ob_start();
?>
<form class="form col" method="POST" action="" name="myForm" onsubmit="return(validate());">
        <?php
        $nama_obat = '';
        $kemasan = '';
        $harga = '';
        $status = '';

        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM obat WHERE id = :id");
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $nama_obat = $row['nama_obat'];
                    $kemasan = $row['kemasan'];
                    $harga = $row['harga'];
                    $status = $row['status'];

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
            <label for="namaObat" class="form-label fw-bold">
                Nama Obat
            </label>
            <input type="text" class="form-control" name="nama_obat" id="namaObat" placeholder="Nama Obat" value="<?php echo $nama_obat ?>">
        </div>
        <div class="row mt-3">
            <label for="Kemasan" class="form-label fw-bold">
                Kemasan
            </label>
            <input type="text" class="form-control" name="kemasan" id="Kemasan" placeholder="Kemasan" value="<?php echo $kemasan ?>">
        </div>
        <div class="row mt-3">
            <label for="Harga" class="form-label fw-bold">
                Harga
            </label>
            <input type="text" class="form-control" name="harga" id="Harga" placeholder="Harga" value="<?php echo $harga ?>">
        </div>
        <div class="row mt-3 d-flex flex-column">
            <label for="Status" class="form-label fw-bold">
                Status
            </label>
            <div class="pl-4">
              <input class="form-check-input" type="radio" name="status" id="flexRadioDefault1" value="Y">
              <label class="form-check-label" for="flexRadioDefault1">
              Y
              </label>
            </div>
            <div class="pl-4">
              <input class="form-check-input" type="radio" name="status" id="flexRadioDefault2" value="N" checked>
              <label class="form-check-label" for="flexRadioDefault2">
              N
              </label>  
            </div>
        </div>


        <div class="row mt-3">
        <div class="row d-flex mt-3 mb-3 mr-3 ml-1">
          <button type="submit" class="btn btn-success" style="width: 3cm;" name="simpan">Save</button>
        </div>
        <div class="row d-flex mt-3 mb-3">
          <a href="<?= $base_admin.'/obat' ?>">
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
          <th scope="col">Nama Obat</th>
          <th scope="col">Kemasan</th>
          <th scope="col">Harga</th>
          <th scope="col">Status</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $pdo->query("SELECT * FROM obat");
        $no = 1;
        while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
              <td><?php echo $no++ ?></td>
              <td><?php echo $data['nama_obat'] ?></td>
              <td><?php echo $data['kemasan'] ?></td>
              <td><?php echo $data['harga'] ?></td>
              <td><?php echo $data['status'] ?></td>
              <td>
                  <a class="btn btn-success" href="index.php?page=obat&id=<?php echo $data['id'] ?>">Ubah</a>
                  <a class="btn btn-danger" href="index.php?page=obat&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
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
            $stmt = $pdo->prepare("UPDATE obat SET 
                                    nama_obat = :nama_obat,
                                    kemasan = :kemasan,
                                    harga = :harga,
                                    status = :status
                                    WHERE
                                    id = :id");

            $stmt->bindParam(':nama_obat', $_POST['nama_obat'], PDO::PARAM_STR);
            $stmt->bindParam(':kemasan', $_POST['kemasan'], PDO::PARAM_STR);
            $stmt->bindParam(':harga', $_POST['harga'], PDO::PARAM_INT);
            $stmt->bindParam(':status', $_POST['status'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $stmt->execute();

            header('Location:index.php');

        } else {
            $stmt = $pdo->prepare("INSERT INTO obat(nama_obat, kemasan, harga, status) 
                                    VALUES (:nama_obat, :kemasan, :harga, :status)");

            $stmt->bindParam(':nama_obat', $_POST['nama_obat'], PDO::PARAM_STR);
            $stmt->bindParam(':kemasan', $_POST['kemasan'], PDO::PARAM_STR);
            $stmt->bindParam(':harga', $_POST['harga'], PDO::PARAM_INT);
            $stmt->bindParam(':status', $_POST['status'], PDO::PARAM_STR);
            $stmt->execute();

            header('Location:index.php');
        }
    }
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $stmt = $pdo->prepare("DELETE FROM obat WHERE id = :id");
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
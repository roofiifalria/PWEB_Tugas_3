<?php

  // Konfigurasi koneksi ke database
  define("DB_HOST", "127.0.0.1");
  define("DB_USER", "root");
  define("DB_PASSWORD", ""); // Ganti dengan password database Anda jika ada
  define("DB_DATABASE", "fp");
  define("DB_PORT", 8111); // Ganti dengan port database Anda jika berbeda
  
  // Membuat koneksi ke database
  $db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE, DB_PORT);
  
  // Memeriksa koneksi
  if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
  }
  
  // Logika untuk insert data baru
  if(isset($_POST['submit']) && !isset($_GET['hal'])) {
    // Ambil data dari form
    $kodeService = $_POST['kodeService'];
    $tanggalService = $_POST['tanggalService'];
    $deskripsiKerusakan = $_POST['deskripsiKerusakan'];
    $estimasiBiaya = $_POST['estimasiBiaya'];
    $status = $_POST['status'];
    
    // Query SQL untuk insert data baru
    $query_insert = "INSERT INTO layanan (id_service, tanggal_service, deskripsi_kerusakan, estimasi_biaya, status)
                     VALUES ('$kodeService', '$tanggalService', '$deskripsiKerusakan', '$estimasiBiaya', '$status')";
    
    // Eksekusi query insert
    $result_insert = mysqli_query($db, $query_insert);
    
    // Periksa hasil eksekusi query
    if($result_insert) {
      echo "<script>
              alert('Data berhasil disimpan!');
              window.location.href = 'index.php';
            </script>";
    } else {
      echo "<script>
              alert('Data gagal disimpan: " . mysqli_error($db) . "');
              window.location.href = 'index.php';
            </script>";
    }
  }
  
  // Logika untuk proses edit data
  if(isset($_GET['hal']) && $_GET['hal'] == "edit") {
    if(isset($_GET['id'])) {
      $id_service = $_GET['id'];
      
      // Mengambil data dari database untuk di-edit
      $query_edit = "SELECT * FROM layanan WHERE id_service = '$id_service'";
      $result_edit = mysqli_query($db, $query_edit);
      
      if(mysqli_num_rows($result_edit) > 0) {
        $data = mysqli_fetch_assoc($result_edit);
        $vkode = $data['id_service'];
        $vdesc = $data['deskripsi_kerusakan'];
        $vtgl = $data['tanggal_service'];
        $vest = $data['estimasi_biaya'];
        $vstatus = $data['status'];
      } else {
        echo "<script>
                alert('Data tidak ditemukan.');
                window.location.href = 'index.php';
              </script>";
      }
    } else {
      echo "<script>
              alert('Parameter ID tidak valid.');
              window.location.href = 'index.php';
            </script>";
    }
  }
  
  // Logika untuk update data
  if(isset($_POST['update']) && isset($_GET['hal']) && $_GET['hal'] == "edit") {
    // Ambil data dari form
    $kodeService = $_POST['kodeService'];
    $tanggalService = $_POST['tanggalService'];
    $deskripsiKerusakan = $_POST['deskripsiKerusakan'];
    $estimasiBiaya = $_POST['estimasiBiaya'];
    $status = $_POST['status'];
    
    // Ambil ID service yang akan di-update
    $id_service = $_GET['id'];
    
    // Query SQL untuk update data
    $query_update = "UPDATE layanan SET
                      id_service = '$kodeService',
                      deskripsi_kerusakan = '$deskripsiKerusakan',
                      tanggal_service = '$tanggalService',
                      estimasi_biaya = '$estimasiBiaya',
                      status = '$status'
                    WHERE id_service = '$id_service'";
    
    // Eksekusi query update
    $result_update = mysqli_query($db, $query_update);
    
    // Periksa hasil eksekusi query
    if($result_update) {
      echo "<script>
              alert('Data berhasil diupdate!');
              window.location.href = 'index.php';
            </script>";
    } else {
      echo "<script>
              alert('Data gagal diupdate: " . mysqli_error($db) . "');
              window.location.href = 'index.php';
            </script>";
    }
  }
  
  // Logika untuk pencarian data
  if(isset($_POST['bcari'])) {
    $tcari = $_POST['tcari'];
    $query = "SELECT * FROM layanan WHERE id_service LIKE '%$tcari%'";
  } else {
    $query = "SELECT * FROM layanan ORDER BY id_service DESC";
  }
  
  // Menjalankan query
  $result = $db->query($query);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Final Praktikum MBD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    .mt-20 {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h3 class="text-center">Data Service PC</h3>
    <div class="row">
      <div class="col-md-8 mx-auto">
        <div class="card text-center">
          <div class="card-header bg-gray text-black">
            Form Service
          </div>
          <div class="card-body">
            <?php if(isset($_GET['hal']) && $_GET['hal'] == "edit") : ?>
              <!-- Form untuk edit data -->
              <form method="post">
                <div class="mb-3">
                  <label for="kodeService" class="form-label">Kode Service</label>
                  <input type="text" id="kodeService" name="kodeService" value="<?= isset($vkode) ? $vkode : '' ?>" class="form-control" placeholder="Masukkan kode service" required>
                </div>
                <div class="mb-3">
                  <label for="deskripsiKerusakan" class="form-label">Deskripsi Kerusakan</label>
                  <input type="text" id="deskripsiKerusakan" name="deskripsiKerusakan" value="<?= isset($vdesc) ? $vdesc : '' ?>" class="form-control" placeholder="Masukkan deskripsi kerusakan" required>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label for="status" class="form-label">Status</label>
                      <select class="form-select" id="status" name="status" aria-label="Default select example" required>
                        <option disabled>Pilih Status</option>
                        <option value="Diterima" <?= (isset($vstatus) && $vstatus == 'Diterima') ? 'selected' : '' ?>>Diterima</option>
                        <option value="Proses" <?= (isset($vstatus) && $vstatus == 'Proses') ? 'selected' : '' ?>>Proses</option>
                        <option value="Selesai" <?= (isset($vstatus) && $vstatus == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label for="tanggalService" class="form-label">Tanggal Service</label>
                      <input type="date" id="tanggalService" name="tanggalService" value="<?= isset($vtgl) ? $vtgl : '' ?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label for="estimasiBiaya" class="form-label">Estimasi Biaya</label>
                      <input type="text" id="estimasiBiaya" name="estimasiBiaya" value="<?= isset($vest) ? $vest : '' ?>" class="form-control" placeholder="Estimasi biaya" required>
                    </div>
                  </div>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
              </form>
            <?php else : ?>
              <!-- Form untuk insert data baru -->
              <form method="post">
                <div class="mb-3">
                  <label for="kodeService" class="form-label">Kode Service</label>
                  <input type="text" id="kodeService" name="kodeService" class="form-control" placeholder="Masukkan kode service" required>
                </div>
                <div class="mb-3">
                  <label for="deskripsiKerusakan" class="form-label">Deskripsi Kerusakan</label>
                  <input type="text" id="deskripsiKerusakan" name="deskripsiKerusakan" class="form-control" placeholder="Masukkan deskripsi kerusakan" required>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label for="status" class="form-label">Status</label>
                      <select class="form-select" id="status" name="status" aria-label="Default select example" required>
                        <option disabled selected>Pilih Status</option>
                        <option value="Diterima">Diterima</option>
                        <option value="Proses">Proses</option>
                        <option value="Selesai">Selesai</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label for="tanggalService" class="form-label">Tanggal Service</label>
                      <input type="date" id="tanggalService" name="tanggalService" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label for="estimasiBiaya" class="form-label">Estimasi Biaya</label>
                      <input type="text" id="estimasiBiaya" name="estimasiBiaya" class="form-control" placeholder="Estimasi biaya" required>
                    </div>
                  </div>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
              </form>
            <?php endif; ?>
          </div>
          <div class="card-footer text-muted">
            <!-- Konten card footer di sini -->
          </div>
        </div>
        <!-- Card bawah -->
        <div class="card mt-4 text-center"> <!-- Menggunakan kelas mt-4 untuk menambahkan margin atas -->
          <div class="card-header">
            Data Service PC
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <div class="col-md-16">
                <form method="post">
                  <div class="input-group mb-3">
                    <input type="text" name="tcari" class="form-control" placeholder="Masukkan ID service">
                    <button class="btn btn-primary" name="bcari" type="submit">Cari</button>
                  </div>
                </form>
              </div>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">ID Service</th>
                    <th scope="col">Deskripsi Kerusakan</th>
                    <th scope="col">Tanggal Service</th>
                    <th scope="col">Estimasi Biaya</th>
                    <th scope="col">Status</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Hapus</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    if ($result->num_rows > 0) {
                      $no = 1;
                      while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['id_service']}</td>
                                <td>{$row['deskripsi_kerusakan']}</td>
                                <td>{$row['tanggal_service']}</td>
                                <td>{$row['estimasi_biaya']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                  <a href='index.php?hal=edit&id={$row['id_service']}' class='btn btn-warning'>Edit</a>
                                </td>
                                <td>
                                  <a href='index.php?hal=hapus&id={$row['id_service']}' class='btn btn-danger'>Hapus</a>
                                </td>
                              </tr>";
                        $no++;
                      }
                    } else {
                      echo "<tr><td colspan='8'>Tidak ada data ditemukan</td></tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer text-muted">
            <!-- Konten card footer di sini -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

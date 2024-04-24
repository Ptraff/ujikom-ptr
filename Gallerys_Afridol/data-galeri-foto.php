<?php
session_start();

if (!isset($_SESSION['a_global'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if (isset($_GET['idp'])) {
    $id = $_GET['idp'];
    // Peringatan SQL Injection: Gunakan prepared statement
    $query_delete = "DELETE FROM tb_image WHERE image_id = ?";
    $stmt = mysqli_prepare($conn, $query_delete);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: admin.php");
    exit();
}

$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
$a = mysqli_fetch_object($kontak);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - Galeri Foto</title>
<link rel="stylesheet" type="text/css" href="css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="admin.php">Halaman Admin - Gallery Foto</a></h1>
            <nav>
                <ul>
                    <li><a href="tambah-image-admin.php">Tambah Data</a></li> <!-- Perbaiki tautan -->
                    <li><a href="data-kategori-foto.php">Data Kategori</a></li> <!-- Perbaiki tautan -->
                    <li><a href="keluar.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    


    <div class="section">
        <div class="container">
            <h3>Data Galeri Foto</h3>
            <div class="box">
                <p><a href="tambah-image-admin.php" class="btn tambah">Tambah Data</a></p>
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Kategori</th>
                            <th>Nama User</th>
                            <th>Nama Foto</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                            <th>Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (isset($_SESSION['a_global']) && $_SESSION['a_global'] !== null) {
                            $foto = mysqli_query($conn, "SELECT tb_image.*, tb_category.category_name 
                                                         FROM tb_image 
                                                         INNER JOIN tb_category ON tb_image.category_id = tb_category.category_id");
                            if (mysqli_num_rows($foto) > 0) {
                                while ($row = mysqli_fetch_array($foto)) {
                        ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $row['category_name'] ?></td>
                                        <td><?php echo $row['admin_name'] ?></td>
                                        <td><?php echo $row['image_name'] ?></td>
                                        <td><?php echo $row['image_description'] ?></td>
                                        <td><a href="foto/<?php echo $row['image'] ?>" target="_blank"><img src="foto/<?php echo $row['image'] ?>" width="100px"></a></td>
                                        <td><?php echo ($row['image_status'] == 0) ? 'Tidak Aktif' : 'Aktif'; ?></td>
                                        <td class="aksi">
                                            <div class="aksi-buttons">
                                                <a href="edit-image-admin.php?id=<?php echo $row['image_id'] ?>" class="btn edit">Edit</a>
                                                <a href="admin.php?idp=<?php echo $row['image_id'] ?>" class="btn hapus" onclick="return confirm('Yakin Ingin Hapus ?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                        <?php
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="8">Tidak ada data</td>
                                </tr>
                        <?php }}else{ ?>
                                <tr>
                                    <td colspan="8">Sesi tidak diinisialisasi atau tidak ada</td>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Galeri Foto.</small>
        </div>
    </footer>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION['a_global'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if (isset($_GET['idp'])) {
    $id = $_GET['idp'];
    $query_delete = "DELETE FROM tb_image WHERE image_id = '$id'";
    $result_delete = mysqli_query($conn, $query_delete);
    if ($result_delete) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
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
                    <li><a href="data-galeri-foto.php">Data galeri foto</a></li>
                    <li><a href="admin.php">Data Kategori</a></li>
                    <li><a href="keluar.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="section">
        <div class="container">
            <h3>Data Kategori</h3>
            <div class="box">
                <p><a href="tambah-kategori.php" class="btn tambah">Tambah Data</a></p>
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th> <!-- Hapus kolom gambar dari judul kolom -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $kategori = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
                        if(mysqli_num_rows($kategori) > 0){
                            $no = 1;
                            while($k = mysqli_fetch_array($kategori)){
                        ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $k['category_name'] ?></td>
                                <td>
                                    <a href="edit-kategori.php?id=<?php echo $k['category_id'] ?>" class="btn edit">Edit</a>
                                    <a href="proses-hapus-kategori.php?id=<?php echo $k['category_id'] ?>" class="btn hapus" onclick="return confirm('Yakin Ingin Hapus ?')">Hapus</a>
                                </td>
                            </tr>
                        <?php }}else{ ?>
                            <tr>
                                <td colspan="3">Tidak ada data</td> <!-- Sesuaikan dengan jumlah kolom yang tersisa -->
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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

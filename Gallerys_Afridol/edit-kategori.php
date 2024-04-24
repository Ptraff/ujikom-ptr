<?php
session_start();
include 'db.php';
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    echo '<script>window.location="login.php"</script>';
    exit; // Jangan lupa keluar dari skrip setelah melakukan pengalihan
}

// Periksa apakah parameter 'id' tersedia di URL
if(isset($_GET['id'])) {
    $kategori = mysqli_query($conn, "SELECT * FROM tb_category WHERE category_id = '".$_GET['id']."'");

    // Periksa apakah kategori ditemukan
    if(mysqli_num_rows($kategori) == 0){
        echo '<script>window.location="index.php"</script>';
        exit; // Jangan lupa keluar dari skrip setelah melakukan pengalihan
    } else {
        $k = mysqli_fetch_object($kategori);
    }
} else {
    // Jika parameter 'id' tidak tersedia, alihkan pengguna atau tampilkan pesan kesalahan
    echo '<script>alert("Parameter ID tidak ditemukan")</script>';
    echo '<script>window.location="index.php"</script>';
    exit; // Jangan lupa keluar dari skrip
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kategori</title>
    <link rel="stylesheet" type="text/css" href="css/edit-kategori.css">
</head>
<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="index.php">WEB GALERI FOTO</a></h1>
            <nav>
                <ul>
                    <li><a href="index.php">Data Kategori</a></li>
                    <li><a href="keluar.php">Keluar</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- content -->
    <div class="section">
        <div class="container">
            <h3>Edit Data Kategori</h3>
            <div class="box">
                <form action="" method="POST">
                    <input type="text" name="nama_kategori" class="input-control" placeholder="Nama Kategori" value="<?php echo $k->category_name ?>" required>
                    <!-- Hilangkan komentar di bawah ini -->
                    <!-- <textarea class="input-control" name="deskripsi" placeholder="Deskripsi"><?php echo $k->category_description ?></textarea><br /> -->
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                    if(isset($_POST['submit'])){
                        $nama_kategori = $_POST['nama_kategori'];
                        // $deskripsi = $_POST['deskripsi'];
                        
                        $update = mysqli_query($conn, "UPDATE tb_category SET 
                                                    category_name = '".$nama_kategori."' 
                                                    WHERE category_id = '".$k->category_id."' ");
                        if($update){
                            echo '<script>alert("Ubah data berhasil")</script>';
                            echo '<script>window.location="admin.php"</script>'; // Ubah ke admin.php
                        }else{
                            echo 'gagal'.mysqli_error($conn);
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    
    <!-- footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Web Galeri Foto.</small>
        </div>
    </footer>
</body>
</html>

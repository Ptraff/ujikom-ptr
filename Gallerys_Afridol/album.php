<?php
require_once 'db.php';

// Mulai sesi
session_start();

// Mengambil informasi kontak admin
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
$a = mysqli_fetch_object($kontak);

// Mengambil nilai pencarian jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mencari foto sesuai dengan input pencarian
$query = "SELECT * FROM tb_image WHERE image_status = 1";
if (!empty($search)) {
    $query .= " AND (image_name LIKE '%$search%' OR description LIKE '%$search%')";
}
$query .= " ORDER BY image_id";
$foto = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header>
        <div class="container">
            <h1><a href="index.php">WEB GALERI FOTO</a></h1>
            <!-- nav.php -->
            <nav>
                <ul>
                    <?php
                    if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true) {
                        // Jika pengguna sudah login
                        echo '<li><a href="index.php">Gallery</a></li>';
                        echo '<li><a href="album.php">Album</a></li>'; // Tambahkan link ke album.php
                        echo '<li><a href="profil.php">Profil</a></li>';
                        echo '<li><a href="data-image.php">Data Foto</a></li>';
                        echo '<li><a href="keluar.php">Keluar</a></li>';
                        
                    } else {
                        // Jika pengguna belum login
                        echo '<li><a href="index.php">Gallery</a></li>';
                        echo '<li><a href="album.php">Album</a></li>'; // Tambahkan link ke album.php
                        echo '<li><a href="registrasi.php">Registrasi</a></li>';
                        echo '<li><a href="login.php">Login</a></li>';
                        
                    }
                    ?>
                </ul>
            </nav>

        </div>
    </header>


    <div class="search">
        <div class="container">
            <form action="galeri.php" method="GET"> <!-- Menggunakan method GET untuk mengirim data pencarian -->
                <input type="text" name="search" placeholder="Cari Foto" value="<?php echo $search; ?>" />
                <button type="submit" name="cari"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <h3>Album</h3>
            <div class="box">
                <?php
                $kategori = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
                if(mysqli_num_rows($kategori) > 0){
                    while($k = mysqli_fetch_array($kategori)){
                ?>
                        <a href="galeri.php?kat=<?php echo $k['category_id'] ?>">
                            <div class="category">
                                <img src="img/<?php echo strlen($k['image']) > 0 ? $k['image'] : $k['category_name'].'.png' ?>" alt="<?php echo $k['category_name'] ?>" />
                                <p><?php echo $k['category_name'] ?></p>
                            </div>
                        </a>
                <?php 
                    }
                } else { 
                ?>
                    <p>Album tidak ada</p>
                <?php 
                } 
                ?>
            </div>
        </div>
    </div>

 
        </div>
    </div>

    <footer>
        <div class="container">
            <small>&copy; 2024 - Web Galeri Foto.</small>
        </div>
    </footer>
</body>
</html>

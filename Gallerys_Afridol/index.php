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
        <marquee width="20%" direction="right" height="100px">          
            <h1><a href="index.php">Gallery of Nothing</a></h1>
            </marquee>
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

   
            </div>
        </div>
    </div>

    <div class="container">
        <h3>Foto Terbaru</h3>
        <div class="box">
            <?php
            if(mysqli_num_rows($foto) > 0){
                while($p = mysqli_fetch_array($foto)){
            ?>
                        <a href="detail-image.php?id=<?php echo $p['image_id'] ?>" class="image-link">
                            <div class="col-4">
                                <div class="image-container">
                                    <img src="foto/<?php echo $p['image'] ?>" alt="<?php echo $p['image_name'] ?>" />
                                    <div class="overlay">
                                        <div class="text"><?php echo substr($p['image_name'], 0, 30) ?></div>
                                    </div>
                                </div>
                                
                                <!-- Tampilkan deskripsi atau nama gambar sesuai pencarian -->
                                <?php if (!empty($search)) { ?>
                                    <p class="description"><?php echo $p['description']; ?></p>
                                <?php } ?>
                            </div>
                        </a>
            <?php 
                }
            } else { 
            ?>
                <p>Foto tidak ada</p>
            <?php 
            } 
            ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <small>&copy; 2024 - Web Galeri Foto.</small>
        </div>
    </footer>
</body>
</html>

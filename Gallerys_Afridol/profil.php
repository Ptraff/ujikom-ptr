<?php
    session_start();
    include 'db.php';
    // Cek apakah pengguna sudah login
    if($_SESSION['status_login'] != true){
        echo '<script>window.location="login.php"</script>';
    }
    // Ambil data user dari session
    $nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    $telpon = isset($_SESSION['telpon']) ? $_SESSION['telpon'] : '';
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $alamat = isset($_SESSION['alamat']) ? $_SESSION['alamat'] : '';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WEB Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/profil.css">
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="index.php">WEB GALERI FOTO</a></h1>
            <nav>
                <ul>
                    <li><a href="index.php">Gallery</a></li>
                    <li><a href="album.php">Album</a></li>
                    <li><a href="data-image.php">Data Foto</a></li>
                    <li><a href="Keluar.php">Keluar</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- content -->
    <div class="section">
        <div class="container">
            <div class="profile-info">
                <h3>Profil</h3>
                <div class="box">
                    <form action="" method="POST">
                        <input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" value="<?php echo $nama ?>" required>
                        <input type="text" name="user" placeholder="Username" class="input-control" value="<?php echo $username ?>" required>
                        <input type="submit" name="submit_profil" value="Ubah Profil" class="btn">
                    </form>
                    <?php
                        if(isset($_POST['submit_profil'])){
                            $nama_baru = $_POST['nama'];
                            $username_baru = $_POST['user'];
                            
                            $update_profil = mysqli_query($conn, "UPDATE tb_admin SET 
                                            admin_name = '$nama_baru',
                                            username = '$username_baru'
                                            WHERE admin_id = '".$_SESSION['id']."'");
                            if($update_profil){
                                // Update session data
                                $_SESSION['nama'] = $nama_baru;
                                $_SESSION['username'] = $username_baru;
                                
                                echo '<script>alert("Ubah data berhasil")</script>';
                                echo '<script>window.location="profil.php"</script>';
                            }else{
                                echo 'gagal '.mysqli_error($conn);
                            }
                        }  
                    ?>
                </div>
            </div>
            
            <div class="change-password">
                <h3>Ubah password</h3>
                <div class="box">
                    <form action="" method="POST">
                        <input type="password" name="pass1" placeholder="Password Baru" class="input-control" required>
                        <input type="password" name="pass2" placeholder="Konfirmasi Password Baru" class="input-control" required>
                        <input type="submit" name="ubah_password" value="Ubah Password" class="btn">
                    </form>
                    <?php
                        if(isset($_POST['ubah_password'])){
                            $pass1   = $_POST['pass1'];
                            $pass2   = $_POST['pass2'];
                            
                            if($pass2 != $pass1){
                                echo '<script>alert("Konfirmasi Password Baru tidak sesuai")</script>';
                            }else{
                                $u_pass = mysqli_query($conn, "UPDATE tb_admin SET 
                                            password = '$pass1'
                                            WHERE admin_id = '".$_SESSION['id']."'");
                                if($u_pass){
                                    echo '<script>alert("Ubah password berhasil")</script>';
                                    echo '<script>window.location="profil.php"</script>';
                                }else{
                                    echo 'gagal '.mysqli_error($conn);
                                }
                            }
                        }  
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer>
        <div class="container">
            <small>&copy; 2024 - Web Galeri Foto.</small>
        </div>
    </footer>
</body>
</html>

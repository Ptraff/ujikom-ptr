<?php
    include 'db.php';
    session_start(); // Mulai session

    // Cek jika form registrasi telah disubmit
    if(isset($_POST['submit'])){
        $nama = ucwords($_POST['nama']);
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $alamat = ucwords($_POST['almt']);
        
        // Masukkan data user ke dalam database
        $insert = mysqli_query($conn, "INSERT INTO tb_admin (admin_name, username, password, admin_address, role) VALUES (
            '$nama',
            '$username',
            '$password',
            '$alamat',
            'user'
        )");

        // Jika registrasi berhasil
        if($insert){
            echo '<script>alert("Registrasi berhasil")</script>';
            // Redirect ke halaman login
            echo '<script>window.location="login.php"</script>';
            exit; // Hentikan eksekusi lebih lanjut
        } else {
            echo 'gagal '.mysqli_error($conn);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi Akun</title>
<link rel="stylesheet" type="text/css" href="css/registrasi.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-LqIh+1LhpFZdF0h2xaLJwu2CtvLa8K37ifpyW5I15Lz+HvDzd2eXtdZvmfLSsB4LpULM5vysSMf3Z4RNo4IWXQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<header class="header" style="background: linear-gradient(90deg, rgba(0, 194, 177, 1) 0%, rgba(0, 147, 162, 1) 50%, rgba(0, 94, 145, 1) 100%);">
  <div class="container header-content">
    <h1><a href="index.php" style="color: #fff;">WEB GALERI FOTO</a></h1>
    <div class="nav-links">
        <ul>
           <li><a href="index.php" style="color: #fff;">Galeri</a></li>
           <!-- <li><a href="registrasi.php" style="color: #fff;">Registrasi</a></li> -->
           <li><a href="album.php" style="color: #fff;">Album</a></li>
           <li><a href="login.php" style="color: #fff;">Login</a></li>
        </ul>
    </div>
  </div>
</header>

<div class="section">
  <div class="container">
    <div class="form-group">
      <h3>Registrasi Akun</h3>
      <form action="" method="POST">
        <div class="group-3101">
          <i class="fas fa-user icon"></i>
          <input type="text" name="nama" placeholder="Nama User" class="input-control" required>
        </div>
        <div class="group-3101">
          <i class="fas fa-user icon"></i>
          <input type="text" name="user" placeholder="Username" class="input-control" required>
        </div>
        <div class="group-3101">
          <i class="fas fa-lock icon"></i>
          <input type="password" name="pass" placeholder="Password" class="input-control" required>
        </div>
        <input type="submit" name="submit" value="Submit" class="btn" style="background: linear-gradient(90deg, rgba(0, 194, 177, 1) 0%, rgba(0, 147, 162, 1) 50%, rgba(0, 94, 145, 1) 100%);">
      </form>
    </div>
  </div>
</div>
<footer class="footer">
  <div class="container">
    <small>&copy; 2024 - User Afridol.</small>
  </div>
</footer>
</body>
</html>

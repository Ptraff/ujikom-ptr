<?php
include 'db.php';
session_start(); // Mulai sesi jika belum dimulai
?>

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

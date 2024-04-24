<?php
// Aktifkan error reporting untuk menampilkan pesan kesalahan
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mulai sesi sebelum output apapun dikirimkan ke browser
session_start();

// Sertakan file koneksi ke database
include 'db.php';

// Inisialisasi variabel untuk mencegah kemunculan pesan kesalahan jika parameter GET tidak tersedia
$p = null;

// Periksa apakah parameter GET 'id' telah diset sebelum mengambil informasi produk
if(isset($_GET['id'])) {
    // Ambil informasi produk berdasarkan ID yang diterima melalui parameter GET
    $produk = mysqli_query($conn, "SELECT * FROM tb_image WHERE image_id = '".$_GET['id']."' ");
    $p = mysqli_fetch_object($produk);
} else {
    // Tampilkan pesan kesalahan atau arahkan pengguna ke halaman lain jika parameter 'id' tidak tersedia
    echo "Parameter 'id' tidak tersedia";
    exit();
}

// Tangani like jika tombol like atau dislike ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['like']) || isset($_POST['dislike']))) {
    $image_id = $_POST['image_id'];
    // Cek apakah pengguna telah melakukan like sebelumnya menggunakan session
    $user_has_liked = isset($_SESSION['liked_image_'.$image_id]);
    if (isset($_POST['like']) && !$user_has_liked) {
        // Perbarui jumlah like di database
        mysqli_query($conn, "UPDATE tb_image SET likes = likes + 1 WHERE image_id = $image_id");

        // Tandai pengguna dengan session untuk menunjukkan bahwa pengguna telah melike foto ini
        $_SESSION['liked_image_'.$image_id] = true;
    } elseif (isset($_POST['dislike']) && $user_has_liked) {
        // Perbarui jumlah like di database
        mysqli_query($conn, "UPDATE tb_image SET likes = likes - 1 WHERE image_id = $image_id");

        // Hapus tanda pengguna dari session
        unset($_SESSION['liked_image_'.$image_id]);
    }

    // Ambil jumlah total like setelah update
    $result = mysqli_query($conn, "SELECT likes FROM tb_image WHERE image_id = $image_id");
    $total_likes = mysqli_fetch_assoc($result)['likes'];

    // Redirect kembali ke halaman produk dengan total likes
    header("Location: login.php?id=$image_id&total_likes=$total_likes");
    exit();
}

// Tangani penambahan komentar dan penghapusan komentar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_comment'])) {
        $image_id = $_POST['image_id'];
        $comment_text = $_POST['comment'];

        // Masukkan komentar ke dalam database
        mysqli_query($conn, "INSERT INTO tb_comments (image_id, comment_text) VALUES ($image_id, '$comment_text')");

        // Redirect kembali ke halaman produk
        header("Location: login.php?id=$image_id");
        exit();
    } elseif (isset($_POST['delete_comment'])) {
        $comment_id = $_POST['comment_id'];

        // Hapus komentar dari database
        mysqli_query($conn, "DELETE FROM tb_comments WHERE comment_id = $comment_id");

        // Redirect kembali ke halaman produk
        header("Location: detai;-image.php?id=$p->image_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Foto</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="index.php">WEB GALERI FOTO</a></h1>
            <ul>
                <li><a href="galeri.php">Galeri</a></li>
                <li><a href="registrasi.php">Registrasi</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </header>

    <!-- search -->
    <!-- <div class="search">
        <div class="container">
            <form action="galeri.php">
                <input type="text" name="search" placeholder="Cari Foto" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
                <input type="hidden" name="kat" value="<?php echo isset($_GET['kat']) ? $_GET['kat'] : ''; ?>" />
                <input type="submit" name="cari" value="Cari Foto" />
            </form>
        </div>
    </div> -->

    <!-- product detail -->
    <div class="section">
        <div class="container">
            <h3>Detail Foto</h3>
            <div class="box">
                <div class="col-2">
                   <!-- Pastikan objek produk tidak null sebelum mengakses propertinya -->
                   <?php if ($p): ?>
                   <img src="foto/<?php echo $p->image ?>" width="50%" />
                   <?php endif; ?>
                </div>
                <div class="col-2">
                   <!-- Pastikan objek produk tidak null sebelum mengakses propertinya -->
                   <?php if ($p): ?>
                   <h3><?php echo $p->image_name ?><br />Kategori : <?php echo $p->category_name  ?></h3>
                   <h4>Nama User : <?php echo $p->admin_name ?><br />
                   Upload Pada Tanggal : <?php echo $p->date_created  ?></h4>
                   <p>Deskripsi :<br />
                        <?php echo $p->image_description ?>
                   </p>
                   
                   <!-- Form Like atau Dislike -->
                   <form method="post" action="login.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                       <input type="hidden" name="image_id" value="<?php echo $p->image_id ?>">
                       <?php 
                            // Cek apakah pengguna telah melike foto ini sebelumnya menggunakan session
                            $user_has_liked = isset($_SESSION['liked_image_'.$p->image_id]);
                            if (!$user_has_liked) {
                                echo '<input type="submit" name="like" value="Like">';
                            } else {
                                echo '<input type="submit" name="dislike" value="Dislike">';
                            }
                       ?>
                   </form>
                   <?php 
                        // Ambil jumlah total like dari URL jika tersedia
                        $total_likes = isset($_GET['total_likes']) ? $_GET['total_likes'] : ($p->likes ?? 0);
                        echo "Total Likes: $total_likes";
                   ?>

                   <!-- Form Komentar -->
                   <form method="post" action="login.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                       <input type="hidden" name="image_id" value="<?php echo $p->image_id ?>">
                       <textarea name="comment" placeholder="Tambahkan komentar"></textarea>
                       <input type="submit" name="add_comment" value="Tambahkan Komentar">
                   </form>

                   <!-- Tampilkan Komentar -->
                   <?php
                        // Ambil komentar untuk foto ini dari database
                        $comments_result = mysqli_query($conn, "SELECT * FROM tb_comments WHERE image_id = $p->image_id");
                        if (mysqli_num_rows($comments_result) > 0) {
                            echo "<h3>Komentar:</h3>";
                            echo "<ul>";
                            while ($comment = mysqli_fetch_assoc($comments_result)) {
                                echo "<li>".$comment['comment_text'];
                                echo '<form method="post" action="login.php?id='.$p->image_id.'">';
                                echo '<input type="hidden" name="comment_id" value="'.$comment['comment_id'].'">';
                                echo '<input type="submit" name="delete_comment" value="Hapus">';
                                echo '</form></li>';
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>Belum ada komentar untuk foto ini.</p>";
                        }
                   ?>
                   <?php else: ?>
                   <p>Produk tidak ditemukan.</p>
                   <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer>
        <div class="container">
            <small>&copy; 2024 - Bintang Satrio Buwono.</small>
        </div>
    </footer>
</body>
</html>

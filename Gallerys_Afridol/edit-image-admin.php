<?php
session_start();
include 'db.php';

// Pengecekan apakah pengguna adalah admin
if ($_SESSION['status_login'] != true || $_SESSION['a_global']['role'] != 'admin') {
    echo '<script>window.location="login.php"</script>';
    exit; // Hentikan eksekusi script
}

$produk = mysqli_query($conn, "SELECT * FROM  tb_image WHERE image_id = '" . $_GET['id'] . "'");
if (mysqli_num_rows($produk) == 0) {
    echo '<script>window.location="data-image.php"</script>';
    exit; // Hentikan eksekusi script
}
$p = mysqli_fetch_object($produk);

// Ambil daftar kategori dari tabel tb_category
$categories = mysqli_query($conn, "SELECT category_id, category_name FROM tb_category");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/edit-image.css">
    <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
</head>

<body>
    <!-- header -->
    <header>
        <!-- Bagian header -->
    </header>

    <!-- content -->
    <div class="section">
        <div class="container">
            <h3>Edit Data Foto</h3>
            <div class="box">
                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- Bagian form -->

                    <!-- Input kategori -->
                    <select name="kategori" class="input-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php while ($category = mysqli_fetch_assoc($categories)) : ?>
                            <option value="<?php echo $category['category_id']; ?>" <?php echo ($p->category_id == $category['category_id']) ? 'selected' : ''; ?>><?php echo $category['category_name']; ?></option>
                        <?php endwhile; ?>
                    </select>

                    <!-- Informasi pengguna dan data foto -->
                    <input type="text" name="namauser" class="input-control" placeholder="Nama User" value="<?php echo $p->admin_name ?>" readonly="readonly">
                    <input type="text" name="nama" class="input-control" placeholder="Nama Foto" value="<?php echo $p->image_name ?>" required>
                    <img src="foto/<?php echo $p->image ?>" width="100px" />
                    <input type="hidden" name="foto" value="<?php echo $p->image ?>" />

                    <!-- Upload gambar baru -->
                    <input type="file" name="gambar" class="input-control">

                    <!-- Deskripsi dan status -->
                    <textarea class="input-control" name="deskripsi" placeholder="Deskripsi"><?php echo $p->image_description ?></textarea><br />
                    <select class="input-control" name="status">
                        <option value="">--Pilih--</option>
                        <option value="1" <?php echo ($p->image_status == 1) ? 'selected' : ''; ?>>Aktif</option>
                        <option value="0" <?php echo ($p->image_status == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                    </select>

                    <!-- Tombol submit -->
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                if (isset($_POST['submit'])) {

                    // Data inputan dari form
                    $nama      = $_POST['nama'];
                    $deskripsi = $_POST['deskripsi'];
                    $status    = $_POST['status'];
                    $foto      = $_POST['foto'];

                    // Data gambar yang baru 
                    $filename = $_FILES['gambar']['name'];
                    $tmp_name = $_FILES['gambar']['tmp_name'];

                    // Jika admin ganti gambar
                    if ($filename != '') {

                        $type1 = explode('.', $filename);
                        $type2 = $type1[1];

                        $newname = 'foto' . time() . '.' . $type2;

                        // Menampung data format file yang diizinkan
                        $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

                        // Validasi format file
                        if (!in_array($type2, $tipe_diizinkan)) {
                            // Jika format file tidak ada di dalam tipe diizinkan
                            echo '<script>alert("Format file tidak diizinkan")</script>';
                        } else {
                            unlink('./foto/' . $foto);
                            move_uploaded_file($tmp_name, './foto/' . $newname);
                            $namagambar = $newname;
                        }
                    } else {
                        // Jika admin tidak ganti gambar
                        $namagambar = $foto;
                    }

                    // Query update data produk
                    $update = mysqli_query($conn, "UPDATE tb_image SET
                                                   image_name          = '" . $nama . "',
                                                   image_description   = '" . $deskripsi . "',
                                                   image               = '" . $namagambar . "',
                                                   image_status        = '" . $status . "',
                                                   category_id         = '" . $_POST['kategori'] . "'
                                                   WHERE image_id      = '" . $p->image_id . "' ");
                    if ($update) {
                        echo '<script>alert("Ubah data berhasil")</script>';
                        echo '<script>window.location="admin.php"</script>'; // Kembali ke halaman admin.php setelah berhasil mengubah data
                    } else {
                        echo 'gagal' . mysqli_error($conn);
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer>
        <!-- Bagian footer -->
    </footer>
    <script>
        CKEDITOR.replace('deskripsi');
    </script>
</body>

</html>

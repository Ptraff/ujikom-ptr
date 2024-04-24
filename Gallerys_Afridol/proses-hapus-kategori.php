<?php
session_start();
require_once 'db.php';

// Pastikan parameter id kategori tersedia dan merupakan bilangan bulat positif
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    $kategori_id = $_GET['id'];

    // Hapus terlebih dahulu semua foto terkait dengan kategori yang akan dihapus
    $hapus_foto_kategori = mysqli_query($conn, "DELETE FROM tb_image WHERE category_id = $kategori_id");

    if ($hapus_foto_kategori) {
        // Kemudian hapus kategori dari database setelah semua foto terkait dihapus
        $hapus_kategori = mysqli_query($conn, "DELETE FROM tb_category WHERE category_id = $kategori_id");

        if ($hapus_kategori) {
            // Kategori dan semua foto terkait berhasil dihapus, redirect ke halaman admin.php
            header("Location: admin.php");
            exit();
        } else {
            // Jika gagal menghapus kategori, tampilkan pesan kesalahan
            echo "Gagal menghapus kategori: " . mysqli_error($conn);
        }
    } else {
        // Jika gagal menghapus foto terkait, tampilkan pesan kesalahan
        echo "Gagal menghapus foto terkait dengan kategori: " . mysqli_error($conn);
    }
} else {
    // Jika parameter id tidak tersedia atau tidak valid, tampilkan pesan kesalahan
    echo "ID kategori tidak valid.";
}
?>

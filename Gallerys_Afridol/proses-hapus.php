<?php

include 'db.php';

// Pastikan sesi dimulai di setiap file yang memerlukan pengguna login
session_start();

if(isset($_GET['idp'])) {
    $foto = mysqli_query($conn, "SELECT image FROM tb_image WHERE image_id = '".$_GET['idp']."' ");
    $p = mysqli_fetch_object($foto);
    
    unlink('./foto/'.$p->image);
    
    $delete = mysqli_query($conn, "DELETE FROM tb_image WHERE image_id = '".$_GET['idp']."' ");
    
    // Check if the user is admin or not
    if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
        header("Location: admin.php");
        exit();
    } else {
        header("Location: data-image.php");
        exit();
    }
}

?>

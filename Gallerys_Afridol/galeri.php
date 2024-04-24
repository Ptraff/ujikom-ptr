<?php
    error_reporting(0);
    include 'db.php';
	$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
	$a = mysqli_fetch_object($kontak);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WEB Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/gallery.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
<?php include 'header.php'; ?> <!-- Sisipkan kode navbar di sini -->
    
    <!-- search -->
    <div class="search">
        <div class="container">
            <form action="galeri.php" method="GET">
                <input type="text" name="search" placeholder="Cari Foto" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>" />
                <input type="hidden" name="kat" value="<?php echo isset($_GET['kat']) ? $_GET['kat'] : '' ?>" />
                <button type="submit" name="cari"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>

    <!-- new product -->
    <div class="section">
        <div class="container">
            <h3>Galeri Foto</h3>
            <div class="box">
                <?php
                    $where = "";
                    if(isset($_GET['search']) && $_GET['search'] != ''){
                        $search = mysqli_real_escape_string($conn, $_GET['search']);
                        $where = " WHERE image_name LIKE '%$search%'";
                    }
                    if(isset($_GET['kat']) && $_GET['kat'] != ''){
                        $category_id = mysqli_real_escape_string($conn, $_GET['kat']);
                        $where .= ($where != "" ? " AND" : " WHERE") . " category_id = '$category_id'";
                    }
                    $foto = mysqli_query($conn, "SELECT * FROM tb_image $where ORDER BY image_id DESC");
                    if(mysqli_num_rows($foto) > 0){
                        while($p = mysqli_fetch_array($foto)){
                ?>
                <a href="detail-image.php?id=<?php echo $p['image_id'] ?>">
                    <div class="col-6">
                        <img src="foto/<?php echo $p['image'] ?>" />
                        <p class="nama"><?php echo substr($p['image_name'], 0, 30) ?></p>
                        <p class="harga"><?php echo $p['admin_name'] ?></p>
                        <p class="admin">Nama User : <?php echo $p['admin_name'] ?></p>
                        <p class="nama"><?php echo $p['date_created']  ?></p>
                    </div>
                </a>
                <?php 
                    }
                } else { 
                    echo "<p>Foto tidak ada</p>";
                } 
                ?>
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

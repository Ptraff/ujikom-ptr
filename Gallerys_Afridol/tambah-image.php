<?php
    session_start();
    include 'db.php';
    if ($_SESSION['status_login'] != true) {
        echo '<script>window.location="login.php"</script>';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/tambah-image.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-LqIh+1LhpFZdF0h2xaLJwu2CtvLa8K37ifpyW5I15Lz+HvDzd2eXtdZvmfLSsB4LpULM5vysSMf3Z4RNo4IWXQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>

<body>
    <!-- header -->
    <header class="header"
        style="background: linear-gradient(90deg, rgba(0, 194, 177, 1) 0%, rgba(0, 147, 162, 1) 50%, rgba(0, 94, 145, 1) 100%);">
        <div class="container header-content">
            <h1><a href="dashboard.php" style="color: #fff;">WEB GALERI FOTO</a></h1>
            <div class="nav-links">
                <ul>
                    <li><a href="dashboard.php" style="color: #fff;">Dashboard</a></li>
                    <li><a href="profil.php" style="color: #fff;">Profil</a></li>
                    <li><a href="data-image.php" style="color: #fff;">Data Foto</a></li>
                    <li><a href="Keluar.php" style="color: #fff;">Keluar</a></li>
                </ul>
            </div>
        </div>
    </header>

   <!-- content -->
<div class="section">
    <div class="container">
        <div class="form-group">
            <h3>Tambah Data Foto</h3>
            <div class="box">

                <form action="" method="POST" enctype="multipart/form-data">

                    <?php   
                    $result = mysqli_query($conn,"select * from tb_category");   
                    $jsArray = "var prdName = new Array();\n";   
                    echo '<select class="input-control" name="kategori" onchange="document.getElementById(\'prd_name\').value = prdName[this.value]" required>  <option>-Pilih Kategori Foto-</option>';while ($row = mysqli_fetch_array($result)) {  echo ' <option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';  
                    $jsArray .= "prdName['" . $row['category_id'] . "'] = '" . addslashes($row['category_name']) . "';\n";}echo '</select>';?>
                    </select>
                    <input type="hidden" name="nama_kategori" id="prd_name">
                    <input type="hidden" name="adminid"
                        value="<?php echo $_SESSION['a_global']['admin_id'] ?>">
                    <input type="text" name="namaadmin" class="input-control"
                        value="<?php echo $_SESSION['a_global']['username'] ?>" readonly="readonly">
                    <input type="text" name="nama" class="input-control" placeholder="Nama Foto" required>
                    <textarea class="input-control" name="deskripsi"
                        placeholder="Deskripsi"></textarea><br />
                    <input type="file" name="gambar" class="input-control" required>
                    <select class="input-control" name="status">
                        <option value="0">Tidak Aktif</option>
                    </select>
                    <input type="submit" name="submit" value="Submit" class="btn"
                        style="background: linear-gradient(90deg, rgba(0, 194, 177, 1) 0%, rgba(0, 147, 162, 1) 50%, rgba(0, 94, 145, 1) 100%);">
                </form>
                <?php
                    if(isset($_POST['submit'])){
                        // memastikan kategori yang dipilih ada dalam tabel tb_category
                        $kategori  = $_POST['kategori'];
                        $check_category = mysqli_query($conn, "SELECT * FROM tb_category WHERE category_id = '$kategori'");
                        if(mysqli_num_rows($check_category) > 0) {
                            // kategori valid, melanjutkan proses penyisipan
                            // menampung inputan dari form
                            $nama_ka   = $_POST['nama_kategori'];
                            $ida       = $_POST['adminid'];
                            $user      = $_POST['namaadmin'];
                            $nama      = $_POST['nama'];
                            $deskripsi = $_POST['deskripsi'];
                            $status    = $_POST['status'];
                            
                            // menampung data file yang diupload
                            $filename = $_FILES['gambar']['name'];
                            $tmp_name = $_FILES['gambar']['tmp_name'];  
                            
                            $type1 = explode('.', $filename);
                            $type2 = $type1[1];
                            $newname = 'foto'.time().'.'.$type2; 
                            
                            // menampung data format file yang diizinkan
                            $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');
                            
                            // validasi format file
                            if(!in_array($type2, $tipe_diizinkan)){
                                // jika format file tidak ada di dalam tipe diizinkan
                                echo '<script>alert("Format file tidak diizinkan")</script>';
                                
                            }else{
                                // jika format file sesuai dengan yang ada di dalam array tipe diizinkan
                                // proses upload file sekaligus insert ke database
                                move_uploaded_file($tmp_name, './foto/'.$newname);
                                
                                $insert = mysqli_query($conn, "INSERT INTO tb_image (category_id, category_name, admin_id, admin_name, image_name, image_description, image, image_status, date_created) VALUES (
                                    '".$kategori."',
                                    '".$nama_ka."',
                                    '".$ida."',
                                    '".$user."',
                                    '".$nama."',
                                    '".$deskripsi."',
                                    '".$newname."',
                                    '".$status."',
                                    null
                                )");
                                if($insert){
                                    echo '<script>alert("Tambah Foto berhasil");';
                                    // Cek apakah pengguna adalah admin atau bukan
                                    if ($_SESSION['a_global']['role'] == 'admin') {
                                        echo 'window.location="admin.php";'; // Jika admin, arahkan ke admin.php
                                    } else {
                                        echo 'window.location="data-image.php";'; // Jika bukan admin, tetap di data-image.php
                                    }
                                    echo '</script>';
                                } else {
                                    echo 'gagal'.mysqli_error($conn);
                                }
                            }
                        } else {
                            // kategori tidak valid
                            echo '<script>alert("Kategori tidak valid")</script>';
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>

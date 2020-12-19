<?php
require('mysql_conect.php');
if($_SERVER["REQUEST_METHOD"]=="GET"){ //lấy id để xóa
    if(isset($_GET['delete_id'])){
        $id = $_GET['delete_id'];
        $query = "SELECT images FROM images WHERE id='$id'";
        $result = mysqli_query($conn, $query);   // thực hiện lấy ra đường dẫn file
        if($result->num_rows>=1){
            while($row = mysqli_fetch_array($result)){
                $status = unlink("".$row['images']."");//xóa file trong máy
                if($status){
                    echo "<script type='text/javascript'>alert('Xóa thành công')</script>";
                }
            }
        }
        mysqli_query($conn, "DELETE FROM images WHERE id='$id'");//xóa trên database
    }
}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $title = $_POST['alt'];
    //tạo mảng lưu lỗi
    $error = array();
    //tạo đường dẫn file;
    $target_dir = "upload/";
    $target_file = $target_dir.basename($_FILES['avatar']['name']);
    //kiểm tra size file
    if($_FILES['avatar']['size']>5242880) {//chỉ nhận file nhỏ hơn hoặc băng 5MB
        $error['upload_file'] = "File phải có dung lượng nhỏ hơn hoặc bằng 5MB";
    }

    //kiểm tra định dạng file đang upload ảnh nên jpeg, gif, png, jpg được châp nhận
    $file_alow = array('jpeg', 'gif', 'png', 'jpg');
    if(!in_array(strtolower(pathinfo($target_file, PATHINFO_EXTENSION)), $file_alow)){
        $error['upload_file'] = "Chỉ nhận upload file ảnh";
    }
    //kiểm tra
    if(file_exists($target_file)){ //kiểm tra file đã tồn tại trên server chưa
        $error['upload_file'] = 'File đã tồn tại trên hệ thông, đổi tên file hoặc thôi upload';
    }
    if(empty($error)){
        if(move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)){ //chuyển file vào folder trên server
            $query = "insert into `images` (images, alt) values ('$target_file', '$title')";
            $result = mysqli_query($conn,$query);
            if($result){
                echo "Upload successfully";
            }else{
                echo "Upload failure";
            }
        }else{
            echo "Upload failure";
        }
    }

}

?>

<!DOCTYPE html>
<html>
<head>
	<title>upload file</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="avatar" id="avatar">
        <input type="text" name="alt" placeholder="Nhap title">
        <input type="submit" value="Upload Image" name="submit">
    </form>
    <?php
        if(!empty($error)){
    ?>
        <p style="color:red;"><?php echo $error['upload_file']; ?></p>
    <?php
        }
    ?>
    <?php
        if(empty($error)){ // nếu không có lỗi
    ?>
        <img src="<?php echo $target_file; ?>" alt="">
    <?php
        }
        $query = "SELECT id,images, alt FROM `images`";
        $result = mysqli_query($conn, $query);
        if($result->num_rows>=1){
    ?>
        <table>
        <h1>Các file đã lưu</h1>
        <?php
            $i = 0;
            while($row = mysqli_fetch_array($result)){
                $i++;
            ?>
                <tr>
                    <td style="width:50px; text-align:center;"><?php echo $i; ?></td>
                    <td><a href="<?php echo $row['images'] ?>"><img src="<?php echo $row['images']; ?>" alt="" style="width:100px; height:100px;"></a></td>
                    <td><a href="<?php echo $row['images'] ?>"><?php echo $row['alt']; ?></a></td>
                    <td><a href="?delete_id=<?php echo $row['id'];?>">Delete</a></td>
                </tr>
        <?php
            }?>
            </table>
<?php        }
    ?>
</body>
</html>
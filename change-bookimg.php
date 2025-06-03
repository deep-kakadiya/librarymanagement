<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

if(isset($_POST['update']))
{

$bookid=intval($_GET['bookid']);
$bookimg=$_FILES["bookpic"]["name"];
//currentimage
$cimage=$_POST['curremtimage'];
$cpath="bookimg"."/".$cimage;
// get the image extension
$extension = substr($bookimg,strlen($bookimg)-4,strlen($bookimg));
// allowed extensions
$allowed_extensions = array(".jpg","jpeg",".png",".gif");
// Validation for allowed extensions .in_array() function searches an array for a specific value.
//rename the image file
$imgnewname=md5($bookimg.time()).$extension;
// Code for move image into directory

if(!in_array($extension,$allowed_extensions))
{
echo "<script>alert('INVALID FORMAT ,  ONLY JPG / JPEG/ PNG /GIF FORMAT ALLOWED');</script>";
}
else
{
    move_uploaded_file($_FILES["bookpic"]["tmp_name"],"bookimg/".$imgnewname);
$sql="update  tblbooks set bookImage=:imgnewname where id=:bookid";
$query = $dbh->prepare($sql);
$query->bindParam(':imgnewname',$imgnewname,PDO::PARAM_STR);
$query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
$query->execute();
unlink($cpath);
echo "<script>alert('BOOK IMAGE UPDATED SUCCESSFULLY');</script>";
echo "<script>window.location.href='manage-books.php'</script>";

}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('includes/header.php');?>
<main>
    <h1 class="title">edit book image</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="change-bookimg.php" class="active">edit book image</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>edit book image</p>
        </div><br>
        <form role="form" method="post" enctype="multipart/form-data">
            <?php 
$bookid=intval($_GET['bookid']);
$sql = "SELECT tblbooks.BookName,tblbooks.id as bookid,tblbooks.bookImage from  tblbooks  where tblbooks.id=:bookid";
$query = $dbh -> prepare($sql);
$query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>
            <input type="hidden" name="currentimage" value="<?php echo htmlentities($result->bookImage);?>"
                style="width: 170px; height: 220px;">


            <div class="coolinput">
                <label for="input" class="text">&nbsp;Book Image&nbsp;&nbsp;</label>
                <img src="bookimg/<?php echo htmlentities($result->bookImage);?>" width="100" class="input">
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp;Book Name&nbsp;&nbsp;</label>
                <input class="input" type="text" name="bookname" value="<?php echo htmlentities($result->BookName);?>"
                    readonly />
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp;Book Picture&nbsp;&nbsp;</label>
                <input class="input" type="file" name="bookpic" autocomplete="off" required="required" />
            </div>
            <?php }} ?><div class="col-md-12">
                <button type="submit" name="update" class="bttn">Update </button>
        </form>
    </div>
</main>
</body>

</html>
<?php } ?>
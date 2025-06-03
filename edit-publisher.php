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
$pubid=intval($_GET['pubid']);
$publisher=$_POST['publisher'];
$sql="update  tblpublisher set PublisherName=:publisher where id=:pubid";
$query = $dbh->prepare($sql);
$query->bindParam(':publisher',$publisher,PDO::PARAM_STR);
$query->bindParam(':pubid',$pubid,PDO::PARAM_STR);
$query->execute();
echo "<script>alert('PUBLISHER INFORMATION UPDATED SUCCESSFULLY');</script>";
echo "<script>window.location.href='manage-publishers.php'</script>";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('includes/header.php');?>
<main>
    <h1 class="title">edit publisher</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="edit-publisher.php" class="active">edit publisher</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>edit publisher</p>
        </div><br>
        <form role="form" method="post">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; publisher Name &nbsp;&nbsp;</label>
                <?php 
                    $pubid=intval($_GET['pubid']);
                    $sql = "SELECT * from  tblpublisher where id=:pubid";
                    $query = $dbh -> prepare($sql);
                    $query->bindParam(':pubid',$pubid,PDO::PARAM_STR);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                    foreach($results as $result)
                    {               ?>
                <input class="input" type="text" name="publisher"
                    value="<?php echo htmlentities($result->PublisherName);?>" required />
                <?php }} ?>
            </div>
            <br>
            <button type="submit" name="update" class="bttn">Update </button>

        </form>
    </div>
</main>
</body>

</html>
<?php } ?>
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
$athrid=intval($_GET['athrid']);
$author=$_POST['author'];
$sql="update  tblauthors set AuthorName=:author where id=:athrid";
$query = $dbh->prepare($sql);
$query->bindParam(':author',$author,PDO::PARAM_STR);
$query->bindParam(':athrid',$athrid,PDO::PARAM_STR);
$query->execute();
echo "<script>alert('AUTHOR INFORMATION UPDATED SUCCESSFULLY');</script>";
echo "<script>window.location.href='manage-authors.php'</script>";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('includes/header.php');?>
<main>
    <h1 class="title">edit AUTHOR</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="edit-author.php" class="active">edit AUTHOR</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>edit AUTHOR</p>
        </div><br>
        <form role="form" method="post">
            <div class="coolinput">
            <label for="input" class="text">&nbsp; AUTHOR Name &nbsp;&nbsp;</label>
                <?php 
$athrid=intval($_GET['athrid']);
$sql = "SELECT * from  tblauthors where id=:athrid";
$query = $dbh -> prepare($sql);
$query->bindParam(':athrid',$athrid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>
                <input class="input" type="text" name="author"
                    value="<?php echo htmlentities($result->AuthorName);?>" required />
                <?php }} ?>
            </div>

            <button type="submit" name="update" class="bttn">Update </button>

        </form>
    </div>
</main>
</body>

</html>
<?php } ?>
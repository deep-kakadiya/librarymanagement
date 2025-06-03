<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

    if(isset($_POST['create'])) {
        $publisher = $_POST['publisher'];
        $sql_check = "SELECT COUNT(*) as count FROM tblpublisher WHERE PublisherName = :publisher";
        $query_check = $dbh->prepare($sql_check);
        $query_check->bindParam(':publisher', $publisher, PDO::PARAM_STR);
        $query_check->execute();
        $row = $query_check->fetch(PDO::FETCH_ASSOC);
        $publisherCount = $row['count'];
        if($publisherCount > 0) {
            echo "<script>alert('PUBLISHER WITH THE SAME NAME ALREADY EXISTS , PLEASE USE A DIFFERENT NAME');</script>";
            echo "<script>window.location.href='manage-publishers.php'</script>";
            exit();
        }
        $sql = "INSERT INTO tblpublisher(PublisherName) VALUES(:publisher)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':publisher', $publisher, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
    
        if($lastInsertId) {
            echo "<script>alert('NEW PUBLISHER ADDED SUCCESSFULLY');</script>";
            echo "<script>window.location.href='manage-publishers.php'</script>";
        } else {
            echo "<script>alert('SOMETHING WENT WRONG ,  PLEASE TRY AGAIN');</script>"; 
            echo "<script>window.location.href='manage-publishers.php'</script>";
        }
    }
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <?php include('includes/header.php');?>
    <main>
    <h1 class="title">add PUBLISHER</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="add-publisher.php" class="active">add new PUBLISHER</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>add PUBLISHER</p>
        </div><br>
        <form role="form" method="post">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; PUBLISHER Name &nbsp;&nbsp;</label>
                <input class="input" type="text" name="publisher" autocomplete="off" required  />
            </div>
            <br>
            <button type="submit" name="create" class="bttn">CREATE</button>
        </form>
    </div>
</main>
</body>

</html>
<?php } ?>
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
        $author = $_POST['author'];
        $sql_check = "SELECT COUNT(*) as count FROM tblauthors WHERE AuthorName = :author";
        $query_check = $dbh->prepare($sql_check);
        $query_check->bindParam(':author', $author, PDO::PARAM_STR);
        $query_check->execute();
        $row = $query_check->fetch(PDO::FETCH_ASSOC);
        $authorCount = $row['count'];
        if($authorCount > 0) {
            echo "<script>alert('AUTHOR WITH THE SAME NAME ALREADY EXISTS ,  PLEASE USE A DIFFERENT NAME');</script>";
            echo "<script>window.location.href='manage-authors.php'</script>";
            exit();
        }
        $sql = "INSERT INTO tblauthors(AuthorName) VALUES(:author)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':author', $author, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
    
        if($lastInsertId) {
            echo "<script>alert('NEW AUTHOR ADDED SUCCESSFULLY');</script>";
            echo "<script>window.location.href='manage-authors.php'</script>";
        } else {
            echo "<script>alert('SOMETHING WENT WRONG , PLEASE TRY AGAIN');</script>"; 
            echo "<script>window.location.href='manage-authors.php'</script>";
        }
    }
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('includes/header.php');?>
<main>
    <h1 class="title">add AUTHOR</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="add-author.php" class="active">add new AUTHOR</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>add AUTHOR</p>
        </div><br>
        <form role="form" method="post">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; AUTHOR Name &nbsp;&nbsp;</label>
                <input class="input" type="text" name="author" autocomplete="off" required />
            </div>
            <br>
            <button type="submit" name="create" class="bttn">CREATE</button>
        </form>
    </div>
</main>
</body>

</html>
<?php } ?>
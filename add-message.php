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
        $msubject = $_POST['msubject'];
        $message = $_POST['message'];
        $sql = "INSERT INTO tblmessage(msubject, message) VALUES(:msubject, :message)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':msubject', $msubject, PDO::PARAM_STR);
        $query->bindParam(':message', $message, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
    
        if($lastInsertId) {
            echo "<script>alert('NEW MESSAGE SENT SUCCESSFULLY');</script>";
            echo "<script>window.location.href='manage-messages.php'</script>";
        } else {
            echo "<script>alert('SOMETHING WENT WRONG. PLEASE TRY AGAIN');</script>"; 
            echo "<script>window.location.href='manage-messages.php'</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
    
    </style>
</head>
<?php include('includes/header.php');?>
<main>
    <h1 class="title">add message</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="add-message.php" class="active">add new message</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>add message</p>
        </div><br>
        <form role="form" method="post">
            <div class="coolinput">
                <label for="input" class="text">&nbsp;Subject&nbsp;&nbsp;</label>
                <input class="input" type="text" name="msubject" required>
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp;Message&nbsp;&nbsp;</label>
                <textarea class="input" type="text" name="message" required></textarea>
            </div><br>
            <button type="submit" name="create" class="bttn">CREATE</button>
        </form>
    </div>
</main>
</body>

</html>
<?php } ?>
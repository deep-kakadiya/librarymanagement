<?php
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 
if(isset($_POST['change']))
  {
$password=md5($_POST['password']);
$newpassword=md5($_POST['newpassword']);
$username=$_SESSION['alogin'];
  $sql ="SELECT Password FROM admin where UserName=:username and Password=:password";
$query= $dbh -> prepare($sql);
$query-> bindParam(':username', $username, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
if($query -> rowCount() > 0)
{
$con="update admin set Password=:newpassword where UserName=:username";
$chngpwd1 = $dbh->prepare($con);
$chngpwd1-> bindParam(':username', $username, PDO::PARAM_STR);
$chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
$chngpwd1->execute();
echo "<script>alert('PASSWORD SUCCESSFULLY CHANGED');</script>";
            echo "<script>window.location.href='dashboard.php'</script>";
}
else {
  echo "<script>alert('YOUR CURRENT PASSWORD IS WRONG');</script>";
  echo "<script>window.location.href='dashboard.php'</script>";
}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <style>
    .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }

    .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }
    </style>
</head>
<script type="text/javascript">
function valid() {
    if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
        alert("New Password and Confirm Password Field do not match  !!");
        document.chngpwd.confirmpassword.focus();
        return false;
    }
    return true;
}
</script>

<?php include('includes/header.php');?>
<main>
    <h1 class="title">CHANGE PASSWORD</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="change-password.php" class="active">CHANGE PASSWORD</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>CHANGE PASSWORD</p>
        </div><br>
        <?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
        else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
        <?php }?>
        <form role="form" method="post" onSubmit="return valid();" name="chngpwd">

            <div class="coolinput">
                <label for="input" class="text">&nbsp;Current Password&nbsp;&nbsp;</label>
                <input class="input" type="password" name="password" autocomplete="off" required />
            </div>

            <div class="coolinput">
                <label for="input" class="text">&nbsp;Enter Password&nbsp;&nbsp;</label>
                <input class="input" type="password" name="newpassword" autocomplete="off" required />
            </div>

            <div class="coolinput">
                <label for="input" class="text">&nbsp;Confirm Password &nbsp;&nbsp;</label>
                <input class="input" type="password" name="confirmpassword" autocomplete="off" required />
            </div>

            <button type="submit" name="change" class="bttn">Change </button>
        </form>
    </div>
</main>
</body>

</html>
<?php } ?>
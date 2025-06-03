<?php
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['login'])==0)
    {   
header('location:index.php');
}
else{ 
if(isset($_POST['change']))
  {
$password=md5($_POST['password']);
$newpassword=md5($_POST['newpassword']);
$email=$_SESSION['login'];
  $sql ="SELECT Password FROM tblstudents WHERE EmailId=:email and Password=:password";
$query= $dbh -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
if($query -> rowCount() > 0)
{
$con="update tblstudents set Password=:newpassword where EmailId=:email";
$chngpwd1 = $dbh->prepare($con);
$chngpwd1-> bindParam(':email', $email, PDO::PARAM_STR);
$chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
$chngpwd1->execute();
echo"<script>alert('YOUR PASSWORD CHANGE SUCCESSFULLY')</script>";
}
else {
    echo"<script>alert('YOUR CURRENT PASSWORD IS WRONG')</script>";
}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<script type="text/javascript">
function valid() {
    if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
        alert("NEW PASSWORD AND CONFORM PASSWORD DOES NOT MATCH !!");
        document.chngpwd.confirmpassword.focus();
        return false;
    }
    return true;
}
</script>
<?php include('includes/header.php');?>
<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">User Change Password</h4>
            </div>
        </div>
        <br>
        <hr><br><br>
        <?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
        else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
        <?php }?>
        <div class="bigbox">
            <br>
            <form role="form" method="post" onSubmit="return valid();" name="chngpwd">
                <div class="form-group">
                    <div class="coolinput">
                        <label for="input" class="text">&nbsp; Current Password &nbsp;&nbsp;</label>
                        <input class="input" type="password" name="password" autocomplete="off" required />
                    </div>
                    <div class="coolinput">
                        <label for="input" class="text">&nbsp;new Password &nbsp;&nbsp;</label>
                        <input class="input" type="password" name="newpassword" autocomplete="off" required />
                    </div>
                    <div class="coolinput">
                        <label for="input" class="text">&nbsp; Confirm Password &nbsp;&nbsp;</label>
                        <input class="input" type="password" name="confirmpassword" autocomplete="off" required />
                    </div>
                </div><br>
                <button type="submit" name="change" class="bttn"> change now</button>
            </form><br>
        </div>
</body>

</html>
<?php } ?>
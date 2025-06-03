<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['login'])==0)
    {   
header('location:index.php');
}
else{ 
if(isset($_POST['update']))
{    
$sid=$_SESSION['stdid'];  
$fname=$_POST['fullanme'];
$mobileno=$_POST['mobileno'];

$sql="update tblstudents set FullName=:fname,MobileNumber=:mobileno where StudentId=:sid";
$query = $dbh->prepare($sql);
$query->bindParam(':sid',$sid,PDO::PARAM_STR);
$query->bindParam(':fname',$fname,PDO::PARAM_STR);
$query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
$query->execute();

echo '<script>alert("YOUR PROFILE HAS BEEN UPDATED")</script>';
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">My Profile</h4>

            </div>

        </div><br>
        <hr><br>
        <div class="bigbox"><br>
            <form name="signup" method="post">
                <?php 
                    $sid=$_SESSION['stdid'];
                    $sql="SELECT StudentId,FullName,EmailId,MobileNumber,RegDate,UpdationDate,Status from  tblstudents  where StudentId=:sid ";
                    $query = $dbh -> prepare($sql);
                    $query-> bindParam(':sid', $sid, PDO::PARAM_STR);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                    foreach($results as $result)
                    {               
                ?>
                <p class="texts">Student ID
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    : <?php echo htmlentities($result->StudentId);?> <br>
                    Register Date
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                    <?php echo htmlentities($result->RegDate);?> <br>
                    Last Updation Date &nbsp;&nbsp;&nbsp;&nbsp;: <?php echo htmlentities($result->UpdationDate);?>
                    <br>
                    Profile Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                    <?php if($result->Status==1){?>
                    <span style="color: green">Active</span>
                    <?php } else { ?>
                    <span style="color: red">Blocked</span>
                    <?php }?>
                </p>
                <br>
                <div class="form-group">
                    <div class="coolinput">
                        <label for="input" class="text">&nbsp; STUDENT NAME &nbsp;&nbsp;</label>
                        <input class="input" type="text" name="fullanme"
                            value="<?php echo htmlentities($result->FullName);?>" autocomplete="off" required />
                    </div>
                    <div class="coolinput">
                        <label for="input" class="text">&nbsp; mobile number &nbsp;&nbsp;</label>
                        <input class="input" type="text" name="mobileno" maxlength="10"
                            value="<?php echo htmlentities($result->MobileNumber);?>" autocomplete="off" required />
                    </div>
                    <div class="coolinput">
                        <label for="input" class="text">&nbsp; email adderess &nbsp;&nbsp;</label>
                        <input class="input" type="email" name="email" id="emailid"
                            value="<?php echo htmlentities($result->EmailId);?>" autocomplete="off" required readonly />
                    </div>
                    <p class="isbntext">EMAIL CAN NOT CHANGE THIS TIME.</p>
                </div>
                <?php }} ?>
                <br>
                <button type="submit" name="update" class="bttn" id="submit">Update Now
                </button>
            </form><br>
        </div>
        </body>

</html>
<?php } ?>
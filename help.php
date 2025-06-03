<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (isset($_POST['submit'])) {
    $studentname = $_POST['studentname'];
    $mobilenumber = $_POST['mobilenumber'];
    $emailaddress = $_POST['emailaddress'];
    $question = $_POST['question'];
    $sql = "INSERT INTO tblhelp (studentname, mobilenumber, emailaddress, question) 
    VALUES ('$studentname', '$mobilenumber', '$emailaddress', '$question')";
    if ($dbh->query($sql)) {
        echo '<script>alert("YOUR QUESTION HAS BEEN SENT TO ADMIN")</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $dbh->error;
    }
}    
if (strlen($_SESSION['login']) == 0) {
  header('location:index.php');
} else { ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <style>

    </style>
</head>
<?php include("includes/header.php"); ?>
<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">help us ?</h4>

            </div>

        </div>
        <br>
        <hr><br>
        <div class="bigbox">
            <div class="container">
                <div class="content">
                    <div class="left-side">
                        <div class="address details">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="topic">Address</div>
                            <a href="https://maps.app.goo.gl/wpbPGMzrCKV9ur2B9"><div class="text-one"> 3rd floor</div>
                            <div class="text-two">sdj international</div></a>
                        </div>
                        <div class="phone details">
                            <i class="fas fa-phone-alt"></i>
                            <div class="topic">Phone</div>
                            <a href="https://wa.link/n9gwa3"><div class="text-one">+91 92282 32989</div></a>
                            
                        </div>
                        <div class="email details">
                            <i class="fas fa-envelope"></i>
                            <div class="topic">Email</div>
                            <a href="mailto:deepkakadiya2021.com"><div class="text-one">deepkakadiya2021.com</div></a>
                        </div>
                    </div>
                    <div class="right-side"><br>
                        <div class="topic-text">&nbsp;&nbsp;&nbsp;Send us a message</div><br>

                        <form action="#" method="post" onSubmit="return valid();" name="submit">
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
                            <div class="coolinput">
                                <label for="input" class="text">&nbsp; STUDENT NAME &nbsp;&nbsp;</label>
                                <input class="input" type="text" name="studentname"
                                    value="<?php echo htmlentities($result->FullName);?>" autocomplete="off" required readonly/>
                            </div>
                            <div class="coolinput">
                                <label for="input" class="text">&nbsp; PHONE NUMBER &nbsp;&nbsp;</label>
                                <input class="input" type="text" name="mobilenumber"
                                    value="<?php echo htmlentities($result->MobileNumber);?>" autocomplete="off" required readonly/>
                            </div>
                            <div class="coolinput">
                                <label for="input" class="text">&nbsp; email adderess &nbsp;&nbsp;</label>
                                <input class="input" type="email" name="emailaddress" id="emailid" autocomplete="off"
                                    required onkeyup="EmailValidate()"
                                    value="<?php echo htmlentities($result->EmailId);?>"  readonly/>
                            </div>
                            <?php }}?>
                            <div class="coolinput">
                                <label for="input" class="text">&nbsp; your question &nbsp;&nbsp;</label>
                                <textarea class="input" type="text" name="question" id="question" autocomplete="off"
                                    required></textarea>
                            </div><br>
                            <button type="submit" name="submit" class="bttn" id="submit">send Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
<?php }?>
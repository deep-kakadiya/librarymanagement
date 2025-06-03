<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
  header('location:index.php');
} else { ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include("includes/header.php"); ?>

<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">all message</h4>

            </div>

        </div>
        <br>
        <hr><br><br>
        <?php $sql = "SELECT * from tblmessage";
            $query = $dbh -> prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            $cnt=1;
            if($query->rowCount() > 0)
            {
            foreach($results as $result)
            {               
        ?>
        <div class="toast">
            <div class="toast-content">
            <i class="ri-message-3-fill check"></i>

                <div class="message">
                    <span class="text text-1">- <?php echo htmlentities($result->msubject);?></span>
                    <span class="text text-2">- <?php echo htmlentities($result->message);?></span><br>
                    <span class="text text-2">- by admin</span>
                </div>
            </div>
        </div>
        <br>
        <?php }}?>
        <br>
    </div>
</div>
</body>

</html>
<?php } ?>
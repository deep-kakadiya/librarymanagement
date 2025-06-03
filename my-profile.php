<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('includes/header.php');?>
<main>
    <h1 class="title">my profile</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="my-profile.php" class="active">my profile</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>my profile</p>
        </div><br>
        <form name="signup" method="post">
            <?php $sql = "SELECT * from  admin";
            $query = $dbh -> prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            $cnt=1;
            if($query->rowCount() > 0)
            {
            foreach($results as $result)
            {               ?>
            <br>

            <div class="coolinput">
                <label for="input" class="text">&nbsp; admin NAME &nbsp;&nbsp;</label>
                <input class="input" type="text" name="FullName" value="<?php echo htmlentities($result->FullName);?>"
                    autocomplete="off" required readonly/>
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp; admin username &nbsp;&nbsp;</label>
                <input class="input" type="text" name="UserName" value="<?php echo htmlentities($result->UserName);?>"
                    autocomplete="off" required readonly/>
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp; admin EMAIL ADDRESS &nbsp;&nbsp;</label>
                <input class="input" type="email" name="AdminEmail" id="emailid"
                    value="<?php echo htmlentities($result->AdminEmail);?>" autocomplete="off" required readonly />
            </div>

            <p class="isbntext">details are only for read purpose not change.</p>
            <?php 
            }
        }
    ?>
            <br>
        </form><br>

    </div>
</main>
</body>

</html>
<?php } ?>
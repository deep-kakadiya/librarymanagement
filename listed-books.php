<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
    {   
header('location:index.php');
}
else{ 



    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<!------MENU SECTION START-->
<?php include('includes/header.php');?>
<div class="container">
    <br><br>
    <div class="col-md-12">
        <h4 class="header-line">ALL BOOKS</h4>
    </div>

    <br>
    <hr><br><br>

    <div class="box-container">
        <?php $sql = "SELECT * from tblbooks join tblauthors on tblbooks.AuthorId=tblauthors.id join tblcategory on tblbooks.CatId=tblcategory.id";
            $query = $dbh -> prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            $cnt=1;
            if($query->rowCount() > 0)
            {
            foreach($results as $result)
            {               
        ?>
        <div class="box">
            <img src="admin/bookimg/<?php echo htmlentities($result->bookImage);?>" alt="">
            <h3><b><?php echo htmlentities($result->BookName);?></b></h3>
            <h4><b class="mainmain">ISBN NO : <?php echo htmlentities($result->ISBNNumber);?></b></h4>
            <h4><b class="mainmain"><?php echo htmlentities($result->AuthorName);?></b></h4>
            <h4><b class="mainmain"><?php echo htmlentities($result->CategoryName);?></b></h4>
            <?php if ($result->isIssued == '1'): ?>
            <p style="padding: 5px;
  background-color: #ff4d4d ;
  color: BLACK; border-radius: 10px; FONT-WEIGHT: 600; FONT-SIZE: 15PX;">Book Already issued</p>
            <?php else: ?>
            <p style="padding: 5px;
  background-color: #4dff4d;
  color: BLACK; border-radius: 10px; FONT-WEIGHT: 600; FONT-SIZE: 15PX;">book availble for you</p>
            <?php endif; ?>

        </div>
        <?php $cnt=$cnt+1;}} ?>
    </div>

</div>
<br><br>
</body>

</html>
<?php } ?>
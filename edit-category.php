<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

if(isset($_POST['update']))
{
$category=$_POST['category'];
$status=$_POST['status'];
$catid=intval($_GET['catid']);
$sql="update  tblcategory set CategoryName=:category,Status=:status where id=:catid";
$query = $dbh->prepare($sql);
$query->bindParam(':category',$category,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->bindParam(':catid',$catid,PDO::PARAM_STR);
$query->execute();
echo "<script>alert('CATEGORY UPDATED SUCCESSFULLY');</script>";
echo "<script>window.location.href='manage-categories.php'</script>";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include('includes/header.php');?>
<main>
    <h1 class="title">edit CATEGORY</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="edit-book.php" class="active">edit CATEGORY</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>edit CATEGORY</p>
        </div><br>
        <form role="form" method="post">
            <?php 
            $catid=intval($_GET['catid']);
            $sql="SELECT * from tblcategory where id=:catid";
            $query=$dbh->prepare($sql);
            $query-> bindParam(':catid',$catid, PDO::PARAM_STR);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            if($query->rowCount() > 0)
            {
            foreach($results as $result)
            {               
                ?>
            <div class="coolinput">
                <label for="input" class="text">Category Name</label>
                <input class="input" type="text" name="category"
                    value="<?php echo htmlentities($result->CategoryName);?>" required />
            </div>
            <div class=""><br>&nbsp;
                <?php if($result->Status==1) {?>
                <label class="custom-radio">
                    <input type="radio" name="status" id="status" value="1" checked="checked" />
                    <span class="radio-btn">
                        <div class="hobbies-icon">
                            <i>
                                <div class="true-symbol">&#10003;</div>
                            </i>
                            <p class="text"><strong>active</strong></p>

                        </div>
                    </span>
                </label>
                <label class="custom-radio">
                    <input type="radio" name="status" id="status" value="0" />
                    <span class="radio-btn">
                        <div class="hobbies-icon">
                            <i>
                                <div class="wrong-symbol">&#10007;</div>
                            </i>
                            <p class="text"><strong>inactive</strong></p>
                        </div>
                    </span>
                </label>
                <?php } else { ?>
                    <label class="custom-radio">
                    <input type="radio" name="status" id="status" value="1"  />
                    <span class="radio-btn">
                        <div class="hobbies-icon">
                            <i>
                                <div class="true-symbol">&#10003;</div>
                            </i>
                            <p class="text"><strong>active</strong></p>

                        </div>
                    </span>
                </label>
                <label class="custom-radio">
                    <input type="radio" name="status" id="status" value="0" checked="checked"/>
                    <span class="radio-btn">
                        <div class="hobbies-icon">
                            <i>
                                <div class="wrong-symbol">&#10007;</div>
                            </i>
                            <p class="text"><strong>inactive</strong></p>
                        </div>
                    </span>
                </label>
                  <?php } ?> </div>
                <?php }} ?><br><br>
                <button type="submit" name="update" class="bttn">Update </button>

        </form>
    </div>
</main>
</body>

</html>
<?php } ?>
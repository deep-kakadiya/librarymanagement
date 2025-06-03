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
        $category = $_POST['category'];
        $status = $_POST['status'];
        
        // Check if the category already exists
        $checkSql = "SELECT COUNT(*) as count FROM tblcategory WHERE CategoryName = :category";
        $checkQuery = $dbh->prepare($checkSql);
        $checkQuery->bindParam(':category', $category, PDO::PARAM_STR);
        $checkQuery->execute();
        $result = $checkQuery->fetch(PDO::FETCH_ASSOC);
        $categoryCount = $result['count'];
        
        if($categoryCount > 0) {
            // Category already exists, handle accordingly (e.g., show an error message)
            echo "<script>alert('CATEGORY WITH THE SAME NAME ALREADY EXISTS , PLEASE USE A DIFFERENT NAME');</script>";
            echo "<script>window.location.href='manage-categories.php'</script>";
        } else {
            // Category does not exist, proceed with insertion
            $sql = "INSERT INTO tblcategory (CategoryName, Status) VALUES (:category, :status)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':category', $category, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            
            if($lastInsertId) {
                echo "<script>alert('NEW CATEGORY ADDED SUCCESSFULLY');</script>";
                echo "<script>window.location.href='manage-categories.php'</script>";
            } else {
                echo "<script>alert('SOMETHING WENT WRONG. PLEASE TRY AGAIN');</script>";   
                echo "<script>window.location.href='manage-categories.php'</script>"; 
            }
        }
    }
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include('includes/header.php');?>
<main>
    <h1 class="title">add category</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="add-category.php" class="active">add new category</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>add category</p>
        </div><br>
        <form role="form" method="post">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; Category Name &nbsp;&nbsp;</label>
                <input class="input" type="text" name="category" autocomplete="off" required />
            </div><br>
            <div class="coolinput">
                <div class="main-container">
                    <div class="radio-buttons">
                        <label class="custom-radio">
                            <input type="radio" name="status" id="active" value="1" checked="checked" />
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
                            <input type="radio" name="status" id="inactive" value="0" />
                            <span class="radio-btn">
                                <div class="hobbies-icon">
                                    <i>
                                        <div class="wrong-symbol">&#10007;</div>
                                    </i>
                                    <p class="text"><strong>inactive</strong></p>
                                </div>
                            </span>
                        </label>
                    </div>
                </div>
            </div><br><br>
            <button type="submit" name="create" class="bttn">CREATE </button>
        </form>
    </div>
</main>
</body>

</html>
<?php } ?>
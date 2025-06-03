<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

    if(isset($_POST['update'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $author = $_POST['author'];
        $publisher = $_POST['publisher'];
        $isbn = $_POST['isbn'];
        $price = $_POST['price'];
        $bookid = intval($_GET['bookid']);
    
        // Assuming $dbh is your PDO database connection object
        $sql = "UPDATE tblbooks SET BookName=:bookname, CatId=:category, AuthorId=:author, PubId=:publisher, BookPrice=:price WHERE id=:bookid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':author', $author, PDO::PARAM_STR);
        $query->bindParam(':publisher', $publisher, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_INT); // Changed to PARAM_INT
        $query->execute();
    
        echo "<script>alert('BOOK INFORMATION UPDATED SUCCESSFULLY');</script>";
        echo "<script>window.location.href='manage-books.php'</script>";
    }
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('includes/header.php');?>
<main>
    <h1 class="title">edit book</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="edit-book.php" class="active">edit book</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>edit book</p>
        </div><br>
        <form role="form" method="post">
            <?php
    // Assuming $dbh is your PDO database connection object

    // Sanitize input
    $bookid = isset($_GET['bookid']) ? intval($_GET['bookid']) : 0;

    // Prepare and execute SQL query
    $sql = "SELECT tblbooks.BookImage, tblbooks.BookName, tblbooks.ISBNNumber, tblbooks.BookPrice, tblbooks.CatId, tblbooks.AuthorId, tblcategory.CategoryName, tblauthors.AuthorName, tblpublisher.id as publisher_id, tblpublisher.PublisherName 
            FROM tblbooks 
            INNER JOIN tblcategory ON tblbooks.CatId = tblcategory.id 
            INNER JOIN tblauthors ON tblbooks.AuthorId = tblauthors.id 
            INNER JOIN tblpublisher ON tblbooks.PubId = tblpublisher.id 
            WHERE tblbooks.id = :bookid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookid', $bookid, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Check if data is found
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
?>
            <div class="coolinput">
                <label for="input" class="text">&nbsp;Book Image &nbsp;&nbsp;</label>
                <img src="bookimg/<?php echo htmlentities($result['BookImage']);?>" class="input">
                <a href="change-bookimg.php?bookid=<?php echo htmlentities($bookid);?>">
                    <p class="isbntext" style="color: red;">CHANGE BOOK PICTURE</p>
                </a>
            </div><br>
            <div class="coolinput">
                <label for="input" class="text">&nbsp; Book Name &nbsp;&nbsp;</label>
                <input class="input" type="text" name="bookname" value="<?php echo htmlentities($result['BookName']);?>"
                    required />
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp;book ISBN Number&nbsp;&nbsp;</label>
                <input class="input" type="text" name="isbn" value="<?php echo htmlentities($result['ISBNNumber']);?>"
                    readonly />
            </div>
            <p class="isbntext">ISBN NUMBER CAN NOT CHANGE THIS TIME.</p>
            <div class="coolinput">
                <label for="input" class="text">&nbsp; book price&nbsp;&nbsp;</label>
                <input class="input" type="text" name="price" value="<?php echo htmlentities($result['BookPrice']);?>"
                    required="required" />
            </div>
            <div class="coolinput">
                <label for="input" class="text"> &nbsp; select Category&nbsp;&nbsp;</label>
                <select class="input" name="category" required="required">
                    <option value="<?php echo htmlentities($result['CatId']);?>">
                        <?php echo htmlentities($result['CategoryName']);?></option>
                    <?php 
                        // Fetch all categories
                        $sql1 = "SELECT * FROM tblcategory WHERE Status = 1";
                        $query1 = $dbh->prepare($sql1);
                        $query1->execute();
                        $categories = $query1->fetchAll(PDO::FETCH_ASSOC);
                        foreach($categories as $category) {
                            if($category['id'] != $result['CatId']) {
                    ?>
                    <option value="<?php echo htmlentities($category['id']);?>">
                        <?php echo htmlentities($category['CategoryName']);?></option>
                    <?php 
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp; select Author &nbsp;&nbsp;</label>
                <select class="input" name="author" required="required">
                    <option value="<?php echo htmlentities($result['AuthorId']);?>">
                        <?php echo htmlentities($result['AuthorName']);?></option>
                    <?php 
                        // Fetch all authors
                        $sql2 = "SELECT * FROM tblauthors";
                        $query2 = $dbh->prepare($sql2);
                        $query2->execute();
                        $authors = $query2->fetchAll(PDO::FETCH_ASSOC);
                        foreach($authors as $author) {
                            if($author['id'] != $result['AuthorId']) {
                    ?>
                    <option value="<?php echo htmlentities($author['id']);?>">
                        <?php echo htmlentities($author['AuthorName']);?></option>
                    <?php 
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp; Select Publisher &nbsp;&nbsp;</label>
                <select class="input" name="publisher" required="required">
                    <option value="<?php echo htmlentities($result['publisher_id']);?>">
                        <?php echo htmlentities($result['PublisherName']);?></option>
                    <?php 
                        $sql3 = "SELECT * from tblpublisher";
                        $query3 = $dbh->prepare($sql3);
                        $query3->execute();
                        $result3 = $query3->fetchAll(PDO::FETCH_ASSOC);
                        if($query3->rowCount() > 0) {
                            foreach($result3 as $pub) {
                                if($pub['id'] != $result['publisher_id']) {
                    ?>
                    <option value="<?php echo htmlentities($pub['id']);?>">
                        <?php echo htmlentities($pub['PublisherName']);?></option>
                    <?php }}} ?>
                </select>
            </div>
            <?php
        }
    } else {
        echo "NO DATA FOUND";
    }
?>


            <button type="submit" name="update" class="bttn">Update </button>
        </form>
    </div>
</main>
</body>

</html>
<?php } ?>
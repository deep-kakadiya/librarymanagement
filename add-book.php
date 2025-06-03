<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 
    if(isset($_POST['add'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $author = $_POST['author'];
        $publisher = $_POST['publisher'];
        $isbn = $_POST['isbn'];
        $price = $_POST['price'];
        $bookimg = $_FILES["bookpic"]["name"];
        $sql_check_isbn = "SELECT COUNT(*) AS count FROM tblbooks WHERE ISBNNumber = :isbn";
        $query_check_isbn = $dbh->prepare($sql_check_isbn);
        $query_check_isbn->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $query_check_isbn->execute();
        $result_check_isbn = $query_check_isbn->fetch(PDO::FETCH_ASSOC);
        if ($result_check_isbn['count'] > 0) {
            echo "<script>alert('BOOK WITH THE SAME ISBN NUMBER ALREADY EXISTS ,  PLEASE USE A DIFFERENT ISBN');</script>";
            echo "<script>window.location.href='manage-books.php'</script>";
        } else {
            $extension = substr($bookimg,strlen($bookimg)-4,strlen($bookimg));
            $allowed_extensions = array(".jpg","jpeg",".png",".gif");
            $imgnewname=md5($bookimg.time()).$extension;
            if(!in_array($extension,$allowed_extensions))
            {
                echo "<script>alert('INVALID FORMAT. ONLY JPG / JPEG/ PNG /GIF FORMAT ALLOWED');</script>";
            }
            else
            {
                move_uploaded_file($_FILES["bookpic"]["tmp_name"],"bookimg/".$imgnewname);
                $sql="INSERT INTO  tblbooks(BookName,CatId,AuthorId,PubId,ISBNNumber,BookPrice,bookImage) VALUES(:bookname,:category,:author,:publisher,:isbn,:price,:imgnewname)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':bookname',$bookname,PDO::PARAM_STR);
                $query->bindParam(':category',$category,PDO::PARAM_STR);
                $query->bindParam(':author',$author,PDO::PARAM_STR);
                $query->bindParam(':publisher',$publisher,PDO::PARAM_STR);
                $query->bindParam(':isbn',$isbn,PDO::PARAM_STR);
                $query->bindParam(':price',$price,PDO::PARAM_STR);
                $query->bindParam(':imgnewname',$imgnewname,PDO::PARAM_STR);
                $query->execute();
                $lastInsertId = $dbh->lastInsertId();
                if($lastInsertId)
                {
                    echo "<script>alert('NEW BOOK ADDED SUCCESSFULLY');</script>";
                    echo "<script>window.location.href='manage-books.php'</script>";
                }
                else 
                {
                    echo "<script>alert('SOMETHING WENT WRONG ,  PLEASE TRY AGAIN');</script>";    
                    echo "<script>window.location.href='manage-books.php'</script>";
                }
            }
        }
    }
    

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include('includes/header.php');?>
<main>
    <h1 class="title">add book</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="add-book.php" class="active">add new book</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>add book</p>
        </div><br>
        <form role="form" method="post" enctype="multipart/form-data">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; book NAME &nbsp;&nbsp;</label>
                <input class="input" type="text" name="bookname" autocomplete="off" required />
            </div>
            <div class="coolinput">
                <label for="isbn" class="text">&nbsp; Book ISBN number &nbsp;&nbsp;</label>
                <input class="input" type="text" name="isbn" id="isbn" required="required" maxlength="10"
                    autocomplete="off" oninput="limitLength(this, 10)" readonly/>
            </div>

            <script>
            window.onload = function() {
                generateISBN();
            };

            function generateISBN() {
                var isbn = generateRandomISBN();
                document.getElementById('isbn').value = isbn;
            }

            function generateRandomISBN() {
                var length = 10; // Length of the ISBN
                var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Allowed characters
                var isbn = '';

                for (var i = 0; i < length; i++) {
                    var randomIndex = Math.floor(Math.random() * characters.length);
                    isbn += characters.charAt(randomIndex);
                }

                return isbn;
            }

            function limitLength(element, maxLength) {
                if (element.value.length > maxLength) {
                    element.value = element.value.slice(0, maxLength);
                }
            }
            </script>

            <p class="isbntext">ISBN NUMBER ARE NOT SAME FOR ANOTHER BOOKS.</p>
            <p class="isbntext">ISBN NUMBER ARE should be only 10 letter.</p>
            <div class="coolinput">
                <label for="input" class="text">&nbsp; book price &nbsp;&nbsp;</label>
                <input class="input" type="text" name="price" autocomplete="off" required="required" />
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp; book picture &nbsp;&nbsp;</label>
                <input class="input" type="file" name="bookpic" autocomplete="off" required="required" />
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp;select CATEGORY &nbsp;&nbsp;</label>
                <select class="input" name="category" required="required">
                    <option value="">select category</option>
                    <?php 
                    $status=1;
                    $sql = "SELECT * from  tblcategory where Status=:status";
                    $query = $dbh -> prepare($sql);
                    $query -> bindParam(':status',$status, PDO::PARAM_STR);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                    foreach($results as $result)
                    {               
                ?>
                    <option value="<?php echo htmlentities($result->id);?>">
                        <?php echo htmlentities($result->CategoryName);?></option>
                    <?php }} ?>
                </select>
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp;select author &nbsp;&nbsp;</label>
                <select class="input" name="author" required="required">
                    <option value="">select author</option>
                    <?php 
                    $sql = "SELECT * from  tblauthors ";
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                    foreach($results as $result)
                    {               
                ?>
                    <option value="<?php echo htmlentities($result->id);?>">
                        <?php echo htmlentities($result->AuthorName);?></option>
                    <?php }} ?>
                </select>
            </div>
            <div class="coolinput">
                <label for="input" class="text">&nbsp;select publisher &nbsp;&nbsp;</label>
                <select class="input" name="publisher" required="required">
                    <option value="">select publisher</option>
                    <?php 
                    $sql = "SELECT * from  tblpublisher";
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                    foreach($results as $result)
                    {               
                ?>
                    <option value="<?php echo htmlentities($result->id);?>">
                        <?php echo htmlentities($result->PublisherName);?></option>
                    <?php }} ?>
                </select>
            </div>
            <button type="submit" name="add" id="add" class="bttn">CREATE </button>
        </form>
    </div>
</main>
</body>

</html>
<?php } ?>
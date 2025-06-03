<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 
    if(isset($_POST['return'])) {
        $rid = intval($_GET['rid']);
        $fine = $_POST['fine'];
        $rstatus = 1;
        $bookid = $_POST['bookid'];
    
        // Update SQL query
        $sql = "UPDATE tblissuedbookdetails SET fine = :fine, RetrunStatus = :rstatus WHERE id = :rid;
                UPDATE tblbooks SET isIssued = 0 WHERE id = :bookid";
        
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_INT);
        $query->bindParam(':fine', $fine, PDO::PARAM_STR);
        $query->bindParam(':rstatus', $rstatus, PDO::PARAM_INT);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_INT);
        
        $query->execute();
    
        echo "<script>alert('BOOK RETURNED SUCCESSFULLY');</script>";
        echo "<script>window.location.href = 'manage-issued-books.php'</script>";
    }    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include('includes/header.php');?>
<main>
    <h1 class="title">update issued book</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="update-issue-bookdetails.php" class="active">update issued book</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>update issued book</p>
        </div><br>
        <form role="form" method="post">
            <?php 
$rid=intval($_GET['rid']);
$sql = "SELECT tblstudents.StudentId ,tblstudents.FullName,tblstudents.EmailId,tblstudents.MobileNumber,tblbooks.BookName,tblbooks.ISBNNumber,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.FineDate,tblissuedbookdetails.ReturnDate,tblissuedbookdetails.id as rid,tblissuedbookdetails.fine,tblissuedbookdetails.RetrunStatus,tblbooks.id as bid,tblbooks.bookImage from  tblissuedbookdetails join tblstudents on tblstudents.StudentId=tblissuedbookdetails.StudentId join tblbooks on tblbooks.id=tblissuedbookdetails.BookId where tblissuedbookdetails.id=:rid";
$query = $dbh -> prepare($sql);
$query->bindParam(':rid',$rid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>



            <input type="hidden" name="bookid" value="<?php echo htmlentities($result->bid);?>">
            <h4 class="titlee">Student Details</h4>
            <div class="col-md-6">
                <div>
                    <label><b>Student ID
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b></label>
                    <?php echo htmlentities($result->StudentId);?>
                </div>
            </div>

            <div class="col-md-6">
                <div>
                    <label><b>Student Name
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b></label>
                    <?php echo htmlentities($result->FullName);?>
                </div>
            </div>

            <div class="col-md-6">
                <div>
                    <label><b>Student Email Id
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b></label>
                    <?php echo htmlentities($result->EmailId);?>
                </div>
            </div>

            <div class="col-md-6">
                <div>
                    <label><b>Student Contact No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</b></label>
                    <?php echo htmlentities($result->MobileNumber);?>
                </div>
            </div>

            <br><br>

            <h4 class="titlee">Book Details</h4>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Book
                        Image&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        :</label>
                    <img src="bookimg/<?php echo htmlentities($result->bookImage); ?>" width="120">
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label>Book
                        Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        :</label>
                    <?php echo htmlentities($result->BookName);?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>ISBN no
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
                    <?php echo htmlentities($result->ISBNNumber);?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Book Issued Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
                    <?php echo htmlentities($result->IssuesDate);?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Book Returned Date &nbsp;&nbsp;&nbsp;:</label>
                    <?php if($result->ReturnDate=="")
                                            {
                                                echo htmlentities("Not Return Yet");
                                            } else {


                                            echo htmlentities($result->ReturnDate);
}
                                            ?>
                </div>
            </div>
            <?php if($result->RetrunStatus==0){?>
            <?php
// Define the calculateFine function
                function calculateFine($dueDate) {
                    // Assuming the due date format is YYYY-MM-DD
                    $currentDate = date("Y-m-d");
                    $dueDateTime = strtotime($dueDate);
                    $currentDateTime = strtotime($currentDate);
                    
                    // Calculate the difference in days
                    $difference = $currentDateTime - $dueDateTime;
                    $daysOverdue = floor($difference / (60 * 60 * 36));
                    
                    // Assuming a fixed fine amount per day overdue
                    $finePerDay = 20; // Adjust this value as needed
                    $fineAmount = $daysOverdue * $finePerDay;
                    
                    return $fineAmount;
                }
                        $dueDate = $result->FineDate;
                        $fineAmount = calculateFine($dueDate);
                        ?><br>
            <div class="coolinput">
                <label for="input" class="text">&nbsp; payable fine &nbsp;&nbsp;</label>
                <input class="input" id="searchInput" type="text" name="fine" autocomplete="off" onkeyup="filterTable()"
                    value="<?php echo htmlentities($fineAmount); ?>" readonly />
            </div>


            <button type="submit" name="return" id="submit" class="bttn">Return Book</button>

    </div>

    <?php }}} ?>
    </form><br>
    </div>
</main><br><br>
<script src="assets/js/jquery-1.10.2.js"></script>

</body>

</html>
<?php } ?>
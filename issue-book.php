<?php
session_start();
error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include('includes/config.php');
function smtp_mailer($to, $subject, $msg) {
    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';
    
    $mail = new PHPMailer(); 
    $mail->IsSMTP(); 
    $mail->SMTPDebug = 1; 
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'TLS'; 
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587; 
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = "";
    $mail->Password = "";
    $mail->SetFrom("deepkakadiya2021@gmail.com","LIBRARY MANAGEMENT");
    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->AddAddress($to);
    if(!$mail->Send()){
        return 0;
    } else {
        return 1;
    }
}

if(strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
    exit(); // Add exit to stop script execution after redirection
} else { 
    if(isset($_POST['issue'])) {
        $studentid = strtoupper($_POST['studentid']);
        $bookid = $_POST['bookid'];
        $isissued = 1;
    
        // Use a transaction for atomicity
        try {
            $dbh->beginTransaction();
            
            // Prepare and execute the INSERT query
            $insertSql = "INSERT INTO tblissuedbookdetails (StudentID, BookId) VALUES (:studentid, :bookid)";
            $insertQuery = $dbh->prepare($insertSql);
            $insertQuery->bindParam(':studentid', $studentid, PDO::PARAM_STR);
            $insertQuery->bindParam(':bookid', $bookid, PDO::PARAM_STR);
            $insertQuery->execute();
            
            // Prepare and execute the UPDATE query
            $updateSql = "UPDATE tblbooks SET isIssued = :isissued WHERE id = :bookid";
            $updateQuery = $dbh->prepare($updateSql);
            $updateQuery->bindParam(':isissued', $isissued, PDO::PARAM_INT); // Assuming isIssued is an integer field
            $updateQuery->bindParam(':bookid', $bookid, PDO::PARAM_STR);
            $updateQuery->execute();     
            $dbh->commit();

                $currentDate = date("Y-m-d");
                $emailQuery = "SELECT EmailId FROM tblstudents WHERE StudentId = :studentid";
                $emailStmt = $dbh->prepare($emailQuery);
                $emailStmt->bindParam(':studentid', $studentid, PDO::PARAM_STR);
                $emailStmt->execute();
                $studentEmail = $emailStmt->fetchColumn();

                $bookQuery = "SELECT BookName, ISBNNumber FROM tblbooks WHERE id = :bookid";
                $bookStmt = $dbh->prepare($bookQuery);
                $bookStmt->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                $bookStmt->execute();
                $bookData = $bookStmt->fetch(PDO::FETCH_ASSOC);
                $bookName = strtoupper($bookData['BookName']);
                $ISBNNumber = $bookData['ISBNNumber'];

                $fullNameQuery = "SELECT FullName FROM tblstudents WHERE StudentId = :studentid";
                $fullNameStmt = $dbh->prepare($fullNameQuery);
                $fullNameStmt->bindParam(':studentid', $studentid, PDO::PARAM_STR);
                $fullNameStmt->execute();
                $fullName = $fullNameStmt->fetchColumn();
                $fullName = strtoupper($fullName);
            if ($studentEmail) {
                // Construct and send email
                $msg = "";
                $mailHtml = 
                "<div style='background-color: black; border-radius: 10px; color:white; padding: 10px; display: inline-block;'>
                    <div style='color:white;'>
                        HEY , $fullName<br></div><div style='color:white;'><br>
                        YOUR BOOK IS ISSUED SUCCESSFULLY , <br><br></div>
                        <div style='color:white;'>ISSUED DATE &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; $currentDate<br>    </div>
                        <div style='color:white;'>BOOK NAME &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; $bookName<br>  </div>
                        <div style='color:white;'>ISBN NUMBER &nbsp;&nbsp;:&nbsp; $ISBNNumber<br>  
                    </div>
                    <p style='color: red;'>
                        TIME LIMIT OF RETURNING A BOOK IS MINIMUM 2 DAYS, AFTER 2 DAYS WE COLLECT FINE 20 RUPEES PER DAY.
                    </p>
                    <br>
                    <div style='color:white;'>
                        THANKS ,<br> 
                        GROUP NO : 72<br> 
                        SDJ INTERNATIONAL COLLEGE<br><br><br>
                    </div>
                </div>";
                smtp_mailer($studentEmail, 'ISSUED BOOK SUCCESSFULLY', $mailHtml);
                echo "<script>alert('ISSUED BOOK SUCCESSFULLY');</script>";
                echo "<script type='text/javascript'> document.location ='manage-issued-books.php'; </script>";
            }else {
                echo "<script>alert('ISSUED BOOK FAILED , PLEASE TRY AGAIN');</script>";
                echo "<script type='text/javascript'> document.location ='manage-issued-books.php'; </script>";
            }
        } catch (Exception $e) {
            $dbh->rollBack(); // Rollback the transaction if an exception occurs
            echo "Error: " . $e->getMessage();
            exit(); // Add exit to stop script execution on error
        }
    }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
.others {
    color: red;
}
</style>
<link rel="icon" href="assets/svg/book-open-solid.svg" type="image/x-icon">
</head>

<body>
    <?php include('includes/header.php');?>
    <main>
        <h1 class="title">issued new book</h1>
        <ul class="breadcrumbs">
            <li><a href="dashboard.php">Home</a></li>
            <li class="divider">/</li>
            <li><a href="issue-book.php" class="active">issued new book</a></li>
        </ul>
        <div class="bigbox">
            <div class="box">
                <p>issued new book</p>
            </div><br>
            <form role="form" method="post">
                <?php
                    $sql = "SELECT * FROM tblstudents";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $students = $query->fetchAll(PDO::FETCH_OBJ);
                    if ($query->rowCount() > 0) {
                        echo '<div class="coolinput">';
                        echo '<label for="studentid" class="text">&nbsp;Student ID&nbsp;&nbsp;</label>';
                        echo '<select class="input" name="studentid" id="studentid" onchange="getStudentDetails()" required>';
                        echo '<option value="">Select Student ID</option>'; 
                        foreach ($students as $student) {
                            echo '<option value="' . htmlentities($student->StudentId) . '">' . htmlentities($student->FullName) . ' - ' . htmlentities($student->StudentId) . '</option>';
                        }
                        echo '</select>';
                        echo '</div>';
                    } else {
                        echo 'NO STUDENT RECORD FOUND';
                    }
                    ?>
                <div class="coolinput getdetail">
                    <span id="get_student_details" style="font-size:16px;"></span>
                </div><br>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                function getStudentDetails() {
                    $("#loaderIcon").show();
                    $.ajax({
                        url: "get_student.php",
                        data: {
                            studentid: $("#studentid").val()
                        },
                        type: "POST",
                        success: function(data) {
                            $("#get_student_details").html(data);
                            $("#loaderIcon").hide();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            // Handle error
                        }
                    });
                }
                </script>
                <?php
                    $sql_books = "SELECT * FROM tblbooks";
                    $query_books = $dbh->prepare($sql_books);
                    $query_books->execute();
                    $books = $query_books->fetchAll(PDO::FETCH_OBJ);
                    if ($query_books->rowCount() > 0) {
                        echo '<div class="coolinput">';
                        echo '<label for="bookid" class="text">&nbsp;Book to issue&nbsp;&nbsp;</label>';
                        echo '<select class="input" name="bookid" id="bookid" onchange="getbook()" required>';
                        echo '<option value="">Select Book to issue</option>';
                        foreach ($books as $book) {
                            echo '<option value="' . htmlentities($book->ISBNNumber) . '">' . htmlentities($book->BookName) . ' - ' . htmlentities($book->ISBNNumber) . '</option>';
                        }
                        echo '</select>';
                        echo '</div>';
                    } else {
                        echo 'NO BOOK RECORD FOUND';
                    }
                ?>
                <div class="coolinput" id="get_book_details">
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                function getbook() {
                    $("#loaderIcon").show();
                    $.ajax({
                        url: "get_book.php",
                        data: {
                            bookid: $("#bookid").val()
                        },
                        type: "POST",
                        success: function(data) {
                            $("#get_book_details").html(data);
                            $("#loaderIcon").hide();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            // Handle error
                        }
                    });
                }
                </script>
                <button type="submit" name="issue" id="submit" class="bttn">Issue Book </button>

            </form>
        </div>
    </main>
    <script src="assets/js/jquery-1.10.2.js"></script>
</body>

</html>
<?php } ?>
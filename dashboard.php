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
                <h4 class="header-line">User DASHBOARD</h4>

            </div>

        </div>
        <br>
        <hr><br>
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
        <div class="textdash">
            <h4>WELCOME
                ,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo htmlentities($result->FullName);?> <br></h4>
            <h4>your student id , &nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo htmlentities($result->StudentId);?></h4>


            <?php }}?>
        </div>
        <div class="row">
            <section class="main">

                <div class="main-skills">
                    <div class="card"><br>
                        <i class="ri-user-line"></i>
                        <h3>+</h3>
                        <p>account informaion</p>
                        <a href="my-profile.php"><button>SHOW</button></a>
                    </div>
                    <div class="card cddd">
                        <i class="fa fa-book fa-5x"></i>
                        <?php
                              $sql = "SELECT id from tblbooks ";
                              $query = $dbh->prepare($sql);
                              $query->execute();
                              $results = $query->fetchAll(PDO::FETCH_OBJ);
                              $listdbooks = $query->rowCount();
                            ?>
                        <h3><?php echo htmlentities($listdbooks); ?></h3>
                        <p>ALL BOOKS</p>
                        <a href="listed-books.php"><button>SHOW</button></a>
                    </div>

                    <div class="card">
                        <i class="fa-solid fa-eye"></i>
                        <?php
                              $rsts = 0;
                              $sid = $_SESSION['stdid'];
                              $sql2 = "SELECT id from tblissuedbookdetails where StudentID=:sid and (RetrunStatus=:rsts || RetrunStatus is null || RetrunStatus='')";
                              $query2 = $dbh->prepare($sql2);
                              $query2->bindParam(':sid', $sid, PDO::PARAM_STR);
                              $query2->bindParam(':rsts', $rsts, PDO::PARAM_STR);
                              $query2->execute();
                              $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
                              $returnedbooks = $query2->rowCount();
                            ?>
                        <h3><?php echo htmlentities($returnedbooks); ?></h3>
                        <p>books not return</p>
                        <a href="issued-books.php"><button>SHOW</button></a>
                    </div>
                    <div class="card">
                        <i class="fa-solid fa-message"></i>
                        <?php
                              $sql = "SELECT id from tblmessage ";
                              $query = $dbh->prepare($sql);
                              $query->execute();
                              $results = $query->fetchAll(PDO::FETCH_OBJ);
                              $isss = $query->rowCount();
                            ?>
                        <h3><?php echo htmlentities($isss); ?></h3>
                        <p>messages</p>
                        <a href="messages.php"><button>SHOW</button></a>
                    </div>
                </div>
            </section>
            </body>

</html>
<?php }?>
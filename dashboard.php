<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
  header('location:index.php');
} else { ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('includes/header.php'); ?>
<main>
    <h1 class="title">Dashboard</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
    </ul>
    <div class="info-data">
        <a href="manage-books.php">
            <div class="card">
                <div class="head">
                    <div>
                        <?php
                        $sql = "SELECT id from tblbooks ";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $listdbooks = $query->rowCount();
                        ?>
                        <p class="dicon"><i class="fa-solid fa-book"></i></p>
                        <h2>ALL BOOKS</h2>
                        <p><?php echo htmlentities($listdbooks); ?></p>
                    </div>
                </div>
            </div>
        </a>
        <a href="manage-issued-books.php">
            <div class="card">
                <div class="head">
                    <div>
                        <?php
                $sql2 = "SELECT id from tblissuedbookdetails where (RetrunStatus='' || RetrunStatus is null)";
                $query2 = $dbh->prepare($sql2);
                $query2->execute();
                $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
                $returnedbooks = $query2->rowCount();
                ?>
                        <p class="dicon"><i class="fa-solid fa-eye"></i></p>
                        <h2> BOOKS NOT RETURN</h2>
                        <p><?php echo htmlentities($returnedbooks); ?></p>
                    </div>
                </div>
            </div>
        </a><a href="manage-authors.php">
            <div class="card">
                <div class="head">
                    <div>
                        <?php
                $sq4 = "SELECT id from tblauthors ";
                $query4 = $dbh->prepare($sq4);
                $query4->execute();
                $results4 = $query4->fetchAll(PDO::FETCH_OBJ);
                $listdathrs = $query4->rowCount();
                ?>
                        <p class="dicon"><i class="fa-solid fa-user-secret"></i></p>
                        <h2>ALL AUTHORS</h2>
                        <p><?php echo htmlentities($listdathrs); ?></p>
                    </div>
                </div>
            </div>
        </a><a href="reg-students.php">
            <div class="card">
                <div class="head">
                    <div>
                        <?php
                $sql3 = "SELECT id from tblstudents ";
                $query3 = $dbh->prepare($sql3);
                $query3->execute();
                $results3 = $query3->fetchAll(PDO::FETCH_OBJ);
                $regstds = $query3->rowCount();
                ?>
                        <p class="dicon"><i class="fa-solid fa-graduation-cap"></i></p>
                        <h2>ALL students</h2>
                        <p><?php echo htmlentities($regstds); ?></p>
                    </div>
                </div>
            </div>
        </a><a href="manage-categories.php">
            <div class="card">
                <div class="head">
                    <div>
                        <?php
                        $sql = "SELECT id from tblcategory";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $allcat = $query->rowCount();
                        ?>
                        <p class="dicon"><i class='bx bxs-analyse'></i></p>
                        <h2>all categories</h2>
                        <p><?php echo htmlentities($allcat); ?></p>
                    </div>
                </div>
            </div>
        </a><a href="manage-publishers.php">
            <div class="card">
                <div class="head">
                    <div>
                        <?php
                        $sql = "SELECT id from tblpublisher";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $lpublisher = $query->rowCount();
                        ?>
                        <p class="dicon"><i class="fa-solid fa-users"></i></p>
                        <h2>ALL publishers</h2>
                        <p><?php echo htmlentities($lpublisher); ?></p>
                    </div>
                </div>
            </div>
        </a><a href="manage-messages.php">
            <div class="card">
                <div class="head">
                    <div>
                        <?php
                        $sql = "SELECT id from tblmessage ";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $message = $query->rowCount();
                        ?>
                        <p class="dicon"><i class='bx bxs-message-square-dots'></i></p>
                        <h2>ALL messages</h2>
                        <p><?php echo htmlentities($message); ?></p>
                    </div>
                </div>
            </div>
        </a><a href="my-profile.php">
            <div class="card">
                <div class="head">
                    <div>
                        <p class="dicon"><i class='bx bx-user'></i></p>
                        <h2>account information</h2>
                <p>+</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
</main>
</body>

</html>
<?php } ?>
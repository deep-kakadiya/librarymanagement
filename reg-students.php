<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

// code for block student    
if(isset($_GET['inid']))
{
$id=$_GET['inid'];
$status=0;
$sql = "update tblstudents set Status=:status  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query -> execute();
echo "<script>alert('STUDENT BLOCKED SUCCESSFULLY');</script>";
echo "<script>window.location.href = 'reg-students.php'</script>";
}



//code for active students
if(isset($_GET['id']))
{
$id=$_GET['id'];
$status=1;
$sql = "update tblstudents set Status=:status  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query -> execute();
echo "<script>alert('STUDENT ACTIVATE SUCCESSFULLY');</script>";
echo "<script>window.location.href = 'reg-students.php'</script>";
}


    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include('includes/header.php');?>
<main>
    <h1 class="title">manage student</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="reg-students.php" class="active">manage student</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>manage student</p>
        </div><br>
        <div style="margin-bottom: 20px; margin-left: 11px;">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; SEARCH BOOK&nbsp;&nbsp;</label>
                <input class="input" id="searchInput" type="text" placeholder="SEARCH BY STUDENT NAME..."
                    autocomplete="off" onkeyup="filterTable()" />
            </div>
        </div>
        <table id="myTable" class="table">
            <thead class="table-dark">
                <tr>
                    <th>no</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Email id </th>
                    <th>Mobile Number</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php $sql = "SELECT * from tblstudents";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>
                <tr class="odd gradeX" style="font-size: 13px;">
                    <td class="center">&nbsp;&nbsp;<?php echo htmlentities($cnt);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->StudentId);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->FullName);?></td>
                    <td class="center"><?php echo htmlentities($result->EmailId);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->MobileNumber);?></td>
                    <td class="center">
                        <?php if($result->Status==1)
                        {
                            echo '<span style="color:green">' . htmlentities("Active") . '</span>';
                        } else {
                            echo '<span style="color:red">' . htmlentities("InActive") . '</span>';
                        }
                        ?></td>
                    <td class="center">
                        <?php if($result->Status==1)
 {?>
                        <a href="reg-students.php?inid=<?php echo htmlentities($result->id);?>"
                            onclick="return confirm('ARE YOU SURE TO BLOCK THIS STUDENT ?');">
                            <button class="bttn"style="font-size: 13px;"><p style="font-size: 15px;">&#10007;</p>  Inactive</button>
                            <?php } else {?>

                            <a href="reg-students.php?id=<?php echo htmlentities($result->id);?>"
                                onclick="return confirm('ARE YOU SURE TO ACTIVE THIS STUDENT ?');"><button
                                    class="bttn" style="font-size: 13px;"><p style="font-size: 15px;">&#10003;</p>  &nbsp;&nbsp;Active&nbsp;&nbsp;</button>
                                <?php } ?>

                                <a href="student-history.php?stdid=<?php echo htmlentities($result->StudentId);?>"><button
                                        class="bttn"style="font-size: 13px;"><p style="font-size: 15px;"><i class='bx bx-info-circle'></i></p> Details</button>


                    </td>
                </tr>
                <?php $cnt=$cnt+1;}} ?>
            </tbody>
        </table>
    </div>
</main>
<script>
function filterTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[2]; // Change the index to match the column you want to search
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>
</body>

</html>
<?php } ?>
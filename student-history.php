<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include('includes/header.php');?>
<main>
    <h1 class="title">student history</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="student-history.php" class="active">student history</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <?php $sid=$_GET['stdid']; ?>
            <p>student history</p>
        </div><br>
        <?php 
                    $siid=$_GET['stdid'];
                    $sql="SELECT *  from  tblstudents  where StudentId=:siid ";
                    $query = $dbh -> prepare($sql);
                    $query-> bindParam(':siid', $siid, PDO::PARAM_STR);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                    foreach($results as $result)
                    {               
                ?>
        <p class="texts">Student ID
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            : <?php echo htmlentities($result->StudentId);?> <br>
            full name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?php echo htmlentities($result->FullName);?> <br>
            mobile number &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo htmlentities($result->MobileNumber);?> <br>
            Email Id  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo htmlentities($result->EmailId);?> <br>
            Register Date
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
            <?php echo htmlentities($result->RegDate);?> <br>
            Last Updation Date &nbsp;&nbsp;&nbsp;&nbsp;: <?php echo htmlentities($result->UpdationDate);?>
            <br>
            Profile Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
            <?php if($result->Status==1){?>
            <span style="color: green">Active</span>
            <?php } else { ?>
            <span style="color: red">Blocked</span>
            <?php }?>
        </p><br><?php }}?>
        <div style="margin-bottom: 20px; margin-left: 11px;">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; SEARCH ISSUED BOOK&nbsp;&nbsp;</label>
                <input class="input" id="searchInput" type="text" placeholder="SEARCH BY BOOK NAME..."
                    autocomplete="off" onkeyup="filterTable()" />
            </div>
        </div>
        <table id="myTable" class="table">
            <thead class="table-dark">
                <tr>
                    <th>NO</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Issued Book </th>
                    <th>Issued Date</th>
                    <th>Returned Date</th>
                    <th>Fine (if any)</th>

                </tr>
            </thead>

            <tbody>
                <?php 

$sql = "SELECT tblstudents.StudentId ,tblstudents.FullName,tblstudents.EmailId,tblstudents.MobileNumber,tblbooks.BookName,tblbooks.ISBNNumber,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.ReturnDate,tblissuedbookdetails.id as rid,tblissuedbookdetails.fine,tblissuedbookdetails.RetrunStatus,tblbooks.id as bid,tblbooks.bookImage from  tblissuedbookdetails join tblstudents on tblstudents.StudentId=tblissuedbookdetails.StudentId join tblbooks on tblbooks.id=tblissuedbookdetails.BookId where tblstudents.StudentId='$sid' ";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>
                <tr class="odd gradeX">
                    <td class="center">&nbsp;&nbsp;<?php echo htmlentities($cnt);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->StudentId);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->FullName);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->BookName);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->IssuesDate);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($result->ReturnDate==''): echo '<span style="color:red;">Not returned yet</span>';
                                            else: echo htmlentities($result->ReturnDate); endif;?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($result->ReturnDate==''): echo '<span style="color:red;">Not returned yet</span>';
                                              else: echo $result->fine; endif;
                                             ?></td>


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
        td = tr[i].getElementsByTagName("td")[3]; // Change the index to match the column you want to search
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
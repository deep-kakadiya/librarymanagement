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
    <h1 class="title">manage issued book</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="manage-issued-book.php" class="active">manage issued book</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>manage issued book</p>
        </div><br>
        <div style="margin-bottom: 20px; margin-left: 11px;">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; SEARCH student&nbsp;&nbsp;</label>
                <input class="input" id="searchInput" type="text" placeholder="SEARCH BY STUDENT NAME..."
                    autocomplete="off" onkeyup="filterTable()" />
            </div>
        </div>
        <table id="myTable" class="table">
            <thead class="table-dark">
                <tr>
                    <th>no</th>
                    <th>Student Name</th>
                    <th>Book Name</th>
                    <th>ISBN no</th>
                    <th>Issued Date</th>
                    <th>last update date</th>
                    <th>fine</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $sql = "SELECT tblissuedbookdetails.fine,tblstudents.FullName,tblbooks.BookName,tblbooks.ISBNNumber,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.ReturnDate,tblissuedbookdetails.RetrunStatus,tblissuedbookdetails.id as rid from  tblissuedbookdetails join tblstudents on tblstudents.StudentId=tblissuedbookdetails.StudentId join tblbooks on tblbooks.id=tblissuedbookdetails.BookId order by tblissuedbookdetails.id desc";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>
                <tr class="odd gradeX">
                    <td class="center"><?php echo htmlentities($cnt);?></td>
                    <td class="center"><?php echo htmlentities($result->FullName);?>
                    </td>
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->BookName);?>
                    </td>
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->ISBNNumber);?></td>
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->IssuesDate);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;<?php if($result->ReturnDate=="")
                                            {
                                                echo '<span style="color: red;">' . htmlentities("NOT RETURN YET") . '</span>';
                                            } else {


                                            echo htmlentities($result->ReturnDate);
}
                                            ?></td>
                    <td class="center">&nbsp;&nbsp;<?php echo htmlentities($result->fine);?>
                    </td>
                    <td class="center">
                        <?php if($result->RetrunStatus==0){?>
                        <a href="update-issue-bookdeails.php?rid=<?php echo htmlentities($result->rid);?>"><button
                                class="bttn">&nbsp;&nbsp;&nbsp;<i class="fa fa-edit "></i> Edit&nbsp;&nbsp;&nbsp;</button>
                            <?php }else{?>
                            <a href="generate_receipt.php?pid=<?php echo htmlentities($result->rid); ?>" class="bttn"
                                download><i class="fa-solid fa-download"></i> Receipt</a>
                            <?php }?>
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
        td = tr[i].getElementsByTagName("td")[1]; // Change the index to match the column you want to search
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
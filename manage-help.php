<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{
if(isset($_GET['del']))
{
$id=$_GET['del'];
$sql = "delete from tblhelp  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> execute();
echo "<script>alert('QUESTION FOR HELP DELETED SUCCESSFULLY');</script>";
echo "<script>window.location.href = 'manage-help.php'</script>";
}


    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include('includes/header.php');?>
<main>
    <h1 class="title">manage help question</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="manage-authors.php" class="active">manage help question</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>manage help question</p>
        </div><br>
        <div style="margin-bottom: 20px; margin-left: 11px;">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; SEARCH BY NAME&nbsp;&nbsp;</label>
                <input class="input" id="searchInput" type="text" placeholder="SEARCH BY STUDENT NAME..."
                    autocomplete="off" onkeyup="filterTable()" />
            </div>
        </div>
        <table id="myTable" class="table">
            <thead class="table-dark">
                <tr>
                    <th>no</th>
                    <th>STUDENT NAME</th>
                    <th>MOBILE NUMBER</th>  
                    <th>email address</th>
                    <th>Question</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php $sql = "SELECT * from  tblhelp";
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
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->studentname);?>
                    </td>
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->mobilenumber);?></td>
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->emailaddress);?></td>
                        <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->question);?></td>
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="manage-help.php?del=<?php echo htmlentities($result->id);?>"
                                onclick="return confirm('ARE YOU SURE TO WANT DELETE A QUESTION OF HELP ?');"" >  <button class="
                                bttn"><i class="fa-solid fa-trash"></i> Delete</button>
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
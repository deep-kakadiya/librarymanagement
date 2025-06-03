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
$sql = "delete from tblpublisher  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> execute();
echo "<script>alert('PUBLISHER DELETED SUCCESSFULLY');</script>";
echo "<script>window.location.href = 'manage-publishers.php'</script>";
}


    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include('includes/header.php');?>
<main>
    <h1 class="title">manage PUBLISHER</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="manage-authors.php" class="active">manage PUBLISHER</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>manage PUBLISHER</p>
        </div><br>
        <div style="margin-bottom: 20px; margin-left: 11px;">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; SEARCH PUBLISHER&nbsp;&nbsp;</label>
                <input class="input" id="searchInput" type="text" placeholder="SEARCH BY PUBLISHER NAME..."
                    autocomplete="off" onkeyup="filterTable()" />
            </div>
        </div>
        <table id="myTable" class="table">
            <thead class="table-dark">
                <tr>
                    <th>no</th>
                    <th>PUBLISHER name</th>
                    <th>Creation Date</th>
                    <th>Updation Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php $sql = "SELECT * from  tblpublisher";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>
                <tr class="odd gradeX">
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($cnt);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->PublisherName);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->creationDate);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->UpdationDate);?></td>
                    <td class="center">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="edit-publisher.php?pubid=<?php echo htmlentities($result->id);?>"><button
                                class="bttn"><i class="fa fa-edit "></i>
                                Edit</button>
                            <a href="manage-publishers.php?del=<?php echo htmlentities($result->id);?>"
                                onclick="return confirm('ARE YOU SURE TO WANT DELETE A PUBLISHER ?');"" >  <button class=" bttn"><i class="fa-solid fa-trash"></i> Delete</button>
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
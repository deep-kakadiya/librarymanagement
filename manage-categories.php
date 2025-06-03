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
$sql = "delete from tblcategory  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> execute();
echo "<script>alert('CATEGORY DELETED SUCCESSFULLY');</script>";
echo "<script>window.location.href = 'manage-categories.php'</script>";
}


    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<?php include('includes/header.php');?>
<main>
    <h1 class="title">manage category</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="manage-categories.php" class="active">manage category</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>manage category</p>
        </div><br>
        <div style="margin-bottom: 20px; margin-left: 11px;">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; SEARCH CATEGORY&nbsp;&nbsp;</label>
                <input class="input" id="searchInput" type="text" placeholder="SEARCH BY CATEGORY NAME..."
                    autocomplete="off" onkeyup="filterTable()" />
            </div>
        </div>
        <table id="myTable" class="table">
            <thead class="table-dark">
                <tr>
                    <th>no</th>
                    <th>Category Name</th>
                    <th>STATUS</th>
                    <th>CREATION DATE </th>
                    <th>UPDATION DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>

            <tbody>
                <?php $sql = "SELECT * from  tblcategory";
                                    $query = $dbh -> prepare($sql);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt=1;
                                    if($query->rowCount() > 0)
                                    {
                                    foreach($results as $result)
                                    {               ?>
                <tr class="odd gradeX">
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($cnt);?></td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->CategoryName);?>
                    </td>
                    <td class="center">&nbsp;&nbsp;&nbsp;<?php if($result->Status==1) {?>
                        <a href="#" class="greenactive">Active</a>
                        <?php } else {?>
                        <a href="#" class="redinactive">Inactive</a>
                        <?php } ?>
                    </td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->CreationDate);?>
                    </td>
                    <td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->UpdationDate);?>
                    </td>
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="edit-category.php?catid=<?php echo htmlentities($result->id);?>"><button
                                class="bttn"><i class="fa fa-edit "></i>
                                Edit</button>
                            <a href="manage-categories.php?del=<?php echo htmlentities($result->id);?>"
                                onclick="return confirm('ARE YOU SURE TO WANT DELETE A CATEGORY ?');"" >  <button class="
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
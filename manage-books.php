<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else{ 
    if(isset($_GET['del']))
    {
    $id=$_GET['del'];
    $sql = "delete from tblbooks  WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query -> bindParam(':id',$id, PDO::PARAM_STR);
    $query -> execute();
    echo "<script>alert('BOOK DELETED SUCCESSFULLY');</script>";
    echo "<script>window.location.href = 'manage-books.php'</script>";
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include('includes/header.php'); ?>
<main>
    <h1 class="title">manage book</h1>
    <ul class="breadcrumbs">
        <li><a href="dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="manage-book.php" class="active">manage book</a></li>
    </ul>
    <div class="bigbox">
        <div class="box">
            <p>manage book</p>
        </div><br>
        <div style="margin-bottom: 20px; margin-left: 11px;">
            <div class="coolinput">
                <label for="input" class="text">&nbsp; SEARCH BOOK&nbsp;&nbsp;</label>
                <input class="input" id="searchInput" type="text" placeholder="SEARCH BY BOOK NAME..."
                    autocomplete="off" onkeyup="filterTable()" />
            </div>
        </div>
        <table id="myTable" class="table" id="tableExport">
            <thead class="table-dark">
                <tr>
                    <th>no</th>
                    <th>Book Name</th>
                    <th>Category id</th>
                    <th>Author id</th>
                    <th>publisher id</th>
                    <th>ISBN</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php $sql = "SELECT * from tblbooks";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) {               
                ?>
                <tr class="odd gradeX">
                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                    <td class="center1" width="150">
                        <img src="bookimg/<?php echo htmlentities($result->bookImage); ?>" width="100">
                        <br /><b><?php echo htmlentities($result->BookName); ?></b>
                    </td>
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->CatId); ?>
                    </td>
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->AuthorId); ?>
                    </td>
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->PubId); ?>
                    </td>
                    <td class="center">&nbsp;&nbsp;<?php echo htmlentities($result->ISBNNumber); ?></td>
                    <td class="center">&nbsp;&nbsp;<?php echo htmlentities($result->BookPrice); ?></td>
                    <td class="center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="edit-book.php?bookid=<?php echo htmlentities($result->id);?>"><button class="bttn"><i
                                    class="fa-solid fa-pen-to-square"></i>
                                EDIT</button>
                            <a href="manage-books.php?del=<?php echo htmlentities($result->id);?>"
                                onclick="return confirm('ARE YOU SURE TO WANT DELETE A BOOK ?');"" >  <button class="
                                bttn"><i class="fa-solid fa-trash"></i> DELETE</button>
                    </td>
                </tr>
                <?php $cnt = $cnt + 1;
                }
                } ?>
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
<script>
$(window).on("load", function() {
    $(".loader").fadeOut("slow");
});
</script>
<script>
// JavaScript to hide loader when page is loaded
window.addEventListener("load", function() {
    var loader = document.querySelector(".loader");
    loader.style.display = "none";
});
</script>
</body>

</html>
<?php } ?>
<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
    {   
header('location:index.php');
}
else{ 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <style>
            tbody tr {
                border-bottom: 2px solid #343a40;
            }
            .table-dark {
                background-color: var(--black-color) !important; 
            }
           
        </style>
    </head>
<?php include('includes/header.php');?>
<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Manage Issued Books</h4>
            </div>
            <br>
            <hr><br><br>
            <main class="py-4">

                <div class="container">

                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    <div style="margin-bottom: 20px;">
                                        <div class="coolinput">
                                            <label for="input" class="text">&nbsp; SEARCH BY BOOK NAME &nbsp;&nbsp;</label>
                                            <input class="input" id="searchInput" type="text" placeholder="SEARCH BY BOOK NAME..."
                                                autocomplete="off"  onkeyup="filterTable()" />
                                        </div>
                                    </div>
                                    <span style="color:red"><b> WARNING : </b> TIME LIMIT OF RETURN A BOOK IS MINIMUM 2 DAYS , AFTER 2 DAYS WE collect FINE 20 RUPPES PER DAY.</span>
                                    <br><br><table id="myTable" class="table">
                                        <thead class="table-dark">
                                            <tr>
                                                <th> no </th>
                                                <th> book name </th>
                                                <th> isbn number </th>
                                                <th> issued date</th>
                                                <th> return date </th>
                                                <th> fine (₹) </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php 
                            $sid=$_SESSION['stdid'];
                            $sql="SELECT tblbooks.BookName,tblbooks.ISBNNumber,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.ReturnDate,tblissuedbookdetails.id as rid,tblissuedbookdetails.fine from  tblissuedbookdetails join tblstudents on tblstudents.StudentId=tblissuedbookdetails.StudentId join tblbooks on tblbooks.id=tblissuedbookdetails.BookId where tblstudents.StudentId=:sid order by tblissuedbookdetails.id desc";
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
                                            <tr class="odd gradeX">
                                                <td class="center">&nbsp;&nbsp;&nbsp;<?php echo htmlentities($cnt);?>
                                                </td>
                                                <td class="center">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->BookName);?>
                                                </td>
                                                <td class="center">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->ISBNNumber);?>
                                                </td>
                                                <td class="center">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->IssuesDate);?>
                                                </td>
                                                <td class="center"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($result->ReturnDate=="")
                                            {?>
                                                    <span style="color:red">
                                                        <?php   echo htmlentities("NOT RETURN YET"); ?>
                                                    </span>
                                                    <?php } else {
                                            echo htmlentities($result->ReturnDate);
                                        }
                                            ?>
                                                </td>
                                                <td class="center">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlentities($result->fine);?>
                                                </td>

                                            </tr>
                                            <?php $cnt=$cnt+1;}} ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>


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
        </div>
    </div>
</div>
</body>

</html>
<?php } ?>
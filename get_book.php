<?php 
require_once("includes/config.php");
if(!empty($_POST["bookid"])) {
  $bookid=$_POST["bookid"];
 
    $sql ="SELECT distinct tblbooks.BookName,tblbooks.id,tblauthors.AuthorName,tblbooks.bookImage,tblbooks.isIssued FROM tblbooks
join tblauthors on tblauthors.id=tblbooks.AuthorId
     WHERE (ISBNNumber=:bookid || BookName like '%$bookid%')";
$query= $dbh -> prepare($sql);
$query-> bindParam(':bookid', $bookid, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query -> rowCount() > 0){
?>
<br>
<div class="coolinput">
    <label for="input" class="text">&nbsp; book details &nbsp;&nbsp;</label>
    <div class="input">
        <?php foreach ($results as $result) {?>
        <img src="bookimg/<?php echo htmlentities($result->bookImage); ?>" width="120" height="200"><br />
        <span class="book-name"><?php echo htmlentities($result->BookName); ?></span><br />
        <span class="author-name"><?php echo htmlentities($result->AuthorName); ?></span><br />
        <?php if($result->isIssued=='1'): ?>
        <p class="issued-message">BOOK ALREADY ISSUED</p>
        <?php else:?><br>
        <div class="checkbox-wrapper">
            <input id="cbtest-19" type="checkbox" name="bookid" value="<?php echo htmlentities($result->id); ?>"
                required>
            <label class="check-box" for="cbtest-19">
            </label>
        </div>
        <?php endif;?>
        <?php }?>
    </div>
</div>

<?php  
}else{?>
<p style='color:red'>RECORD NOT FOUND PLEASE TRY AGAIN.</p>
<?php
 echo "<script>$('#submit').prop('disabled',true);</script>";
}
}
?>
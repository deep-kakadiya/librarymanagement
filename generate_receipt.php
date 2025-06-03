<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
  header('location:index.php');
} else { 
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>LIBRARY MANAGEMENT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.0/css/all.min.css"
        integrity="sha512-gRH0EcIcYBFkQTnbpO8k0WlsD20x5VzjhOA1Og8+ZUAhcMUCvd+APD35FJw3GzHAP3e+mP28YcDJxVr745loHw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
    <link rel="icon" type="image/svg+xml" href="assets/svg/book-open-solid.svg">
    <style>
    body {
        font-family: 'Open Sans', sans-serif;
        margin: 0;
        padding: 0;
        text-transform: uppercase;
    }

    .container {
        width: 800px;
        margin: 20px auto;
        padding: 20px;
        border: 2px solid black;
    }

    header h1 {
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }

    header h2 {
        font-size: 18px;
        font-weight: bold;
        margin: 10px 725px;
    }

    .content {
        margin-top: 20px;
    }

    .billing-details,
    .invoice-details {
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        border: 2px solid black;
        padding: 8px;
        font-weight: bold;
    }

    .total {
        text-align: right;
        margin-top: 20px;
    }

    .amount-in-words {
        margin-top: 20px;
    }

    footer {
        margin-top: 20px;
        text-align: center;
    }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1 style="text-align: center;">
                <i class="fa-solid fa-book-open" style="font-size: 30px;"></i>
                library management
            </h1>

            <p
                style="margin: 0; padding: 0; font-weight: bold; font-family: 'Open Sans', sans-serif; margin-top: 40px;">
                GROUP NO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 72</p>
            <p style="margin: 0; padding: 0; font-weight: bold; margin-top: 5px;">PHONE NO
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 9228232989</p>
            <p style="margin: 0; padding: 0; font-weight: bold; margin-top: 5px;">SDJ INTERNATIONAL COLLEGE</p>

            <h2>INVOICE</h2>
        </header>
        <hr>
        <div style="height: 3px; background-color: black;"></div>
        <div class="content">
            <div class="billing-details">
                <?php
                $currentDate = date("Y-m-d");
        $pid = intval($_GET['pid']);
        $sql = "SELECT tblstudents.StudentId, tblstudents.FullName, tblstudents.EmailId, tblstudents.MobileNumber, tblbooks.BookName, tblbooks.ISBNNumber, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.FineDate, tblissuedbookdetails.ReturnDate, tblissuedbookdetails.id as rid, tblissuedbookdetails.fine, tblissuedbookdetails.RetrunStatus, tblbooks.id as bid, tblbooks.bookImage FROM tblissuedbookdetails JOIN tblstudents ON tblstudents.StudentId=tblissuedbookdetails.StudentId JOIN tblbooks ON tblbooks.id=tblissuedbookdetails.BookId WHERE tblissuedbookdetails.id=:pid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':pid', $pid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $cnt = 1;
        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                ?>
                <h3>Bill To</h3>
                <b>
                    <p>student id&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;
                        <?php echo htmlentities($result->StudentId); ?> </p>
                    <p>student Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;
                        <?php echo htmlentities($result->FullName); ?></p>
                    <p>phone
                        no&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        :&nbsp;&nbsp; <?php echo htmlentities($result->MobileNumber); ?></p>
                    <p>email address&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;
                        <span style='text-transform: lowercase;'><?php echo htmlentities(strtolower($result->EmailId)); ?></span></p>

                </b>
            </div>
            <div class="invoice-details">
                <b>
                    <p>Invoice No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;
                        <span id="invoiceno"></span>
                    </p>
                    <p>Invoice Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;
                        <?php echo $currentDate; ?>
                    </p>
                </b>
                <script>
                window.onload = function() {
                    generateInvoiceNo();
                };

                function generateInvoiceNo() {
                    var invoiceno = generateRandomInvoiceNo();
                    document.getElementById('invoiceno').textContent = invoiceno;
                }

                function generateRandomInvoiceNo() {
                    var length = 5; // Length of the Invoice No
                    var characters =
                        'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; // Allowed characters for Invoice No (letters and numbers)
                    var invoiceno = '';

                    for (var i = 0; i < length; i++) {
                        var randomIndex = Math.floor(Math.random() * characters.length);
                        invoiceno += characters.charAt(randomIndex);
                    }

                    return invoiceno;
                }
                </script>


            </div><br><br>
            <table border="3">
                <thead>
                    <tr>
                        <th>book name</th>
                        <th>issued date</th>
                        <th>return date</th>
                        <th>fine</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    <tr>
                        <td><?php echo htmlentities($result->BookName); ?></p></td>
                        <td><?php echo htmlentities($result->IssuesDate); ?></p></td>
                        <td><?php echo htmlentities($result->ReturnDate); ?></p></td>
                        <td><?php echo htmlentities($result->fine); ?></p></td>
                    </tr>
                </tbody>
            </table>
            <?php }
        } ?>
            <footer>
                <b style="text-align: right;">
                    <p>for , library management</p>
                    <p>Authorized Signature</p>
                </b>

                <br><br><br>
                <strong>This is a computer generated invoice</strong>

            </footer>
        </div>
</body>

</html>
<?php } ?>
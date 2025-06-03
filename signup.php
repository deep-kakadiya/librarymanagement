<?php
session_start();
include('includes/config.php');
error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
if (isset($_POST['signup'])) {
    $email = $_POST['email'];

    // Check if the email is already registered
    $checkEmailSql = "SELECT COUNT(*) as count FROM tblstudents WHERE EmailId = :email";
    $checkEmailQuery = $dbh->prepare($checkEmailSql);
    $checkEmailQuery->bindParam(':email', $email, PDO::PARAM_STR);
    $checkEmailQuery->execute();
    $emailCount = $checkEmailQuery->fetch(PDO::FETCH_ASSOC)['count'];

    if ($emailCount > 0) {
        // Account already exists, show a popup or handle accordingly
        echo "<script>alert('ACCOUNT ALREADY EXISTS WITH THIS EMAIL');</script>";
    } else {
        // Code for student ID
        $count_my_page = ("studentid.txt");
        $hits = file($count_my_page);
        $hits[0]++;
        $fp = fopen($count_my_page, "w");
        fputs($fp, "$hits[0]");
        fclose($fp);
        $StudentId = $hits[0];
        $fname = $_POST['fullanme'];
        $mobileno = $_POST['mobileno'];
        $password = md5($_POST['password']);
        $otp = rand(111111, 999999);
        $status = 0;

        $insertSql = "INSERT INTO tblstudents(StudentId, FullName, MobileNumber, EmailId, Password, Status, otp) VALUES(:StudentId, :fname, :mobileno, :email, :password, :status, :otp)";
        $insertQuery = $dbh->prepare($insertSql);
        $insertQuery->bindParam(':StudentId', $StudentId, PDO::PARAM_STR);
        $insertQuery->bindParam(':fname', $fname, PDO::PARAM_STR);
        $insertQuery->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
        $insertQuery->bindParam(':email', $email, PDO::PARAM_STR);
        $insertQuery->bindParam(':password', $password, PDO::PARAM_STR); // Bind plain text password
        $insertQuery->bindParam(':status', $status, PDO::PARAM_STR);
        $insertQuery->bindParam(':otp', $otp, PDO::PARAM_STR);
        $insertQuery->execute();

        $lastInsertId = $dbh->lastInsertId();
        $fname = strtoupper($fname);
        if ($lastInsertId) {
            $msg = "";
            $mailHtml = "<br><div style='background-color: black; border-radius: 10px; color:white; padding: 10px; display: inline-block;'>
                HEY , <b style='color:red; font-weight:550;'>$fname</b><br><br> 
                <p color:white;>WE HAVE RECEIVED YOUR REQUEST FOR SIGN-UP CODE TO USE LIBRARY MANAGEMENT ACCOUNT.</p>
                <div style='text-align: center;'>
                <p color: white;><b>YOUR OTP :</b> <b style='color: red; font-size: 18px; font-weight: 550;'>$otp</b></p>
                </div>
                <p color:white;>IF YOU DIDN'T REQUEST THIS CODE, YOU CAN SAFELY IGNORE THIS EMAIL. SOMEONE ELSE MIGHT HAVE TYPED YOUR EMAIL ADDRESS BY MISTAKE.</p>
                <br><br> <p color:white;>
                THANKS ,<br> 
                GROUP NO : 72<br>
                TEAM LEADER : DEEP KAKADIYA ( DK )<br>
                SDJ INTERNATIONAL COLLEGE<br><br><br></p>
            </div>";
            smtp_mailer($email, 'ACCOUNT VERIFICATION', $mailHtml);
            echo "<script type='text/javascript'> document.location ='verify.php'; </script>";
        } else {
            echo "<script>alert('SOMETHING WENT WRONG');</script>";
        }
    }
}

function smtp_mailer($to,$subject, $msg){
	require 'phpmailer/src/Exception.php'; 
 require 'phpmailer/src/PHPMailer.php'; 
 require 'phpmailer/src/SMTP.php';
	$mail = new PHPMailer(); 
	$mail->IsSMTP(); 
	$mail->SMTPDebug = 1; 
	$mail->SMTPAuth = true; 
	$mail->SMTPSecure = 'TLS'; 
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587; 
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	$mail->Username = "";
	$mail->Password = "";
	$mail->SetFrom("deepkakadiya2021@gmail.com","LIBRARY MANAGEMENT");
	$mail->Subject = $subject;
	$mail->Body =$msg;
	$mail->AddAddress($to);
	if(!$mail->Send()){
		return 0;
	}else{
		return 1;
	}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>LIBRARY MANAGEMENT SYSTEM</title>
    <link rel="icon" href="assets/svg/book-open-solid.svg" type="image/x-icon">
    <link href="assets/css/font-awesome.css" rel="stylesheet" />

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script>
    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'emailid=' + $("#emailid").val(),
            type: "POST",
            success: function(data) {
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }
    </script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Montserrat', sans-serif;
    }

    body {
        background-color: #c9d6ff;
        background: linear-gradient(to right, #e2e2e2, #c9d6ff);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        height: 100vh;
        text-transform: uppercase;
    }


    .container {
        background-color: #fff;
        border-radius: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
        position: relative;
        overflow: hidden;
        width: 768px;
        max-width: 100%;
        min-height: 480px;
    }

    .container p {
        font-size: 14px;
        line-height: 20px;
        letter-spacing: 0.3px;
        margin: 20px 0;
    }

    .container span {
        font-size: 12px;
        font-weight: 500;
    }

    .container a {
        color: #fff;
        font-size: 13px;
        text-decoration: none;
        margin: 15px 0 10px;
    }

    .container button {
        background-color: #512da8;
        color: #fff;
        font-size: 12px;
        padding: 10px 45px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-top: 10px;
        cursor: pointer;
    }

    .container button.hidden {
        background-color: transparent;
        border: 2px solid #fff;
    }

    .container form {
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 40px;
        height: 100%;
    }

    .container input {
        background-color: #eee;
        border: none;
        margin: 8px 0;
        padding: 10px 15px;
        font-size: 13px;
        border-radius: 8px;
        width: 100%;
        outline: none;
    }

    .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.6s ease-in-out;
    }

    .sign-in {
        left: 0;
        width: 50%;
        z-index: 2;
    }

    .container.active .sign-in {
        transform: translateX(100%);
    }

    .sign-up {
        left: 0;
        width: 50%;
        opacity: 0;
        z-index: 1;
    }

    .container.active .sign-up {
        transform: translateX(100%);
        opacity: 1;
        z-index: 5;
        animation: move 0.6s;
    }

    @keyframes move {

        0%,
        49.99% {
            opacity: 0;
            z-index: 1;
        }

        50%,
        100% {
            opacity: 1;
            z-index: 5;
        }
    }

    .social-icons {
        margin: 20px 0;
    }

    .social-icons a {
        border: 1px solid #ccc;
        border-radius: 20%;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        margin: 0 3px;
        width: 40px;
        height: 40px;
    }

    .toggle-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: all 0.6s ease-in-out;
        border-radius: 150px 0 0 100px;
        z-index: 1000;
    }

    .container.active .toggle-container {
        transform: translateX(-100%);
        border-radius: 0 150px 100px 0;
    }

    .toggle {
        background-color: #512da8;
        height: 100%;
        background: linear-gradient(to right, #5c6bc0, #512da8);
        color: #fff;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: all 0.6s ease-in-out;
    }

    .container.active .toggle {
        transform: translateX(50%);
    }

    .toggle-panel {
        position: absolute;
        width: 50%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 30px;
        text-align: center;
        top: 0;
        transform: translateX(0);
        transition: all 0.6s ease-in-out;
    }

    .toggle-left {
        transform: translateX(-200%);
    }

    .container.active .toggle-left {
        transform: translateX(0);
    }

    .toggle-right {
        right: 0;
        transform: translateX(0);
    }

    .container.active .toggle-right {
        transform: translateX(200%);
    }

    .titlename {
        font-size: 15px;
        font-weight: 550;

    }
    </style>
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-in">
            <form name="signup" method="post" onSubmit="return valid();">
                <h1>SIGN UP</h1>
                <div class="social-icons">
                    <p class="titlename"> LIBRARY MANAGEMENT SYSTEM</p>
                </div>
                <span>REGISTER YOUR ACCOUNT</span>
                <input type="text" name="fullanme" autocomplete="on" placeholder="ENTER YOUR FULL NAME" required />
                <input type="number" name="mobileno" maxlength="10" autocomplete="on"
                    placeholder="ENTER YOUR MOBILE NUMBER" oninput="limitLength(this, 10)" required />
                    <script>
                        function limitLength(element, maxLength) {
                if (element.value.length > maxLength) {
                    element.value = element.value.slice(0, maxLength);
                }
            }
                    </script>
                <input type="email" name="email" id="emailid" onBlur="checkAvailability()"
                    placeholder="ENTER YOUR EMAIL ID" autocomplete="on" required />
                <span id="user-availability-status" style="font-size:12px; color:black;"></span>
                <input type="password" name="password" autocomplete="off" placeholder="CREATE A PASSWORD" required />
                <button type="submit" name="signup" id="submit">SIGN UP</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>Welcome Back !</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login"><a href="index.php">SIGN IN</a></button>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
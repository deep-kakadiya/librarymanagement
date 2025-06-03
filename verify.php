<?php
session_start();
include('includes/config.php');
error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['verify'])) {
    $otp = $_POST['otp'];
    $checkOtpSql = "SELECT StudentId, EmailId, FullName, MobileNumber FROM tblstudents WHERE otp = :otp AND Status = 0";
    $checkOtpQuery = $dbh->prepare($checkOtpSql);
    $checkOtpQuery->bindParam(':otp', $otp, PDO::PARAM_STR);
    $checkOtpQuery->execute();
    $student = $checkOtpQuery->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        $studentId = $student['StudentId'];
        $emailId = $student['EmailId'];
        $fname = $student['FullName'];
        $mobileNumber = $student['MobileNumber'];

        // Update account status
        $updateStatusSql = "UPDATE tblstudents SET Status = 1 WHERE StudentId = :studentId";
        $updateStatusQuery = $dbh->prepare($updateStatusSql);
        $updateStatusQuery->bindParam(':studentId', $studentId, PDO::PARAM_STR);
        $updateStatusQuery->execute();

        // Create email HTML with student details
        $mailHtml = "<br>
        <div style='background-color: black; border-radius: 10px; color: white; padding: 10px; display: inline-block;'>
            <p>HEY, <b style='color:red; font-weight:550;'>$fname</b></p>
            <p color:white;>YOUR ACCOUNT HAS BEEN SUCCESSFULLY VERIFIED.</p><br>
            <p><b>STUDENT IDENTIFY NUMBER</b><br><b style='color: red; font-size: 18px; font-weight: 550;'> $studentId</b></p>
            <p><b>EMAIL ID</b><br><b style='color: red; font-size: 18px; font-weight: 550;'> $emailId</b></p>
            <p><b>MOBILE NUMBER</b><br><b style='color: red; font-size: 18px; font-weight: 550;'> $mobileNumber</b></p>
            <br><br>
            <p color:white;>
            THANKS ,<br> 
            GROUP NO : 72<br>
            TEAM LEADER : DEEP KAKADIYA ( DK )<br>
            SDJ INTERNATIONAL COLLEGE<br><br><br></p>
        </div>";

        // Send email
        if (smtp_mailer($emailId, 'ACCOUNT ACTIVATION SUCCESSFUL', $mailHtml)) {
            echo '<script>alert("OTP VERIFIED SUCCESSFULLY, YOUR ACCOUNT IS NOW ACTIVATED");</script>';
        } else {
            echo '<script>alert("ACCOUNT ACTIVATED BUT FAILED TO SEND VERIFICATION EMAIL");</script>';
        }
        echo "<script type='text/javascript'> document.location ='index.php'; </script>";
    } else {
        echo '<script>alert("INVALID OTP, TRY AGAIN");</script>';
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

    .container a span {
        font-size: 12px;
        font-weight: 500;
        color: black;
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

    a.hidden {
        color: #fff !important;
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
            <form role="form" method="post">
                <h1>VERIFY ACCOUNT</h1>
                <div class="social-icons">
                    <p class="titlename"> LIBRARY MANAGEMENT SYSTEM</p>
                </div>
                <span>VERIFY YOUR ACCOUNT</span>
                <input type="number" placeholder="ENTER YOUR VERIFICATION CODE" name="otp" required autocomplete="on">
                <button type="submit" name="verify">VERIFY</button><br>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">

                <div class="toggle-panel toggle-right">
                    <h1>Hello, student !</h1>
                    <p>EMAIL IS WRONG ? COME BACK.</p>
                    <button class="hidden" id="register"><a href="signup.php">SIGN IN</a></button>
                </div>
            </div>
        </div>
    </div>

  
</body>

</html>
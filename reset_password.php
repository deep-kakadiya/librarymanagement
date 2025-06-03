<?php
session_start();
error_reporting(0);
include('includes/config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $sql = "SELECT EmailId, FullName FROM tblstudents WHERE reset_token_hash = :token AND reset_token_expires_at > NOW()";
    $query = $dbh->prepare($sql);
    $query->bindParam(':token', $token, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result) {
        $email = $result->EmailId;
        $fname = $result->FullName;

        if (isset($_POST['reset_password'])) {
            $newpassword = $_POST['newpassword'];
            $confirmpassword = $_POST['confirmpassword'];

            // Check if passwords match
            if ($newpassword === $confirmpassword) {
                // Hash the new password using md5
                $hashedPassword = md5($newpassword);

                // Update password and clear reset token
                $updateSql = "UPDATE tblstudents SET Password = :newpassword, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE EmailId = :email";
                $updateQuery = $dbh->prepare($updateSql);
                $updateQuery->bindParam(':newpassword', $hashedPassword, PDO::PARAM_STR); // Use md5 hashed password
                $updateQuery->bindParam(':email', $email, PDO::PARAM_STR);
                $updateQuery->execute();

                // Email content
                $mailHtml = "
                    <div style='background-color: black; border-radius: 10px; color: white; padding: 10px; display: inline-block;'>
                        <p>HEY, <b style='color:red; font-weight:550;'>$fname</b></p>
                        <p>YOUR PASSWORD HAS BEEN SUCCESSFULLY RESET.</p>

                        <p style='color:white;'><b style='color:red; font-weight:550;'>WARNING</b><br>IF YOU DIDN'T RESET YOUR PASSWORD, KINDLY CONTACT THE ADMIN.</p>
                        <br><br>
                        <p style='color:white;'>
                            THANKS,<br> 
                            GROUP NO : 72<br>
                            TEAM LEADER : DEEP KAKADIYA (DK)<br>
                            SDJ INTERNATIONAL COLLEGE<br><br><br>
                        </p>
                    </div>";

                // Send the email
                if (smtp_mailer($email, 'PASSWORD RESET SUCCESSFULLY', $mailHtml)) {
                    echo "<script>alert('YOUR PASSWORD HAS BEEN SUCCESSFULLY RESET.');</script>";
                    echo "<script>window.location.href='index.php';</script>";
                } else {
                    echo "<script>alert('FAILED TO SEND CONFIRMATION EMAIL.');</script>";
                    echo "<script>window.location.href='index.php';</script>";
                }
            } else {
                echo "<script>alert('PASSWORDS DO NOT MATCH.');</script>";
            }
        }
    } else {
        echo "<script>alert('INVALID OR EXPIRED TOKEN.');</script>";
        echo "<script>window.location.href='user-forgot-password.php';</script>";
    }
} else {
    echo "<script>alert('NO TOKEN PROVIDED.');</script>";
    echo "<script>window.location.href='user-forgot-password.php';</script>";
}


function smtp_mailer($to, $subject, $msg) {
    require 'phpmailer/src/Exception.php'; 
    require 'phpmailer/src/PHPMailer.php'; 
    require 'phpmailer/src/SMTP.php';

    $mail = new PHPMailer(); 
    $mail->IsSMTP(); 
    $mail->SMTPDebug = 0; 
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'TLS'; 
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587; 
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = "";
    $mail->Password = ""; // Ensure you handle credentials securely
    $mail->SetFrom("deepkakadiya2021@gmail.com", "LIBRARY MANAGEMENT");
    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->AddAddress($to);

    if (!$mail->Send()) {
        return 0;
    } else {
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
            <form method="post"><br>
                <h2>FORGOT PASSWORD ?</h2>
                <div class="social-icons">
                    <p class="titlename"> LIBRARY MANAGEMENT SYSTEM</p>
                </div>
                <span>ENTER NEW PASSWORD HERE</span>
                <input class="form-control" placeholder="ENER YOUR NEW PASSWORD" type="password" name="newpassword" id="newpassword" required
                    autocomplete="off" />
                    <input class="form-control" placeholder="ENER YOUR CONFIRM PASSWORD" type="password" name="confirmpassword" id="confirmpassword" required
                    autocomplete="off" />
                <button type="submit" name="reset_password" class="btn btn-info">RESET PASSWORD</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">

                <div class="toggle-panel toggle-right">
                    <h1>Hello, student !</h1>
                    <p>IF YOU FORGOT YOUR PASSWORD ! <br> YOU CAN CHANGE HERE</p>
                    <button class="hidden" id="register"><a href="signup.php">SIGN UP</a></button><span><button class="hidden"
                        id="register"><a href="index.php">SIGN IN</a></button></span>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>
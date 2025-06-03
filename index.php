<?php
session_start();
error_reporting(0);
include('includes/config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['login'])) {

    $email = $_POST['emailid'];
    $password = md5($_POST['password']); // If using md5, consider upgrading to password_hash and password_verify
    $sql = "SELECT EmailId, Password, StudentId, Status, FullName FROM tblstudents WHERE EmailId = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetch(PDO::FETCH_OBJ);

    if ($results) {
        // Verify if password matches
        if ($results->Password == $password) {
            if ($results->Status == 1) {
                $_SESSION['stdid'] = $results->StudentId;
                $_SESSION['login'] = $email;
                $fname = $results->FullName;

                // Get live location using IP geolocation
                $ip = $_SERVER['REMOTE_ADDR'];
                try {
                    $locationData = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
                    $location = isset($locationData->city) ? $locationData->city : 'UNKNOWN LOCATION';
                } catch (Exception $e) {
                    $location = 'LOCATION SERVICE UNAVAILABLE';
                }

                $location = strtoupper($location);

                // Prepare the email content
                $mailHtml = "
                    <div style='background-color: black; border-radius: 10px; color: white; padding: 10px; display: inline-block;'>
                        <p>HEY, <b style='color:red; font-weight:550;'>$fname</b></p>
                        <p>WE HAVE NOTICED A NEW LOGIN TO YOUR ACCOUNT</p>
                        <p style='color:white;'><b style='color:red; font-weight:550;'>DATE AND TIME</b><br> 
                            $currentDate &nbsp; $currentTime</p>
                        <p style='color:white;'><b style='color:red; font-weight:550;'>LIVE LOCATION</b><br> 
                            $location</p>
                        <p style='color:white;'><b style='color:red; font-weight:550;'>WARNING</b><br>IF YOU DIDN'T LOGIN TO YOUR ACCOUNT, KINDLY CHECK YOUR ACCOUNT AND TAKE ACTION</p>
                        <br><br>
                        <p style='color:white;'>THANKS,<br> 
                            GROUP NO: 72<br>
                            TEAM LEADER: DEEP KAKADIYA (DK)<br>
                            SDJ INTERNATIONAL COLLEGE<br><br><br>
                        </p>
                    </div>";

                // Send the email
                if (smtp_mailer($email, 'YOUR ACCOUNT LOGIN', $mailHtml)) {
                    echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
                } else {
                    echo "<script>alert('YOUR ACCOUNT HAS NOT BEEN VERIFIED, PLEASE VERIFY YOUR ACCOUNT FIRST');</script>";
                }
            } else {
                // Generate OTP
                $otp = rand(100000, 999999); // Generate a 6-digit OTP

                // Save OTP in the database (you might need to create a new column in your database)
                $sqlUpdate = "UPDATE tblstudents SET OTP = :otp WHERE EmailId = :email";
                $updateQuery = $dbh->prepare($sqlUpdate);
                $updateQuery->bindParam(':otp', $otp, PDO::PARAM_INT);
                $updateQuery->bindParam(':email', $email, PDO::PARAM_STR);
                $updateQuery->execute();
$fname = $results->FullName;
                // Send OTP via email
                $otpMailHtml = "<br><div style='background-color: black; border-radius: 10px; color:white; padding: 10px; display: inline-block;'>
                HEY , <b style='color:red; font-weight:550;'>$fname</b><br><br> 
                <p color:white;>YOU SIGN - UP YOUR ACCOUNT BUT NOT ACTIVE YOUR ACCOUNT SO WE SENT OTP FOR ACTIVE YOUR ACCOUNT AND USE LIBRARY MANAGEMENT ACCOUNT.</p>
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

                // Send the OTP email
                if (smtp_mailer($email, 'VERIFY YOUR ACCOUNT', $otpMailHtml)) {
                    // Redirect user to the verification page
                    echo "<script type='text/javascript'> document.location ='verify.php?email=$email'; </script>";
                } else {
                    echo "<script>alert('FAILED TO SEND OTP, PLEASE TRY AGAIN.');</script>";
                }
            }
        } else {
            echo "<script>alert('WRONG USERNAME OR PASSWORD');</script>";
        }
    } else {
        echo "<script>alert('USER NOT FOUND');</script>";
    }
}



// Function to send email
function smtp_mailer($to, $subject, $msg)
{
    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug = 0; // Set to 0 to turn off debug output
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = "";
    $mail->Password = ""; // Make sure this is correct (use app passwords if needed)
    $mail->setFrom("deepkakadiya2021@gmail.com", "LIBRARY MANAGEMENT");
    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->addAddress($to);

    if (!$mail->send()) {
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
                <h1>Sign In</h1>
                <div class="social-icons">
                    <p class="titlename"> LIBRARY MANAGEMENT SYSTEM</p>
                </div>
                <span>LOGIN YOUR ACCOUNT</span>
                <input type="email" placeholder="ENTER YOUR EMAIL" name="emailid" required autocomplete="on">
                <input type="password" placeholder="ENTER YOUR PASSWORD" name="password" required autocomplete="on">
                <button type="submit" name="login">Sign In</button><br>
                <span>or</span>
                <br>
                <a href="user-forgot-password.php"><span>FORGOT A PASSWORD ?</span></a>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">

                <div class="toggle-panel toggle-right">
                    <h1>Hello, student !</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register"><a href="signup.php">SIGN UP</a></button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
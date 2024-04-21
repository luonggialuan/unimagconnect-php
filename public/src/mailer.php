<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "../../vendor/autoload.php";

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->SMTPAuth = true;

//Server settings
$mail->isSMTP();                              //Send using SMTP
$mail->Host = 'smtp.gmail.com';       //Set the SMTP server to send through
$mail->SMTPAuth = true;             //Enable SMTP authentication
$mail->Username = 'unimagconnect@gmail.com';   //SMTP write your email   
$mail->Password = 'zdnt txun arbw rqtv';      //SMTP password  ---> https://myaccount.google.com/apppasswords
$mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
$mail->Port = 465;

$mail->isHtml(true);

return $mail;
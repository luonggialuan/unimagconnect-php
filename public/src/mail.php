<?php
include_once ("../../connect.php");
// require_once "vendor/autoload.php";
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//required files
require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
if (isset($_POST["send"])) {

  $mail = new PHPMailer(true);

  //Server settings
  $mail->isSMTP();                              //Send using SMTP
  $mail->Host = 'smtp.gmail.com';       //Set the SMTP server to send through
  $mail->SMTPAuth = true;             //Enable SMTP authentication
  $mail->Username = 'unimagconnect@gmail.com';   //SMTP write your email   
  $mail->Password = 'zdnt txun arbw rqtv';      //SMTP password  ---> https://myaccount.google.com/apppasswords
  $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
  $mail->Port = 465;

  //Recipients
  // $mail->setFrom($_POST["email"], $_POST["name"]); // Sender Email and name
  $mail->setFrom($_POST["email"], $_POST["name"]); // Sender Email and name
  $mail->addAddress('luonggialuan07402200@gmail.com');     //Add a recipient email
  $mail->addReplyTo($_POST["email"], $_POST["name"]); // reply to sender email

  //Content
  $mail->isHTML(true);               //Set email format to HTML
  $mail->Subject = $_POST["subject"];   // email subject headings
  $mail->Body = $_POST["message"]; //email message

  // Success sent message alert
  $mail->send();
  echo
    " 
    <script> 
     alert('Message was sent successfully!');
    </script>
    ";
  echo "<script>window.history.go(-1);</script>";
}

function sendEmail(string $subject, ?string $recipient_email, ?string $reciptient_fullname, string $message, bool $multi_recipient, ?array $multi_recipient_email)
{
  $mail = new PHPMailer(true);

  try {
    //Server settings
    $mail->isSMTP();                              //Send using SMTP
    $mail->Host = 'smtp.gmail.com';       //Set the SMTP server to send through
    $mail->SMTPAuth = true;             //Enable SMTP authentication
    $mail->Username = 'unimagconnect@gmail.com';   //SMTP write your email   
    $mail->Password = 'zdnt txun arbw rqtv';      //SMTP password  ---> https://myaccount.google.com/apppasswords
    $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
    $mail->Port = 465;

    $mail->setFrom("unimagconnect@gmail.com", "UniMagConnect"); // Sender Email and name

    if ($multi_recipient) {
      foreach ($multi_recipient_email as $key) {
        $mail->addCC($key);
      }
    } else
      $mail->addAddress($recipient_email, $reciptient_fullname);

    //Content
    $mail->isHTML(true);               //Set email format to HTML
    $mail->Subject = $subject;   // email subject headings
    // $mail->Body = $message; //email message

    $mail->msgHTML($message);

    // Success sent message alert
    $mail->send();

  } catch (Exception $e) {
    echo $e->getMessage();
    echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
    echo "<script>window.history.go(-1);</script>";
  }
}
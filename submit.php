<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

define('SENDER_MAIL', 'jackson.badger112@gmail.com');
define('SENDER_NAME', 'Jackson Badger');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $name = $_POST["name"];
  $email = $_POST["email"];
  $phone = $_POST["phone"];
  $message = $_POST["message"];

  // Sanitize form data
  $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $phone = filter_var($phone, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  // Validate form data
  if (empty($name) || empty($email) || empty($phone) || empty($message)) {
    http_response_code(400);
    die("All fields are required.");
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die("Invalid email address.");
  }

  if (!preg_match("/^\d{10,12}$/", $phone)) {
    http_response_code(400);
    die("Invalid phone number.");
  }

  // die('test');
  $mail = new PHPMailer(true);

  try {

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  //gmail SMTP server
    $mail->SMTPAuth = true;
    //to view proper logging details for success and error messages
    // $mail->SMTPDebug = 1;
    $mail->Host = 'smtp.gmail.com';  //gmail SMTP server
    $mail->Username = 'jackson.badger112@gmail.com';   //email
    $mail->Password = 'eeljkzkjoyxtfvtn';   //16 character obtained from app password created
    $mail->Port = 465;                    //SMTP port
    $mail->SMTPSecure = "ssl";

    //sender information
    $mail->setFrom(SENDER_MAIL, SENDER_NAME);

    //receiver email address and name
    $mail->addAddress($email, $name);

    $mail->isHTML(true);

    $mail->Subject = 'Thank you for your message';
    $mail->Body = "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td ><h1>Contact Form Response</h1><p>Dear " . $name . ",</p><p>Thank you for reaching out to us. We have received your message and will get back to you as soon as possible.</p><p>Here is a summary of your message:</p><p><strong>Name:</strong> .$name.</p><p><strong>Email:</strong> .$email.</p><p><strong>Phone:</strong> " . $phone . "</p><p><strong>Message:</strong> " . $message . "</p><p>Thank you for contacting us. We look forward to assisting you.</p><p>Best regards,</p></td></tr></table>";

    // Send mail   
    if (!$mail->send()) {
      echo 'Email not sent an error was encountered: ' . $mail->ErrorInfo;
    } else {

      $mail->clearAddresses();

      $mail->setFrom($email, $name);

      // $mail->setFrom($from, $fromName);
      $mail->addAddress(SENDER_MAIL);

      $mail->Subject = 'New message from Contact form';
      $mail->Body = "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td ><h1>Contact Form Response</h1><p><strong>Name:</strong> .$name.</p><p><strong>Email:</strong> .$email.</p><p><strong>Phone:</strong> " . $phone . "</p><p><strong>Message:</strong> " . $message . "</p></td></tr></table>";
      $mail->isHTML(true);

      if ($mail->send()) {
        echo 'Message has been sent.';
      }
    }

    $mail->smtpClose();
  } catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
  }
} else {
  http_response_code(405);
  echo "Method not allowed.";
}

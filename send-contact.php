<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $to = "psuguide@gmail.com";
  $subject = "PSU-GUIDE Contact Form";
  $name = strip_tags($_POST["name"]);
  $email = strip_tags($_POST["email"]);
  $message = strip_tags($_POST["message"]);

  $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

  if (mail($to, $subject, $body)) {
    header("Location: thanks.html");
  } else {
    echo "Error sending email.";
  }
}
?>

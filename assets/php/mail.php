<?php

    ini_set("display_errors",1);
    error_reporting(E_ALL);
     use PHPMailer\PHPMailer\PHPMailer;
     use PHPMailer\PHPMailer\Exception;
     use PHPMailer\PHPMailer\SMTP;

     require($_SERVER['DOCUMENT_ROOT'].'/assets/php/PHPMailer.php');
     require($_SERVER['DOCUMENT_ROOT'].'/assets/php/SMTP.php'); 
     require($_SERVER['DOCUMENT_ROOT'].'/assets/php/Exception.php');



    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["con_name"]));
				$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["con_email"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["con_message"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Please complete the form and try again.";
            exit;
        }

        $recipient = "info@infraplangroup.co.za";
        $emailFromName = "Infraplan group web";
        $subject = "Mail From $name";

        // Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Message:\n$message\n";

        $mail = new PHPMailer(true);
        try {
           // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
           // $mail->SMTPDebug = 0;
            $mail->isHtml(true);
            $mail->isSMTP();
            $mail->SMTPDebug = 2;
            $mail->Host = "mail.infraplangroup.co.za";
            $mail->Username = "info@infraplangroup.co.za";
            $mail->Password = "1nf03m@1l";
            $mail->Port = "465";
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth = true;
            $mail->setFrom($recipient, $emailFromName);
            $mail->addAddress($recipient, $emailFromName);
            $mail->Subject = $subject;
            $mail->msgHTML($email_content);
            // $mail->Body = $email_content;
            $mail->AltBody = 'HTML messaging not supported';

            if(!$mail->send()){
                 echo "Mailer Error: " . $mail->ErrorInfo;
            }else{
               echo "Thank You! Your message has been sent.";
            }
        }catch(Exception $e){
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }


    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }
?>
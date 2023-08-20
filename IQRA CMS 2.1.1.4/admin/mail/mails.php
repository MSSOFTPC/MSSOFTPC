<?php
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;

function IQ_mail( $to, $subject, $body, $attachments = null ){

                require 'Mailer/src/Exception.php';
                require 'Mailer/src/PHPMailer.php';
                require 'Mailer/src/SMTP.php';

                   // main function start
                $fromemail = site_options('return_email');
                $to = $to;
                $subject = $subject;
                $body = $body;
                $attachments = $attachments;

       
            $mail = new PHPMailer(true);

            try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = site_options('return_smtp_host');                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = site_options('return_smtp_username');                     //SMTP username
                $mail->Password   = site_options('return_smtp_password');                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = site_options('return_smtp_port');                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                //Recipients
                $mail->setFrom($fromemail, site_options('return_title'));
                $mail->addAddress($to, site_options('return_title'));     //Add a recipient
                // $mail->addAddress('ellen@example.com');               //Name is optional
                // $mail->addReplyTo('info@example.com', 'Information');
                // $mail->addCC('cc@example.com');
                // $mail->addBCC('bcc@example.com');
            
                //Attachments
                if(isset($attachments) && !empty($attachments)){
                $mail->addAttachment($attachments);         //Add attachments
                 // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                }
               
            
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $body;
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
                $mail->send();
                return true;
            } catch (Exception $e) {
                return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }




}

// IQ_mail('abc@gmail.com','Hello Subject','Hello Body',CONTENTDIR.'/uploads/71oihG9m38S._SY550_.jpg');

include('mail-default.php');

?>
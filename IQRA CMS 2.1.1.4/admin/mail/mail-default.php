<?php

//  reset
        function IQ_mail_reset($forgettoken,$email){
            $subject = 'Password Reset '. site_options('return_title');
            $variables = array();
            $variables['site_url'] = site_url('');
            $variables['logo'] = site_options('return_logo');
            $variables['forgetlink'] = site_url('admin', 'login.php?action=reset&forgettoken='.$forgettoken.'&email='.$email.'');
            $variables['title'] = site_options('return_title');

            $template = file_get_contents(ADMINROOTDIR.'mail/templates/forget.php');
            foreach($variables as $key => $value){
                $template = str_replace('{{ '.$key.' }}', $value, $template);
                }

            $mail_execute = IQ_mail($email,$subject,$template);
            if($mail_execute == true){
                $_SESSION['success'] = 'Reset Link Sended Successfully';
            }else{
                $_SESSION['error'] = $mail_execute;
            }
           
        }


 // registration
        function IQ_mail_registration($email){
            $subject = 'Thanks for Registration '. site_options('return_title');
            $variables = array();
            $variables['site_url'] = site_url('');
            $variables['logo'] = site_options('return_logo');
            $variables['email_verify_link'] = site_url('admin', 'function.php?email='.$email.'&email_token='.date('Ymd').'');
            $variables['title'] = site_options('return_title');

            $template = file_get_contents(ADMINROOTDIR.'/mail/templates/registration.php');
            foreach($variables as $key => $value){
                $template = str_replace('{{ '.$key.' }}', $value, $template);
                }

            $mail_execute = IQ_mail($email,$subject,$template);
            if($mail_execute == true){
                $_SESSION['success'] = 'Thanks For Registration Email Verification Link has been Send to Mail Successfully';
            }else{
                $_SESSION['error'] = $mail_execute;
            }
        }


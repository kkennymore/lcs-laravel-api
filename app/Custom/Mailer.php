<?php
/* @author: Usiobaifo Kenneth
 * @developer: Usiobaifo Kenneth
 * @year: 2022
 * @rights: Usiobaifo Kenneth
 * */
namespace App\Custom;

class Mailer
{
    public static function emailMethod(string $receiverEmail = '', string $senderEmail = '', string $companyName = '', string $title = '', string $messageData = '', string $btnText = '', string $btnLink = '', bool $hasBtn = false)
    {
        $to = $receiverEmail; // note the comma
        // Subject
        $subject = strtoupper($title);
        // To send HTML mail, the Content-type header must be set
        $headers = "Reply-To: " . strtoupper($companyName) . " <" . $senderEmail . ">\r\n";
        $headers .= "Return-Path: " . strtoupper($companyName) . " <" . $senderEmail . ">\r\n";
        $headers .= "From: " . strtoupper($companyName) . " <" . $senderEmail . ">\r\n";
        $headers .= "Organization: " . strtoupper($companyName) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= "X-Mailer: PHP" . phpversion() . " \r\n";
        $message = '<head>';
        $message .= '<meta charset="UTF-8">';
        $message .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $message .= ' <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $message .= '<link rel="stylesheet" href="https://etc.paybuymax.com/mailing_files/style.css">';
        $message .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        $message .= '<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">';
        $message .= '</head>';
        $message .= '<body class="bg-color-wrap" style="background-color: #f1f1f1; border-radius: 8px;">';
        $message .= '<div class="bg-color" style="margin: 0 10px; padding: 2px 20px;border-radius: 8px;position: relative; display: block; background-color: #f9f9f9">';
        $message .= '<div style=" padding: 2px 10px;text-align:center; position: relative; display: block; background-color: #f9f9f9"><img width="100" src="https://bizchatpay.com/Webroot/Images/SiteImages/logo.webp" alt="Logo"></div><br>';
        $message .= '<div class="spacing" style="padding: 2px 10px;border-radius: 8px;background-color: #f1f1f1; border: 1px solid #e1e1e1; position: relative; display: block; height: 100%; clear: both">';
        $message .= $messageData;
        $message .= '</div>';
        if ($hasBtn) {
            $message .= '<br><div style=" position: relative; display: flex; flex: 1; justify-content: center; align-items: center; margin: 10px padding: 10px; text-align: center;">';
            $message .= '<a style=" position: relative; display: block; padding: 15px; border-radius: 8px; background-color: #773c68; color: #f1f1f1; width: 150px; " href="' . $btnLink . '">' . $btnText . '</a>';
            $message .= '</div>';
        }
        $message .= '<div style=" padding: 2px 10px;position: relative; display: block;">';
        $message .= '<p><b>Thank you for choosing ' . ucfirst(strtolower($companyName)) . '!</b> Best regards<br/> Customer Care<br/> ' . ucfirst(strtolower($companyName)) . '</p>';
        $message .= '<p>Allrights Reserved | ' . ucfirst(strtolower($companyName)) . ' &copy; ' . date("Y", time()) . "</p>";
        $message .= '</div>';
        $message .= '</div>';
        $message .= '</body>';
        // Sending email
        if (@mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }
}

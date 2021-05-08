<?php
if (filter_var($Recipient, FILTER_VALIDATE_EMAIL)){
	   
    require 'Exception.php';
    require 'PHPMailer.php';
    require 'SMTP.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 2;                                        
    $mail->SMTPAuth = false;
    $mail->Host = "smtp.ttu.edu.tw";
	
    $mail->Port = 25;
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64";
    $mail->WordWrap = 500;
    $mail->Username = $From;
	
    $mail->SetFrom('zxcasdqwe8800@gmail.com', '郵件系統管理員');
    $mail->Subject = $Subject;
    $mail->AddAddress($Recipient, $Recipient);
    $Notice = $Recipient . " 您好\n\n" . $Message . "\n\n此信件為系統自動發出請勿回覆，謝謝！\n";
    $mail->Body = $Notice;
    $mail->Send();
    $mail->ClearAllRecipients();
}
?>
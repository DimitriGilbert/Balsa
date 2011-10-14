<?php

function mail_de_base($adr,$subject,$message,$from='someone@yopmail.com',$reply='no_reply@yopmail.com')
{
  inclure_fonction('lib/mailer/class.phpmailer.php');
  $mail = new PHPMailer();

  $mail->AddAddress($adr, '');

  $mail->IsSMTP(); // telling the class to use SMTP
  $mail->Host = ""; // SMTP server
  $mail->SMTPAuth = true; // enable SMTP authentication
  $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
  $mail->Host = "||adresse_serveur||"; // sets GMAIL as the SMTP server
  $mail->Port = ||port_serveur||; // set the SMTP port for the GMAIL server
  $mail->Username = "||utilisateur||"; // GMAIL username
  $mail->Password = "||mdp_mail||"; // GMAIL password

  $mail->SetFrom($from, '', 1);

  $mail->AddReplyTo($reply,"");

  $mail->Subject = $subject;

  $mail->MsgHTML($message);


  $mail->IsHTML(true); // send as HTML

  if(!$mail->Send()){
    return true;
  }
}

//$pj est le chemin vers la piece jointe (peut etre un tableau de chemin), $pjn est le nom de la piece jointe(peut etre un tab aussi :)
function mail_plus_piece_jointe($adr,$subject,$message,$pj,$pjn,$from='someone@yopmail.com',$reply='no_reply@yopmail.com')
{
  inclure_fonction('lib/mailer/class.phpmailer.php');
  $mail = new PHPMailer();

  $mail->AddAddress($adr, '');

  $mail->IsSMTP(); // telling the class to use SMTP
  $mail->Host = ""; // SMTP server
  $mail->SMTPAuth = true; // enable SMTP authentication
  $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
  $mail->Host = "||adresse_serveur||"; // sets GMAIL as the SMTP server
  $mail->Port = ||port_serveur||; // set the SMTP port for the GMAIL server
  $mail->Username = "||utilisateur||"; // GMAIL username
  $mail->Password = "||mdp_mail||"; // GMAIL password

  $mail->SetFrom($from, '', 1);

  $mail->AddReplyTo($reply,"");

  $mail->Subject = $subject;

  $mail->MsgHTML($message);


  $mail->IsHTML(true); // send as HTML

  if(is_array($pj))
  {
    $x=0;
    foreach($pj as $p)
    {
      $mail->addAttachement($p,$pjn[$x]);
      $x++;
    }
  }
  else
  {
    $mail->addAttachement($pj,$pjn);
  }

  if(!$mail->Send()){
    return true;
  }
}

?>



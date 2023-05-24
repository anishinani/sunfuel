<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//include_once("../cursor.php");
require 'PHPMailerAutoload.php';

class MyMail extends PHPMailer
{
    private $_host      = "server2.thinkxcloud.com";     //'your stmp server name'
  private $_user      = "info@creditplus.ug";   //'your smtp username'
  private $_password  = "W_!JPY%xGF3f";                 // 'your password'
  private $_name      =  "Creditplus";

  // private $_host      = "smtp.gmail.com";     //'your stmp server name'
  // private $_user      = "katznicho@gmail.com";   //'your smtp username'
  // private $_password  = "uwbngqaxyzyzfyxc";                 // 'your password'
  // private $_name  =   "Creditplus";

  // echo 1111;

  public function __construct($exceptions = true)
  {
    $this->SMTPDebug = 0;

    // 0 = no output, 1 = errors and messages, 2 = messages only.


    $this->IsSMTP();
    $this->Host = $this->_host;
    $this->Port = "465";
    // //usually the port for TLS is 587, for SSL is 465 and non-secure is 25
    $this->SMTPSecure = "ssl";
    // //TLS, SSL or  delete the line
    $this->SMTPAuth = true;
    $this->Username = $this->_user;
    $this->Password = $this->_password;
    $this->From     = $this->_user;
    $this->FromName = $this->_name;
    $this->IsHTML(true);
    parent::__construct($exceptions);
  }

  public function sendMail($from, $to, $subject, $body)
  {

    $table = "email_queue";

    $this->From = $this->_user;
    $this->AddAddress($to, $this->_name);
    $this->Subject = $subject;
    $this->Body = $body;
    $result = $this->Send();
    //$db=new Cursor;
    if (!$result) {
      // echo 'Mailer error: '.$this->ErrorInfo;   //  Invalid address: (addAnAddress to): Creditplus Account Activation 
      $data['to_email'] = $to;
      $data['subject'] = $subject;
      $data['message'] = $body;
      $data['success'] = $result;
      // $data['csv_attached'] = $result;

      // $id = $db->insert("email_queue" , $data);

      // $id = $db->select("cp_employer",["*"]);
      // echo $id;
      // print_r($id);
      return "failed";
    } else {
      $data['to_email'] = $to;
      $data['subject'] = $subject;
      $data['message'] = $body;
      $data['success'] = $result;
      // $data['csv_attached'] =$result;

      //$id = $db->insert("email_queue", $data);
      // echo $id;
      // print_r($data);
      // echo $this->mailHeader;

      return "success";
    }
  }

  public function PDF_Attachment($from, $to, $subject, $body, $pdf)

  {

    $this->From = $this->_user;
    $this->AddAddress($to, $this->_name);
    $this->Subject = $subject;
    $this->Body = $body;

    foreach ($pdf as $attachment) {
      $this->AddAttachment($attachment, '', $encoding = 'base64', $type = 'application/pdf');
    }



    if (!$this->Send()) {
      return "failed";
    } else {
      return "success";
    }
  }
}

// try {
//   //code...
//   $m = new MyMail();
// $to       =  "pnagaba254@gmail.com";
// // $name     =  "creditors";
// $subject  =  "Creditplus Account Activation test";
// $body     =  "Hello Charles,last mailer test";
// $from     =   $m->From;
// $result = $m->sendMail($from, $to, $subject, $body);
// echo $result;
// } catch (\Throwable $th) {
//   //throw $th;
//   die($th->getMessage());
// }


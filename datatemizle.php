<?php
$servername = "localhost";
$username = "kalite";
$password = "kali-te**";
$dbname = "Kalite";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
          die("Veritabani baglanti hatasi: " . $conn->connect_error);
  }

  $error = true;
  $sql='bos'; 
  $msg = array('Durum' => !$error, 'msg' => 'veritabani tamizlenemedi! ');
 
  if(isset($_POST)){
    if(isset($_POST['data']) && !empty($_POST['data']) && $error) {
      $table = $_POST['data'];
      $error = false;
      
    } else {
      $error = true;
    }
  
    if(!$error) {
        $sql = "TRUNCATE TABLE ".$table;
        $status = $conn->query($sql);
        $msg = array('Durum' => !$error, 'msg' => 'Veritabanı temizlendi.'); 
    }
  }
  
   // send data as json format
  
  echo json_encode($msg);
?>

<?php
$servername = "localhost";
$username = "user-name";
$password = "pass-word";
$dbname = "Kalite";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
  }

  $columns = array(
    'id', 
    'dokumanno',
    'dosyaadi',
    'versiyon',
    'yayintarihi',
    'aciklama',
    'boyut',
    'klasor'
  );
  $error = true;
  $colVal = '';
  $colIndex = $rowId = 0;
  $sql='bos'; 
  $msg = array('Durum' => !$error, 'msg' => 'veritabaninda guncelleme sirasinda hata olustu! ');
 
  if(isset($_POST)){
    if(isset($_POST['deger']) && !empty($_POST['deger']) && $error) {
      $colVal = $_POST['deger'];
      $error = false;
      
    } else {
      $error = true;
    }
    if(isset($_POST['kolonno']) && $_POST['kolonno'] > 0  &&  $error) {
      $colIndex = $_POST['kolonno'];
      $error = false;
    } else {
      $error = true;
    }
    if(isset($_POST['id']) && $_POST['id'] > 0 && $error) {
      $rowId = $_POST['id'];
      $error = false;
    } else {
      $error = true;
    }
  
    if(!$error) {
        if($_POST['kolonno'] == 4 ) {
		$date = explode('.', $colVal);
		$colVal = "".$date[2].'-'.$date[1].'-'.$date[0]."";
	}
        $sql = "UPDATE dosyalar SET ".$columns[$_POST['kolonno']]." = '".$colVal."' WHERE id='".$rowId."'";
        $status = $conn->query($sql);
        $msg = array('Durum' => !$error, 'msg' => 'Guncelleme basarili.'); 
    }
  }
  
   // send data as json format
  
  echo json_encode($msg);
?>

<?php


    function ShowFile($file){
	$file = 'merkez/'.$file;
	if (file_exists($file)) {
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename="'.basename($file).'"');
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Cache-Control: private",false);
	    readfile($file);
	    exit;
	} else {
	    echo $file;
	}
    }
$dosya=$_GET['dosyaadi'];
ShowFile($dosya);
?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
}

#customers tr:nth-child(even){
    background-color: #f2f2f2;
    padding-top: 8px;
    padding-bottom: 8px;
}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding-top: 8px;
    padding-bottom: 8px;
    text-align: left;
    background-color: #FCAF50;
    color: white;
}
</style>
<script>
function openInNewTab(url) {
  var win = window.open('http://78.188.119.90:8080/openfile.php'+url, '_blank');
  win.focus();
}
</script>
</head>
<body>
<div class="container">
<div>
<?php

/**
*	$tip 1 klasor demek , 2 dosya 
*	$detay 
*		klasor de detay[0] klasor adi
*		dosya da detay[0] dosya adi
*			 detay[1] path
*			 detay[2] versiyon
*			 detay[3] aciklama
*			 detay[4] dosya boyutu
*			 detay[5] degisiklik varmi yok mu kontrolu var da Degisti , yokta - olur
*			 detay[6] kayitno
*			 detay[7] yayin tarihi
*			 detay[8] dokuman no
***/
    function Goster($tip, $detay) {
	switch ($tip) {
	case 0:
	    break;
	case 1:
//		echo '<a href=\'?dosyaadi='.$detay[1].'/'.$detay[0].'\'> <img src="img/'.strtolower(substr($detay[0], strrpos($detay[0], '.')+1)).'.png" height="20" width="20"> '." ";
//		echo $detay[0]." </a>\n";
	    break;
	case 2:
		echo "<tr><td>";
		echo "<div onclick=\"openInNewTab('?dosyaadi=".$detay[1]."/".$detay[0]."');\">".$detay[6]."</div>";
		echo "</td><td>";
		echo "<div onclick=\"openInNewTab('?dosyaadi=".$detay[1]."/".$detay[0]."');\">".$detay[8]."</div>";
		echo "</td><td>";
		echo "<div onclick=\"openInNewTab('?dosyaadi=".$detay[1]."/".$detay[0]."');\"><img src=\"/img/".strtolower(substr($detay[0], strrpos($detay[0], ".")+1)).".png\" height=\"20\" width=\"20\">".$detay[0]."</div>";
		echo "</td><td>";
		echo "<div onclick=\"openInNewTab('?dosyaadi=".$detay[1]."/".$detay[0]."');\">".$detay[2]."</div>";
		echo "</td><td width=\"12\">";
		echo "<div onclick=\"openInNewTab('?dosyaadi=".$detay[1]."/".$detay[0]."');\">".$detay[7]."</div>";
		echo "</td><td>";
		echo "<div onclick=\"openInNewTab('?dosyaadi=".$detay[1]."/".$detay[0]."');\">".$detay[3]."</div>";
		echo "</td><td>";
		echo "<div onclick=\"openInNewTab('?dosyaadi=".$detay[1]."/".$detay[0]."');\">".$detay[4]."</div>";
		echo "</td><td>";
		echo "<div onclick=\"openInNewTab('?dosyaadi=".$detay[1]."/".$detay[0]."');\">".$detay[1]."</div>";
		echo "</td></tr>";
		//echo "<br>\n";
	    break;
	case 3:
	    break;
	}
	return $say;
    }

    function veritabanikontrol($dosyaadi, $dosyayolu, $boyut){
	$servername = "localhost";
	$username = "user-name";
	$password = "pass-word";
	$dbname = "Kalite";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "select id,dosyaadi, klasor, versiyon, aciklama, boyut, yayintarihi, dokumanno from dosyalar where dosyaadi='".$dosyaadi."' and klasor='".$dosyayolu."' ";  
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
		if ($row["boyut"] == $boyut) {
			$durum="-";
		} else {
			$durum="Degismis";
		}
	   	$sonuc= array($row["versiyon"],$row["aciklama"],number_format($row["boyut"] / 1024, 2) . ' KB',$durum, $row["id"],$row["yayintarihi"],$row["dokumanno"]);
	    }
	} else {
//	        $sql = "insert into dosyalar  (dosyaadi, klasor, versiyon, aciklama, boyut) values ('".$dosyaadi."', '".$dosyayolu."', '1.0.0', '".$dosyaadi."', ".$boyut.") ";  
//		$conn->query($sql);
//		$sonuc=array("1"," ",number_format($boyut / 1024, 2) . ' KB',"-");
	}
	$conn->close();

	return $sonuc;
    }


     /**
     * Get an array that represents directory tree
     * @param string $directory     Directory path
     * @param bool $recursive         Include sub directories
     * @param bool $listDirs         Include directories on listing
     * @param bool $listFiles         Include files on listing
     * @param regex $exclude         Exclude paths that matches this regex
     */
   
    function directoryToArray($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '', $sayac) {
        $arrayItems = array();
        $skipByExclude = false;
        $handle = opendir($directory);
	$oncekiklasor = "";
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
              preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE|.htaccess|.css|_dosyalar|.htm|.php|.swp))$/iu", $file, $skip);
              if($exclude){
                preg_match($exclude, $file, $skipByExclude);
              }
              if (!$skip && !$skipByExclude) {
                if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
                    if($recursive) {
                        $arrayItems = array_merge($arrayItems, directoryToArray($directory. DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude, $sayac));
                    }
                    if($listDirs){
                        $file = $directory . DIRECTORY_SEPARATOR . $file;
                        $arrayItems[] = $file;
                    }
                } else {

                    if($listFiles){
			if (strcmp(str_replace("/var/www/html/merkez","",$directory),$oncekiklasor)==0) {
				$dosyasize = filesize("/var/www/html/merkez/".$oncekiklasor."/".$file);
				$gerigelen = veritabanikontrol($file, $oncekiklasor, $dosyasize);
				$gerigelen1[0] = $file;
				$gerigelen1[1] = $oncekiklasor;

				Goster(2, array_merge($gerigelen1, $gerigelen));
			} else {
				Goster(3, $oncekiklasor);
				$oncekiklasor=str_replace("/var/www/html/merkez/","",$directory);
				Goster(0, $oncekiklasor);
				//Goster(1, str_replace("/var/www/html/merkez/","",$directory));
				
				$dosyasize = filesize("/var/www/html/merkez/".$oncekiklasor."/".$file);
				$gerigelen = veritabanikontrol($file, $oncekiklasor, $dosyasize);
				$gerigelen1[0] = $file;
				$gerigelen1[1] = $oncekiklasor;

				Goster(2, array_merge($gerigelen1, $gerigelen));
			} 
                       	$file = $directory . DIRECTORY_SEPARATOR . $file;
                        $arrayItems[] = $file;
                    }
                }
              }
        }
        closedir($handle);
        }
        return $arrayItems;
    }
?>
<br><br>
<table id='customers'>
<thead>
<tr>
   <th>Sıra</th>
   <th>DökümanNo</th>
   <th>Dosya</th>
   <th>Versiyon</th>
   <th>Yayın_Tarihi</th>
   <th>Açıklama</th>
   <th>Boyut(KB)</th>
   <th>Klasor</th>
</tr>
</thead>

<?php
    directoryToArray('/var/www/html/merkez', $recursive = true, $listDirs = false, $listFiles = true, $exclude = '%\~\$%',$divsay);
    echo "</table>";
    echo "<br><br><br><br><br></div>";
?>

<script type="text/javascript" src="/gs_sortable.js"></script>
<script type="text/javascript">
<!--
var TSort_Data = new Array ('customers', 'i', 's', 's', 's', 's', 's', 's', 's', 's');
tsRegister();
// -->
</script>

<br><br><br><br>
</body>
</html>

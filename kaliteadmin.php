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
function doConfirm(msg, yesFn, noFn)
{
    var confirmBox = $("#confirmBox");
    confirmBox.find(".message").text(msg);
    confirmBox.find(".yes,.no").unbind().click(function()
    {
        confirmBox.hide();
    });
    confirmBox.find(".yes").click(yesFn);
    confirmBox.find(".no").click(noFn);
    confirmBox.show();
}

function DataTemizle(){
    alert("Evet dedin.");
            $.ajax({
                type: 'POST',
                data: 'dosyalar',
                url: '/datatemizle.php,
                success: function(ajaxCevap) {
                    $('#cevap').html(ajaxCevap);
                }
            });

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
*			 detay[7] yayintarihi
*			 detay[8] dokuman no
***/
    function Goster($tip, $detay) {
	switch ($tip) {
	case 0:
	    break;
	case 1:
	    break;
	case 2:
		echo "<tr data-row-id=".$detay[6].">";
		echo "<td class='editable-col' contenteditable='false' col-index='0' oldVal =".$detay[6].">".$detay[6]." \n";
		echo "</td><td class='editable-col' contenteditable='true' col-index='1' oldVal =".$detay[8].">";
		echo $detay[8];
		echo "</td><td>";
                echo "<div onclick=\"openInNewTab('?dosyaadi=".$detay[1]."/".$detay[0]."');\"><img src=\"/img/".strtolower(substr($detay[0], strrpos($detay[0], ".")+1)).".png\" height=\"20\" width=\"20\">".$detay[0]."</div>";
		echo "</td><td class='editable-col' contenteditable='true' col-index='3' oldVal =".$detay[2].">";
		echo $detay[2];
		echo "</td><td class='editable-col' contenteditable='true' col-index='4' oldVal =".$detay[7].">";
		echo $detay[7];
		echo "</td><td class='editable-col' contenteditable='true' col-index='5' oldVal =".$detay[3].">";
		echo $detay[3];
		echo "</td><td>";
		echo $detay[4];
		echo "</td><td>";
//		echo $detay[5];
//		echo "</td><td>";
		echo $detay[1];
		echo "</td></tr>";
		//echo "<br>\n";
	    break;
	case 3:
	    break;
	}
	return 0;
    }

    function veritabanikontrol($dosyaadi, $dosyayolu, $boyut){
	$servername = "localhost";
	$username = "user-name";
	$password = "pass-word";
	$dbname = "Kalite";

	$connn = new mysqli($servername, $username, $password, $dbname);
	if ($connn->connect_error) {
	    die("Connection failed: " . $connn->connect_error);
	} 

	$sql = "select id,dosyaadi, klasor, versiyon, aciklama, boyut, DATE_FORMAT(yayintarihi, '%d.%m.%Y') as yayintarihi, dokumanno from dosyalar where dosyaadi='".$dosyaadi."' and klasor='".$dosyayolu."' ";  
	$result = $connn->query($sql);

	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
		if ($row["boyut"] == $boyut) {
			$durum="-";
		} else {
			$durum="Degismis";
		}
	   	$sonuc= array($row["versiyon"],$row["aciklama"],number_format($row["boyut"] / 1024, 2) . ' KB',$durum, $row["id"], $row["yayintarihi"],$row["dokumanno"]);
	    }
	} else {
	        $sql = "insert into dosyalar  (dosyaadi, klasor, versiyon, aciklama, boyut, yayintarihi,dokumanno) values ('".$dosyaadi."', '".$dosyayolu."', '1.0.0', '".$dosyaadi."', ".$boyut.", '".date("Y/m/d")."',' ') ";  
		$connn->query($sql);
		$sonuc=array("1"," ",number_format($boyut / 1024, 2) . ' KB',"-");
	}
	$connn->close();

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
   
    function directoryToArray($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '') {
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
                        $arrayItems = array_merge($arrayItems, directoryToArray($directory. DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude));
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
<div id="cevap" style="font-weight: bold;"></div>

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
   <tbody id="_editable_table">

<?php

    directoryToArray('/var/www/html/merkez', $recursive = true, $listDirs = false, $listFiles = true, $exclude = '%\~\$%');
    if ($_GET['dosyaadi']!="") {
	$dosyaadi = $_GET['dosyaadi']; // Email address of sender 
	$link = "<script>window.open('http://78.188.119.90:8080/openfile.php?dosyaadi=$dosyaadi', 'width=710,height=555,left=160,top=170')</script>";

	echo $link;
    }
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
<script type="text/javascript">
$(document).ready(function(){
        $('td.editable-col').on('focusout', function() {
                var data = {};
                data['deger'] = $(this).text();
                data['id'] = $(this).parent('tr').attr('data-row-id');
                data['kolonno'] = $(this).attr('col-index');
            if($(this).attr('oldVal') === data['val'])
                return false;
	    console.log(data);
	    $.ajax({
	        type: 'POST',
	        data: data,
	        url: '/server.php?veri='+data,
	        success: function(ajaxCevap) {
	            $('#cevap').html(ajaxCevap);
	        }
	    });
        });
});
</script>



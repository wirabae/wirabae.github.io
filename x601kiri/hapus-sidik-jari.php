<html>
<head><title>Hapus Sidik Jari</title></head>
<body bgcolor="#caffcb">

<H3>Hapus Sidik Jari</H3>

<?php
$IP=@$_GET["ip"];
$Key=@$_GET["key"];
$id=@$_GET["id"];
$fn=@$_GET["fn"];
$temp=@$_GET["temp"];

if($IP=="") $IP="192.168.97.117";
if($Key=="") $Key="0";
if($id=="") $id="";
if($fn=="") $fn="";
?>

<form action="hapus-sidik-jari.php">
IP Address: <input type="Text" name="ip" value="<?=$IP?>" size=15><BR>
Comm Key: <input type="Text" name="key" size="5" value="<?=$Key?>"><BR><BR>

UserID: <input type="Text" name="id" size="5" value="<?=$id?>"><BR>
<BR>

<input type="Submit" value="Hapus">
</form>
<BR>

<?php
if(@$_GET["ip"]!=""){?>

	<?php
	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$soap_request="<DeleteTemplate><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">".$id."</PIN></Arg></DeleteTemplate>";
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
	    fputs($Connect, "Content-Type: text/xml".$newLine);
	    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
	    fputs($Connect, $soap_request.$newLine);
		$buffer="";
		while($Response=fgets($Connect, 1024)){
			$buffer=$buffer.$Response;
		}
	}else echo "Koneksi Gagal";
	include("parse.php");
//	echo $buffer;
	$buffer=Parse_Data($buffer,"<DeleteTemplateResponse>","</DeleteTemplateResponse>");
	$buffer=Parse_Data($buffer,"<Information>","</Information>");
	echo "<B>Result:</B><BR>".$buffer;
	
}?>


</body>
</html>

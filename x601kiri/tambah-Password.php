<html>
<head><title>Tambah Password</title></head>
<body bgcolor="#caffcb">

<H3>Tambah Password</H3>

<?php
$IP=@$_GET["ip"];
$Key=@$_GET["key"];
$id=@$_GET["id"];
$password=@$_GET["password"];
if($IP=="") $IP="192.168.97.117";
if($Key=="") $Key="0";
if($id=="") $id="";
if($password=="") $password="";

?>

<form action="tambah-Password.php">
IP Address: <input type="Text" name="ip" value="<?=$IP?>" size=15><BR>
Comm Key: <input type="Text" name="key" size="5" value="<?=$Key?>"><BR><BR>

UserID: <input type="Text" name="id" size="5" value="<?=$id?>"><BR>
Password: <input type="Text" name="password"  value="<?=$password?>"><BR><BR>

<input type="Submit" value="Tambah password">
</form>
<BR>

<?php
if(@$_GET["ip"]!=""){
	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$id=@$_GET["id"];
		$nama=@$_GET["password"];
		$soap_request="<SetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN>".$id."</PIN><Password>".$password."</Password></Arg></SetUserInfo>";
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
	    fputs($Connect, "Content-Type: text/xml".$newLine);
	    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
	    fputs($Connect, $soap_request.$newLine);
		$buffer="";
		while($Response=fgets($Connect, 1024)){
			$buffer=$buffer.$Response;
		}
	} else echo "Koneksi Gagal";
	include("parse.php");	
	$buffer=Parse_Data($buffer,"<Information>","</Information>");
	echo "<B>Result:</B><BR>";
	echo $buffer;
}	
?>

</body>
</html>


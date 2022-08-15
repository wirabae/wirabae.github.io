<html>
<head><title>Restart</title></head>
<body bgcolor="#caffcb">

<H3>Restart Device</H3>

<?PHP
$IP=@$_GET["ip"];
$Key=@$_GET["key"];
if($IP=="") $IP="192.168.97.117";
if($Key=="") $Key="0";
?>

<form action="restart.php">
IP Address: <input type="Text" name="ip" value="<?=$IP?>" size=15><BR>
Comm Key: <input type="Text" name="key" size="5" value="<?=$Key?>"><BR><BR>

<input type="Submit" value="Restart">
</form>
<BR>

<?PHP
if(@$_GET["ip"]!=""){
	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$id=@$_GET["id"];
		$nama=@$_GET["nama"];
		$soap_request="<Restart><ArgComKey Xsi:type=\"xsd:integer\">".$Key."</ArgComKey></Restart>";
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
	echo $buffer;
	$buffer=Parse_Data($buffer,"<Information>","</Information>");
	echo "<B>Result:</B><BR>";
	echo $buffer;
}	
?>

</body>
</html>


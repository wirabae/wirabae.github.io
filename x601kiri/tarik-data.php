<html>
<head><title>Tarik Data</title></head>
<body bgcolor="#caffcb">

<H3>Download Log Data</H3>

<?php
$IP=@$_GET["ip"];
$Key=@$_GET["key"];
$id=@$_GET["id"];
if($IP=="") $IP="192.168.97.117";
if($Key=="") $Key="0";
if($id=="") $id="";
?>

<form action="tarik-data.php">
IP Address: <input type="Text" name="ip" value="<?=$IP?>" size=15><BR>
Comm Key: <input type="Text" name="key" size="5" value="<?=$Key?>"><BR><BR>
UserID: <input type="Text" name="id" size="5" value="<?=$id?>"><BR><BR>

<input type="Submit" value="Download">
</form>
<BR>

<?php
if(@$_GET["ip"]!=""){?>
	<table cellspacing="2" cellpadding="2" border="1">
	<tr align="center">
	    <td><B>UserID</B></td>
	    <td width="200"><B>Tanggal & Jam</B></td>
	    <td><B>Verifikasi</B></td>
	    <td><B>Status</B></td>
	</tr>
	<?php
	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">".$id."</PIN><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
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
	$buffer=Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
	$buffer=explode("\r\n",$buffer);
	for($a=0;$a<count($buffer);$a++){
		$data=Parse_Data($buffer[$a],"<Row>","</Row>");
		$PIN=Parse_Data($data,"<PIN>","</PIN>");
		$DateTime=Parse_Data($data,"<DateTime>","</DateTime>");
		$Verified=Parse_Data($data,"<Verified>","</Verified>");
		$Status=Parse_Data($data,"<Status>","</Status>");
	?>
	<tr align="center">
		    <td><?=$PIN?></td>
		    <td><?=$DateTime?></td>
		    <td><?=$Verified?></td>
		    <td><?=$Status?></td>
		</tr>
	<?php }?>
	</table>
<?php }?>

</body>
</html>

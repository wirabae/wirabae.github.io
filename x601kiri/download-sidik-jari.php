<html>
<head><title>Download Sidik Jari</title></head>
<body bgcolor="#caffcb">

<H3>Download Sidik Jari</H3>

<?php
$IP=@$_GET["ip"];
$Key=@$_GET["key"];
$id=@$_GET["id"];
$fn=@$_GET["fn"];

if($IP=="") $IP="192.168.97.117";
if($Key=="") $Key="0";
if($id=="") $id="";
if($fn=="") $fn="";
?>

<form action="download-sidik-jari.php">
IP Address: <input type="Text" name="ip" value="<?=$IP?>" size=15><BR>
Comm Key: <input type="Text" name="key" size="5" value="<?=$Key?>"><BR><BR>

UserID: <input type="Text" name="id" size="5" value="<?=$id?>"><BR>
Finger No: <input type="Text" name="fn" size="1" value="<?=$fn?>"><BR><BR>

<input type="Submit" value="Download">
</form>
<BR>

<?php
if(@$_GET["ip"]!=""){?>

	<table cellspacing="2" cellpadding="2" border="1">
	<tr align="center">
	    <td><B>UserID</B></td>
	    <td width="200"><B>FingerID</B></td>
	    <td><B>Size</B></td>
	    <td><B>Valid</B></td>
	    <td align="left"><B>Template</B></td>		
	</tr>

	<?php
	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$soap_request="<GetUserTemplate><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">".$id."</PIN><FingerID xsi:type=\"xsd:integer\">".$fn."</FingerID></Arg></GetUserTemplate>";
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
	
	echo $buffer;
	
	include("parse.php");
	$buffer=Parse_Data($buffer,"<GetUserTemplateResponse>","</GetUserTemplateResponse>");
	$buffer=explode("\r\n",$buffer);
	for($a=0;$a<count($buffer);$a++){
		$data=Parse_Data($buffer[$a],"<Row>","</Row>");
		$PIN=Parse_Data($data,"<PIN>","</PIN>");
		$FingerID=Parse_Data($data,"<FingerID>","</FingerID>");
		$Size=Parse_Data($data,"<Size>","</Size>");
		$Valid=Parse_Data($data,"<Valid>","</Valid>");
		$Template=Parse_Data($data,"<Template>","</Template>");?>
		<tr align="center">
		    <td><?=$PIN?></td>
		    <td><?=$FingerID?></td>
		    <td><?=$Size?></td>
		    <td><?=$Valid?></td>
		    <td><?=$Template?></td>			
		</tr>		
	<?php }?>
</table>
<?php }?>


</body>
</html>

<html>
<head><title>Semua Informasi</title></head>
<body bgcolor="#caffcb">

<H3>Get All Info User</H3>

<?php
$IP=@$_GET["ip"];
$Key=@$_GET["key"];
if($IP=="") $IP="192.168.97.117";
if($Key=="") $Key="0";
?>

<form action="get-all-info-user.php">
IP Address: <input type="Text" name="ip" value="<?=$IP?>" size=15><BR>
Comm Key: <input type="Text" name="key" size="5" value="<?=$Key?>"><BR><BR>

<input type="Submit" value="Download">
</form>
<BR>

<?php
if(@$_GET["ip"]!=""){?>
	<table cellspacing="2" cellpadding="2" border="1">
	<tr align="center">
	    <td><B>UserID</B></td>
	    <td width="200"><B>Nama</B></td>
	    <td><B>Password</B></td>
	    <td><B>Group</B></td>
	    <td><B>Privilege</B></td>
	    <td><B>Card</B></td>
	    <td><B>TZ1</B></td>
	    <td><B>TZ2</B></td>
	    <td><B>TZ3</B></td>

	</tr>
	<?php
	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$soap_request="<GetAllUserInfo><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN2 xsi:type=\"xsd:integer\">All</PIN2></Arg></GetAllUserInfo>";
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
	$buffer=Parse_Data($buffer,"<GetAllUserInfoResponse>","</GetAllUserInfoResponse>");
	$buffer=explode("\r\n",$buffer);
	for($a=0;$a<count($buffer);$a++){
		$data=Parse_Data($buffer[$a],"<Row>","</Row>");
		$PIN2=Parse_Data($data,"<PIN2>","</PIN2>");
		$Name=Parse_Data($data,"<Name>","</Name>");
		$Password=Parse_Data($data,"<Password>","</Password>");
		$Group=Parse_Data($data,"<Group>","</Group>");
		$Privilege=Parse_Data($data,"<Privilege>","</Privilege>");
		$Card=Parse_Data($data,"<Card>","</Card>");
		$TZ1=Parse_Data($data,"<TZ1>","</TZ1>");
		$TZ2=Parse_Data($data,"<TZ2>","</TZ2>");
		$TZ3=Parse_Data($data,"<TZ3>","</TZ3>");
		
	?>
	<tr align="center">
		    <td><?=$PIN2?></td>
		    <td><?=$Name?></td>
		    <td><?=$Password?></td>
		    <td><?=$Group?></td>
		    <td><?=$Privilege?></td>
		    <td><?=$Card?></td>
		    <td><?=$TZ1?></td>
		    <td><?=$TZ2?></td>
		    <td><?=$TZ3?></td>
		   
		</tr>
	<?php }?>
	</table>
<?php }?>

</body>
</html>

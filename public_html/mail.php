<?php
//pobranie adresu pacjenta
$klient_mail="";

if(isset($_SESSION['id_mail']))
{
$id2=$_SESSION['id_mail'];

require_once "conected.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$con= new mysqli($servername,$username,$password,$database);
		if($con->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}

		else
		{
		$rez=$con->query("SELECT *from customers WHERE Id=$id2 ");
		if(!$rez) throw new Exception($con->error);
		
		$row=mysqli_fetch_assoc($rez);
		$klient_mail=$row['Mail'];
        }
			$con->close();

	}
		
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}

}
///mail
$to = "daniello.gab@gmail.com,'$klient_mail'";
$subject = "HTML email";

$message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>elo tu strona e porady;)</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <webmaster@example.com>' . "\r\n";
$headers .= 'Cc: myboss@example.com' . "\r\n";

mail($to,$subject,$message,$headers);
?>   				    	

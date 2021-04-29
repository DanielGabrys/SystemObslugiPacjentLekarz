<?php
session_start();
if(!isset($_SESSION['zalogowany2']))
{
header('location:zaloguj_doctor_layout.php');
}

/////////sprawdzamy czy sa nieprzeczytane wiadomosci
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
	    $id=$_SESSION['Id'];
		$rez=$con->query("SELECT *from powiadomienia_lekarze where lekarz_id='$id'AND status='nieprzeczytane' ");
		if(!$rez) throw new Exception($con->error);
		
		$ile=$rez->num_rows;
        }
			$con->close();

	}
		
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}



	
require_once "menu.php"; //arkusz stylow
?>

<!DOCTYPE html>
<html>
 <link href="menu.css" rel="stylesheet">  
<head>
	<title>System obslugi dokkora</title>
</head>

<body>
<?php
unset($_SESSION['licznik']);
unset($_SESSION['urlop']);
unset($_SESSION['urlop_pozostaly']);
unset($_SESSION['l4_pom']);
?>

<div id="imie">
    <div id="text">
    <?php
        echo "</br> Witaj ".$_SESSION['Name']." ".$_SESSION['Specjalizacja'];
        echo "<br/> <br/>";
    ?>
    </div>
</div>


<a href="powiadomienia_lekarz.php"> <button  id="block">Powiadomienia
<?php
if($ile>0)
    echo "(".$ile." nowe)";
?>
</button> </a>  
<a href="kalendarz_lekarz.php"> <button id="block2">kalendarz</button> </a> 
<a href="urlop.php"> <button id="block2">Podanie o urlop</button> </a> 
<a href="pacjenci2.php"> <button id="block2">Pacjenci</button> </a> 
<a href="logout.php"> <button id="block3">Wyloguj!</button> </a> 
</body>
</html> 


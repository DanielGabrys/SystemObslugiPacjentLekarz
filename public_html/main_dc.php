<?php
session_start();
if(!isset($_SESSION['zalogowany2']))
{
header('location:zaloguj_doctor_layout.php');
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>System obslugi dokkora</title>
</head>

<body>
<?php
unset($_SESSION['licznik']);
unset($_SESSION['urlop']);
unset($_SESSION['urlop_pozostaly']);
echo "Witaj ".$_SESSION['Name']." ".$_SESSION['Specjalizacja'];

echo "<br/> <br/>";

?>
<a href="kalendarz_lekarz.php"> <button type="button">kalendarz</button> </a> </br>
<a href="urlop.php"> <button type="button">Podanie o urlop</button> </a> </br> 
<a href="pacjenci.php"> <button type="button">Pacjenci</button> </a> </br> </br>
<a href="logout.php"> <button type="button">Wyloguj!</button> </a> </br> 
</body>
</html> 

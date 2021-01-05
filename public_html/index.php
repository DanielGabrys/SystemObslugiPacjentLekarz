<?php
session_start();
if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
{
header('Location:main_pacjent.php');
exit();
}

if((isset($_SESSION['zalogowany2'])) && ($_SESSION['zalogowany2']==true))
{
header('Location:main_dc.php');
exit;
}
?>

<style>
.wybor
{
  float:left;
  text-align:center;
}

.div1
{
  margin:auto;   
  width:800px;
  height:500px;

}

h1
{
 text-align:center;
}

</style>

<!DOCTYPE html>
<html>
<head>
	<title>E-Porady</title>
</head>

<body>

<h1>Witamy w Systemie e-Porad </h1>

<div class="div1">
	<div class="wybor">
	JESTEM PACJENTEM </br>
  	<a href="zaloguj_pacjent_layout.php"> <img src="porady_grafika/pacjent.jpeg" 
		onmouseover="this.src='porady_grafika/pacjent2.jpeg'" 
		onmouseout="this.src='porady_grafika/pacjent.jpeg'" width="400" height="500" /> </a>
	</div>	

    	<div class="wybor">
	JESTEM LEKARZEM </br>
	<a href="zaloguj_doctor_layout.php"> <img src="porady_grafika/stetoskop.jpeg" 
		onmouseover="this.src='porady_grafika/stetoskop2.jpeg'" 
		onmouseout="this.src='porady_grafika/stetoskop.jpeg'" width="400" height="500" /> </a>
		
	</div>
</div>



</body>
</html> 

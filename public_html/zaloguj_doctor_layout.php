<?php
	session_start();
	if((isset($_SESSION['zalogowany2'])) && ($_SESSION['zalogowany2']==true))
	header('Location:main_dc.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>E-Porady</title>
</head>

<body>


<h2>Jestem lekarzem </h2>
<form action="zaloguj_doctor.php" method="post">
	
	Login: <br/> <input type="text" name="login_doctor"/> <br/>
 	Hasło: <br/> <input type="password" name="haslo_doctor"/> <br/> <br/>
        <input type="submit" value= "Zaloguj sie jako doktor"/>
  	<br/><br/>
</form>

<a href="admin_layout.php"> <button type="button">Zarzadzaj</button> </a> </br>

<?php
if(isset($_SESSION['blad']))
	echo $_SESSION['blad'];
?>





</body>
</html> 

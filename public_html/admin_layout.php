<?php
	session_start();
	//if((isset($_SESSION['zalogowany2'])) && ($_SESSION['zalogowany2']==true))
	//header('Location:main_dc.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>E-Porady</title>
</head>

<body>


<h2>Zaloguj jako admin </h2>
<form action="zaloguj_admin.php" method="post">
	
	Login: <br/> <input type="text" name="login_ad"/> <br/>
 	Hasło: <br/> <input type="password" name="haslo_ad"/> <br/> <br/>
        <input type="submit" value= "Zaloguj sie"/>
  	<br/><br/>
</form>

<?php
if(isset($_SESSION['blad']))
	echo $_SESSION['blad'];
?>





</body>
</html> 

<?php
session_start();
if(!isset($_SESSION['zalogowany2']))
{
header('location:index.php');
}
unset($_SESSION['Spec']);
unset($_SESSION['d2']);
unset($_SESSION['lek_rem']);
unset($_SESSION['lek_rem2']);
unset($_SESSION['lekarz_imie']);
unset($_SESSION['lekarz_id']);


?>

<!DOCTYPE html>
<html>
<head>
	<title>System ubslugi pacjenta</title>
</head>

<body>
<a href="main_dc.php"> <button name="wroc">Wroc</button> </a> </br> 

<form action="kalendarz_urlop.php" method="post">
    
    <input id="l4" type="submit" name="l4" value="L4">
    <input id="ur" type="submit" name="urlop" value="URLOP">

</form>

<div id="block3">
Dostepnie do wziecia z dnia na dzien. </br>
Zaświadzczenie lekarskie należy przesłać na adres mailowy administratota.  
</div>



<div id="block4">
Aby wziąsć płatny urlop nalezy określić daty neobecności z conajmniej miesięcznym wyprzedzeniem. </br>
Istnieje okreslona ilość dni wolnych do wykorzystania. </br>
O chęci realizacji należy poinformować na adres mailowy administratota.  
    
</div>

 
</body>
</html> 

<style>

a
{
 text-decoration:none;   
}

#l4,#ur
{
    
    border: solid 1px;
    color: white;
    padding: 14px 28px;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    width: 30%;
    background-color: grey;
    link-decoration:none;
}

#l4
{
 float:left; 
}

#block3
{
   float:left; 
}

#block4
{
    float:left;
    border-left: 1px solid;
    border-color:white;
}

#block3,#block4
{
    width: 28%; 
    padding: 1% ;
    font-size: 16px;
    height: 180px;
    background-color:#ddd;
}

#l4:hover,#ur:hover
{
  background-color: #ddd;
  color: black;
  
}

</style
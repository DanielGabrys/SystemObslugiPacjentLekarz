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

<a href="kalendarz_urlop.php"> <button id="block">L4</button> </a> 
<a href="kalendarz_urlop.php"> <button id="block2">Urlop</button> </a> 

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

A {text-decoration: none;}

#block
{
 float:left; 
}

#block2,#block
{
    display: block;
    border: solid 1px;
    color: white;
    padding: 14px 28px;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    
    
}

#block2,#block
{
    width: 30%; 
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


#block2,#block
{
  background-color: grey;
}

#block2:hover,#block:hover
{
  background-color: #ddd;
  color: black;
}

</style
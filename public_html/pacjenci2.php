<?php

session_start();
require_once "conected.php";

$ID=$_SESSION['Id'];
$tab=NULL;
$choroby=NULL;
$przypisani=NULL;
$wszyscy=NULL;

//tablica asjocjacyjna statusow
$statusy[0]['status'] = "chory";
$statusy[1]['status'] = "wyleczony"; 

function tablica(&$tablica,$rezultat) //zapelniamy tablice danymi
{
  $licz=0; 
			
		while($row = mysqli_fetch_assoc($rezultat)) //specjalizacje
		{
			 
			$tablica[$licz]=$row;
			$licz++;
			  
		}  
}

function wypisz($ile,$tab) //wypisanie wynikow wyszukiwania
{
    $i=0;
	?><div class="blok0"> </div> <?php
	while($i<$ile)
		{	
		?> <div class="blok"> <?php
		echo "Pesel: ".$tab[$i]['Pesel'].", "."Imie: ".$tab[$i]['Name']."</br></br>"
		?> <a href="pacjent_profil.php?value=<?php echo $tab[$i]['Id']?>"> <button>Podglad</button></a> 
		</div>
		<?php
		$i++;
		}
}

function requires(&$tab,$zapyt,$s,$u,$p,$d) //zapytanie do bazy danych
{
    $ile=0;
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
	    
		$con= new mysqli($s,$u,$p,$d);
		if($con->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
		    $rez=$con->query($zapyt);
    		if(!$rez) throw new Exception($con->error);
    		
            $ile=$rez->num_rows;
    		tablica($tab,$rez);
    		
    		$con->close();
		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}  
	return $ile;
}

function element($tab,$ile) //ciag ID przypisanych pacjentowdo lekarza
{
    $element="";
	$element2=',';	
	for($i=0;$i<$ile;$i++)
		{
			$element.=$tab[$i]['Id']; 
			if($i!=$ile-1 )
			    $element.=$element2;
		}
			
	   if($ile==0) //jezeli lekarz nie ma przypisanych pacjentow
	        $element=0; 
	        
	return $element;
}

function opcje($name,$wszyscy,$ile,$tab,$tab_name) //generuje pole wyboru opcji
{
    $opcja="0";
    if(isset($_POST[$name])) //jezeli nie wchodzimy pierwszy raz na biezaca strone
    { ?>
    <option value ="<?php echo $_POST[$name];?>" > <?php echo $_POST[$name];
    $opcja=$_POST[$name]; //zapamietanie aktualnej opcji
    } ?>
    
	<?php if($opcja!=$wszyscy){?> <option value ="<?php echo $wszyscy;?>" ><?php echo $wszyscy; 
	
	}
	for ($i=0;$i<$ile;$i++)
	    {   
	    if($opcja!=$tab[$i][$tab_name])
	    {
	    ?>
    	<option value ="<?php echo $tab[$i][$tab_name];?>"> <?php echo $tab[$i][$tab_name];?>
	    <?php
	    }
	    }   
}

///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////

//pacjenci przypisaci do danego lekarza(wyszukujemy ID pacjentow)
$zap1="SELECT customers.Name,customers.Pesel,customers.Id,lekarze.ID FROM pacjenci JOIN customers ON
customers.Id = pacjenci.cus_id JOIN lekarze ON lekarze.ID = pacjenci.lekarz_id WHERE lekarze.ID=".$ID." ORDER
BY Name";

$ile_el=requires($przypisani,$zap1,$servername,$username,$password,$database);
$element=element($przypisani,$ile_el); //(ID pacjentow jako ciag)

//dane osobowe pacjentow przypisanych do danego lekarza
$zap2="SELECT Name,Pesel,Id From customers WHERE Id IN (".$element.")";

$ile_wszyscy=requires($wszyscy,$zap2,$servername,$username,$password,$database);
     
//wszystkie choroby bez powtorzen   
$zap3="SELECT DISTINCT choroby.choroba FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id ORDER BY choroba";        
$ile_choroby=requires($choroby,$zap3,$servername,$username,$password,$database);

    

/////////////////////////////////////////////////////////////////////////////////////////////////////

//cialo wyszukiwarki
if(isset($_POST['pacjent']) )
{
    
    $ok=true;
    $all=0;
    
    $pacjent=$_POST['pacjent'];
    if($pacjent!='wszyscy')
      $fraza_p="customers.Name="."'".$pacjent."' "."AND";
    else
    {
      $fraza_p="";
      $all++;
    }
    
    $choroba=$_POST['choroba'];
    if($choroba!='wszystkie')
      $fraza_c="choroby.choroba="."'".$choroba."' "."AND";
    else
      {
          $fraza_c="";
          $all++;
      }
     
    $status=$_POST['status'];
    if($status!='wszystkie')
      $fraza_s="choroby_pacjenci.status="."'".$status."' "."AND";
    else
      {
          $fraza_s="";
          $all++;
      }
      
    if($pacjent=="wszyscy" && $choroba=="wszystkie" && $status=="wszystkie")
        $fraza0="AND";
    else
        $fraza0="WHERE";
    
    
    //serce wyszukiwarki
    	if($pacjent!='wszyscy' && $choroba=='wszystkie' && $status=='wszystkie')
    	{
            $zapytanie="SELECT Name,Pesel,Id FROM customers WHERE Name=".'"'.$pacjent.'"';
    	}
    	else
    	{
    		$zapytanie=""."SELECT customers.Name,customers.Pesel,customers.Id,choroby.choroba FROM choroby_pacjenci JOIN customers
    		ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id "."$fraza0 "
    		."$fraza_p ".""."$fraza_c "."$fraza_s "." customers.Id IN (".$element.")"."";  
    	}
    
    
    $ile_wynik=requires($tab,$zapytanie,$servername,$username,$password,$database);
    		    
    if($ile_wynik==0)       //nie znaleziono
    {
        $ok=false;
        $_SESSION['blad']="nie znaleziono";
    }
     
}
?>

<!DOCTYPE html>
<html>
<body>
    
<a href="main_dc.php"> <button > Powrót </button> </br> </a>
<h2>Pacjenci</h2>
<form method="post">

Pacjent:
<td> 
    <select name="pacjent">
    <?php opcje("pacjent","wszyscy",$ile_el,$wszyscy,"Name"); ?>
    </select>
</td>
Choroba:
<td> 
    <select name="choroba">
    <?php opcje("choroba","wszystkie",$ile_choroby,$choroby,"choroba");  ?>
	</select>
</td>
  
Status:
<td> 
    <select name="status">
    <?php opcje("status","wszystkie",count($statusy),$statusy,"status"); ?>
    </select>
</td>

</br> </br>
  
<input type="submit" name="Szukaj" value="SZUKAJ">
<input type="submit" name="wszyscy" value="Pokaz wszystkich pacjentow"> </br>
</form> </br>
<?php

///////////////// wypisywanie wynikow wyszukiwarki

if(isset($_SESSION['blad']) && !isset($_POST['wszyscy'])) //jezeli blad
	{	
		echo $_SESSION['blad'];
		unset($_SESSION['blad']);
	}

else if(isset($_POST['wszyscy']) || !isset($_POST['pacjent']) || $all==3) //jezeli kliknieto przycisk pokaz wszystkich pascjentow lub pierwsze odwiedze niestrony lub wszystko jest ustawione na wartosc wszystkie($all==3)
    {
	    if($ile_wszyscy>0)
	        unset($_SESSION['blad']);
		    
         wypisz($ile_wszyscy,$wszyscy);
    }
    
else
         wypisz($ile_wynik,$tab);	
?>

</body>
</html>


<style>
    .blok0, .blok
    {
    border-top: 3px solid black; 
    background:grey;
    display: block;
    width:50%;
    }
    
   .blok
   {
       border-left: 3px solid black;
       border-right: 3px solid black;
       border-bottom: 3px solid black;
   }
   
   .blok0
    {
    border-top: 3px solid black; 
    }
    
</style>

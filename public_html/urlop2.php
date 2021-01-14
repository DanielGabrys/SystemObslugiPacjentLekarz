<?php
session_start();

if(!isset($_SESSION['zalogowany2']))
{
    header('location:index.php');
}

if(!isset($_SESSION['licznik']))
{
	header('location:main_dc.php');
}  


$wizyty=NULL;
$tab0=NULL;
///funkcje 

function wypisz($tablica,$rozmiar) //zapelniamy tablece danymi
{
  for($i=0;$i<$rozmiar;$i++)
  {
      
          ?>
          <button class="block"> <?php echo $tablica[$i]."</br>";?> </button> 
          <?php
  }
}

function tociag($tablica,$rozmiar) //zapelniamy tablece danymi
{
  $ciag='"';
  for($i=0;$i<$rozmiar;$i++)
  {
      $ciag.=$tablica[$i];
      
      if($i!=$rozmiar-1)
        $ciag.='","';
          
  }
  $ciag.='"';
  
  return $ciag;
}

function tablica(&$tablica,$rezultat) //zapelniamy tablece danymi
{
  $licz=0; 
			
		while($row = mysqli_fetch_assoc($rezultat)) //specjalizacje
		{
			$tablica[$licz]=$row;
			$licz++;
		}  
}

///fukcje koniec


// dni wolne


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
		    
		//wszystkie wiadomosci
	    $id=$_SESSION['Id'];
		$rez=$con->query("SELECT * FROM powiadomienia_pacjent WHERE lekarz_id='$id'");
		if(!$rez) throw new Exception($con->error);
		
		$ile=$rez->num_rows;


		$con->close();

		}
	}

	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}
	
$wyraz=tociag($_SESSION['urlop'],$_SESSION['licznik']);	
	
///////////////////////////////////////////////////// po wsicniecu zapisz	
if(!isset($_POST['zatwierdz']))
    $_SESSION['pom']=0;

if(isset($_POST['zatwierdz']))
{
    $_SESSION['pom']++;
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
		    // zapisujemydni urlopudo bazy
		    for($i=0;$i<$_SESSION['licznik'];$i++)
			{
			    $id=$_SESSION['Id'];
			    $data=$_SESSION['urlop'][$i];
			    if(!isset($_SESSION['l4_pom']))
			        $rodzaj="urlop";
			    else
			        $rodzaj="L4";
				if($con->query("INSERT INTO urlopy VALUES(NULL,'$id', '$data','$rodzaj')"))
				{	
				
				}
				else
					throw new Exception($con->error);
				
			}
			
			// wizyt yw przeciagu urlopu
			    $rez2=$con->query("SELECT *FROM wizyty WHERE Lekarz_id='$id' AND Data in ($wyraz)");
				if(!$rez2) throw new Exception($con->error);
	        	$ile2=$rez2->num_rows;
	        	
	        	//echo '</br>'.$ile2.'</br>';
	        	tablica($wizyty,$rez2);
	        	
	        //aktualizujemy pule dni urlopu do wziecia dla lekarza
	        if(!isset($_SESSION['l4_pom'])) //tylko jezeli birzemy platny urlop
             {
	  	        $urlop= $_SESSION['urlop_pozostaly'];
	  	        if($con->query("update lekarze set urlop='$urlop' WHERE ID='$id'"))
				{
				    
				}
				else
					throw new Exception($con->error);
             }
	        

	        
	  	    //generujemy powiadomienie o dokonaniu wizyty
	  	    if($ile2>0)
	  	    {
	  	        for($i=0;$i<$ile2;$i++)
                  {      
                        
	  	                 $_SESSION['id_mail']=$id2;
	  	                $id=$wizyty[$i]['Lekarz_id'];
    					$wys=date("Y-m-d H:i:s");
    					$tresc="ODWOLANO WIZYTE"."\r".
    					'Data: '.$wizyty[$i]['Data']."\r".
    					"Czas: ".$wizyty[$i]['Czas']."\r".
    					"Lekarz: ".$_SESSION['Name']."\r"."\r".
    					
    					"Lekarz prowadzący będzie nieobecny w danym okresie"."\r".
    					"Prosimy o ustalenie nowych dat wizyt w późniejszym okresie lub "."\r".
    					"umowienie sie z innym lekarzem";
    					
    					if($con->query("INSERT INTO powiadomienia_pacjent VAlUES
    					(NULL, '$tresc','$id2','$id','$wys','nieprzeczytane','ODWOŁANO WIZYTE')"))
    					{
    					    
    					}
    					else
    				    	throw new Exception($con->error);
                  }	
    					    
	  	        //usuwamy wizyty ktore nie moga sie odbyć
	  	        for($i=0;$i<$ile2;$i++)
	  	        {
	  	                $id_wiz=$wizyty[$i]['ID'];
	  	    	        if($con->query("DELETE FROM wizyty WHERE ID = '$id_wiz'"))
    					{
    					    
    					}
    					else
    				    	throw new Exception($con->error);
	  	        }
	  	    
	  	        
	  	    }
	  	       
			    $con->close();
			    header('Location:main_pacjent.php');
			    require_once "mail.php"; //wysłanie maila do administratora, pacjenta



		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}

}	
 

//echo $wyraz;
?>	


<style>

A {text-decoration: none;
    
}


.block
{
    border: none;
    color:  white;
    display:block;
    padding: 14px 18px;
    font-size: 12px;
    cursor: pointer;
    text-align: center;
    background-color: grey;
    border: solid 2px;
    width: 90%;
}

#container,#container2
{
    
    
    width:25%;
    float:left;
    text-align: center;
    
}

#container2
{
    
    background-color: #ddd;
    width:30%;
    padding: 14px 18px;
}

</style


<!DOCTYPE html>
<html>
<head>
	<title>System ubslugi pacjenta</title>
</head>

<body>
<a href="kalendarz_urlop.php"> <button  type="button">Powrot </button> </a> </br></br>

<div id="container">
<?php
wypisz($_SESSION['urlop'],$_SESSION['licznik']);
?>
</div>

<div id="container2">

UWAGA: </br>

Podjęcie decyzji o wzięciu urlopu moze wziązać się z koniecznością </br>
przełozenia lub anulowania umowionych wizyt </br> </br>
Kliknij ZATWIERDZ aby zatwierdzic urlop </br>


<form action="" method="post">

<p>  <input type="submit" name="zatwierdz" value="ZATWIERDZ" /> </p>
 
</form>

</div>

</body>
</html> 




<?php
session_start();

$tab=NULL;  
$godziny_all=NULL;
$godziny_wolne=NULL;
$godziny_zajete=NULL;
$inna_wizyta;
$today = date('Y-m-d');
//echo $today;

$d=substr($_GET['d'],-2);
$d =(int)$d;
$m=substr(substr($_GET['d'],0,-3),-2);
$m =(int)$m;
$y=substr($_GET['d'],-0,-6);
$y =(int)$y;
$ok=checkdate($m,$d,$y);

////pobieramy id przez get
if(isset($_GET['d']) && $today<$_GET['d'] && isset($_SESSION['lekarz_imie']) && $ok==1)
{
    
    
    // gdy probujemy wpisac w link date gdzie zawiera sie urlop
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
		$dat= $_GET['d'];
		$id=$_SESSION['lekarz_id'];
		$rez0=$con->query("SELECT * FROM urlopy WHERE lekarz_id='$id' AND dzien='$dat'"); 
		if(!$rez0) throw new Exception($con->error);
		$ileu=$rez0->num_rows;
		
		if($ileu>0)
		{
		    header('Location:zaloguj_pacjent_layout.php');
            exit();
		}
		
         $con->close();
		}
	}

	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}
    
    
	$data = $_GET['d'];
	//$id2 = htmlentities($id);
	//echo $id;
	unset($_GET['value']);
}
else
{
header('Location:zaloguj_pacjent_layout.php');
exit();
}
//echo $d.'</br>';
//echo $m.'</br>';
//echo $y;
//echo $ok;
/// funkcje
function tablica(&$tablica,$rezultat) //zapelniamy tablice danymi
{
        $licz=0; 
			
		while($row = mysqli_fetch_assoc($rezultat)) //specjalizacje
		{
			$tablica[$licz]=$row;
			$licz++;
			  
		}  
}

function ptablica(&$tablica) //pokaz tablice
{
 for($i=0;$i<count($tablica);$i++)
    echo $tablica[$i]['Czas']."</br>";
echo '</br>';
}

function godziny_pracy(&$tablica,$start,$finish) //zapelniamy tablice danymi
{
  for($i=0;$i<$finish-$start;$i++)
  {
      if($start+$i<10)
        $k="0";
      else
        $k="";
      $k.=$i+$start;
      $k.=":00";
      $tablica[$i]=$k; // tworzymy godzine np 9:00
      //echo $k."</br>";
      
  }
}

function wypisz_godziny2($tablica) //zapelniamy tablice danymi
{
  $godziny_kal="";
  for($i=0;$i<count($tablica);$i++)
  {
      $godziny_kal.=$tablica[$i]['Czas'];
      if($i%2==1)
         $godziny_kal.="</br>";
      else
         $godziny_kal.=" ";
         
  }
  return $godziny_kal;
}

function porownaj($tab2,&$wynik) //zapelniamy tablice danymi
{
  $k=0;
  $i=8;
  $j=0;
  
  while($i<16)
  {
     // echo $i." ".$x." ".$j."</br>";
      if($tab2==NULL)
      {
        $wynik[$k]=$i.":00";
        $i++;
        $k++;
      }
      else if($j<count($tab2))
      {
        $x=substr($tab2[$j]['Czas'], 0, -6); 
        
            if($i<$x)
            {
                $godzina=$i.":00";
                $wynik[$k]=$godzina;
                
                $k++;
                $i++;
            }
            else
            {
                $j++;
                $i++;
            }
      }
      else
      {
          $godzina=$i.":00";
          $wynik[$k]=$godzina;

          $i++;
          $k++;
         
      }
      
  }
}

function inna($wizyta,$tab)
{
    $oks=1;
    for($i=0;$i<count($tab);$i++)
    {
        if($tab[$i]==$wizyta)
            $oks=0;
    }
    return $oks;
}
/// funkcje

godziny_pracy($godziny_all,8,16);

// baza danych
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
		$id=$_SESSION['lekarz_id'];
		//imie lekarza
		$rez0=$con->query("SELECT * FROM lekarze WHERE ID='$id'"); 
		if(!$rez0) throw new Exception($con->error);
		
		$wiersz=$rez0->fetch_assoc();
		$imie=$wiersz['Name'];
		

		$rez=$con->query("SELECT * FROM wizyty WHERE Lekarz_id='$id' AND Data='$data' order by Czas");  //harmonogram lekarza
		if(!$rez) throw new Exception($con->error);
		
		$ile=$rez->num_rows;
		
		if($ile>=8)
		    header('Location:zaloguj_pacjent_layout.php');
		if($ile!=0)
		    tablica($tab,$rez);
		
         $con->close();
		}
	}

	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}

porownaj($tab,$godziny_wolne);

$pom=0;

if(isset($_POST['godziny']))
{
    	//////////////////// sprawdzamy czypacjent nie da innej wizyty o tej godzinie
    	
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
        $t=$_POST['godziny'].":00";
        $t0=substr($t,0,-6);
        if($t0<10)
            $t="0".$t;
        $id2=$_SESSION['id'];
        //echo $t."</br>";
       // echo $id2."</br>";
        //echo $data."</br>";
		$rez2=$con->query("SELECT * FROM wizyty WHERE Pacjent_id='$id2' AND Data='$data' AND Czas='$t' order by Czas");  
		if(!$rez2) throw new Exception($con->error);
		
		$ile2=$rez2->num_rows;
		//echo $ile2;
		
		if($ile2==1)
		{
		    tablica($inna_wizyta,$rez2);
		}
     
           $con->close();
		}
	}

	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}
     
     ///////////////////////////////////////
        if($ile2==1)
        {
            //echo "</br>".$t." ".$inna_wizyta[0]['Czas']."</br>";
            if($t==$inna_wizyta[0]['Czas'])
            {
                $pom=1;
            }
        }
        if($pom==0)
        {
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
                    
                	$czas=$_POST['godziny'];
    				if($con->query("INSERT INTO wizyty VALUES(NULL, '$data', '$czas', '$id2', '$id')"))
    				{	
    				    //customer staje sie pacjentem
    				    if($con->query("INSERT INTO pacjenci VALUES(NULL, '$id2', '$id')"))
    				    {
    					//generujemy powiadomienie o dokonaniu wizyty
    					$wys=date("Y-m-d H:i:s");
    					$tresc="Umowiono na wizyte"."\r".
    					'Data: '.$data."\r".
    					"Czas: ".$czas."\r".
    					"Lekarz: ".$imie;
    					
    					//echo $wys."</br>";
    					//echo $tresc."</br>";
    					if($con->query("INSERT INTO powiadomienia_pacjent VAlUES
    					(NULL, '$tresc','$id2','$id','$wys','nieprzeczytane','NOWA WIZYTA')"))
    					{
    					    
    					}
    					else
    				    	throw new Exception($con->error);
    					
    					header('Location:main_pacjent.php');
    				    }
    				    else
    				    	throw new Exception($con->error);
    				}
    				else
    					throw new Exception($con->error);

			        $con->close();
        		}
	        }

        	catch(Exception $e)
        	{
        		echo '<span style="color:red;">Blad serwera </span>';
        		echo '<br/>'.$e;
        	}
        }
     
}


?>



<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Kalendarz</title>
    
    
     <a href="kalendarz2.php"> <button > Powrót </button> </a> </br> </br>
     
     
<form method="post">   
    <?php 
    echo $_SESSION['lekarz_imie']."</br></br>";
    echo $data."</br>";
    ?>
    Dostepne godziny: 
    
<td> 
  <select name="godziny"> 
  <?php
  
        for($i=0;$i<count($godziny_wolne);$i++)
        { 
         
            ?>
             <option value ="<?php echo $godziny_wolne[$i];?>">
            <?php  echo $godziny_wolne[$i];
        }  
    ?>
    </option>  
	</select>
</td> 
  </br>
  <input type="submit" name="umow" value="UMOW"> 
 </form>    
</head>
<body>
  
</body>
</html>

<?php
if($pom==1)
    echo '</br><span style="color:red;">Masz juz wizyte z innym lekarzem</span>';
?>

       
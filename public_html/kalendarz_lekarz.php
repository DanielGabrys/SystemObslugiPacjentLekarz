<?php
session_start();
$id=$_SESSION['Id'];
//funkcje


function sprawdz_czy_urlop($tablica,$r,$data) //zapelniamy tablice danymi
{
$k="0";
  for($i=0;$i<$r;$i++)
  {
      if($tablica[$i]['dzien']==$data)
      {
          if($tablica[$i]['rodzaj']=="urlop")
          {
              $k='URLOP';
          }
          else
          {
              $k='L4';
          }
      }
        
  }
  return $k;
}


function tablica(&$tablica,$rezultat) //zapelniamy tablice danymi
{
  $licz=0; 
			
		while($row = mysqli_fetch_assoc($rezultat)) //specjalizacje
		{
			 
			$tablica[$licz]=$row;
			$licz++;
			  
		}  
}

//funkcje

$tab=NULL;  //jesli nie bedzie wizyt tablica domyslnie pusta
$tab2=NULL; //jesli nie bedzie wizyt tablica domyslnie pusta
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
     
		//urlopy
    	$rez_u=$con->query("SELECT * FROM urlopy WHERE lekarz_id='$id' order by dzien");
		if(!$rez_u) throw new Exception($con->error);
		$ile_u=$rez_u->num_rows;
		tablica($urlopy,$rez_u); //wsadzamyurlopy do tablicy
        
	    
		$rez=$con->query("SELECT * FROM wizyty WHERE Lekarz_id='$id' ORDER BY Data,Czas");  //najblizsze wizyty dla danego pacjenta
		if(!$rez) throw new Exception($con->error);
		
		$ile=$rez->num_rows;
		
		$licz=0; 
			
			while($row = mysqli_fetch_assoc($rez))
			{
			   $tab[$licz]=$row;
			   $licz++;
			}
			
			/////////////////////////
			
			for($i=0; $i<$ile; $i++) //znajdujemy imie pacjenta o danym id
		    {
    		    $iden=$tab[$i]['Pacjent_id'];
    		    //echo $iden;
    		    $rez2=$con->query("SELECT * FROM customers WHERE ID='$iden'");  
    		    if(!$rez2) throw new Exception($con->error);
    		    
    		    $row=mysqli_fetch_assoc($rez2);
    		    $tab2[$i]=$row;
    		    //echo $tab2[$i]['Name'].$tab[$i]['ID'].'</br>';
	    	}
            $con->close();
		}
	}

	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}




//formula kalendarza


// Set your timezone
date_default_timezone_set('Europe/Warsaw');

// Get prev & next month
if (isset($_GET['ym']))
{
    $ym = $_GET['ym'];
} else {
    // This month
    $ym = date('Y-m');
}

// Check format
$timestamp = strtotime($ym . '-01');
if ($timestamp === false)
{
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}
//echo $timestamp."</br>";
// Today
$today = date('Y-m-d', time());
//echo $today."</br>";
//echo $tab[0];
// For H3 title
$html_title = date('Y / m', $timestamp);

// Create prev & next month link     mktime(hour,minute,second,month,day,year)
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)-1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)+1, 1, date('Y', $timestamp)));
// You can also use strtotime!
// $prev = date('Y-m', strtotime('-1 month', $timestamp));
// $next = date('Y-m', strtotime('+1 month', $timestamp));

// Number of days in the month
$day_count = date('t', $timestamp);
 
// 0:Sun 1:Mon 2:Tue ...
$str = date('w', mktime(0, 0, 0, date('m', $timestamp), 0, date('Y', $timestamp)));
//$str = date('w', $timestamp);


// Create Calendar!!
$weeks = array();
$week = '';

// Add empty cell
$week .= str_repeat('<td></td>', $str);

for ( $day = 1; $day <= $day_count; $day++, $str++)
{
    $dzisiaj="";
     
    if($day<10)
        $date = $ym . '-' ."0".$day;
    else
        $date = $ym . '-' . $day;
     
   //echo $date.'</br>';
     
    $i=0;
    $pom=0;
    $pom2=$ile;
    

   
    while($i<$pom2) //terminy aktualnych wizyt
    {
        if ($date == $tab[$i]['Data'] && $today<=$tab[$i]['Data']) //zlapanie dzisiejszej daty
        {
           
            $godzina=$tab[$i]['Czas'];
            $godzina=substr($godzina,0,-3);  //wycinamy sekundy
            $doktorek=$tab2[$i]['Name'];
            if($date!=$today) //jezeli dzis nie jest wizyta
            {
                
                if($i==0) //ostatni element tablicy
                {
                    $week .= '<td class="myvisit">' . $day."</br></br> ".$godzina." ".$doktorek;
                  
                    $pom=1;
                }
                else if($tab[$i]['Data']!=$tab[$i-1]['Data']) //reszta
                {
                    $week .= '<td class="myvisit">' . $day."</br></br> ".$godzina." ".$doktorek;
                }
                else
                {
                     $week .="</br>".$godzina." ".$doktorek;
                }
                $pom=1;
            }
            else
            {
                $dzisiaj=$tab[$i]['Data'];
                   if($i==0) //pierwszy element tablicy
                {
                     $week .= '<td class="today_visit">' . $day."</br>dzis wizyta </br></br>".$godzina." ".$doktorek;
                }
                else if($tab[$i]['Data']!=$tab[$i-1]['Data']) //reszta
                {
                    $week .= '<td class="today_visit">'. $day."</br>dzis wizyta </br></br>".$godzina." ".$doktorek;
                }
                else
                {
                     $week .="</br>".$godzina." ";
                }
                $pom=1;
            }
            
        } 
        
        $i++;
    }
    //
    if ($today == $date && $pom==0) //zlapanie dzisiejszej daty
    {
        
        $week .= '<td class="today">' . $day."</br> "."Dzis"; 
    } 
   
    else 
    {
        $rodzaj=sprawdz_czy_urlop($urlopy,$ile_u,$date);
        if($rodzaj!='0' && $pom==0)
        {
            
            $week .= '<td class="urlop">' . $day."</br> ".$rodzaj; 
        }
        else if($pom==0)
            $week .= '<td>' . $day;
    {
    
    }
    }
    $week .= '</td>';
     
    // End of the week OR End of the month
    if ($str % 7 == 6 || $day == $day_count) 
    {

        if ($day == $day_count) {
            // Add empty cell
            $week .= str_repeat('<td></td>', 6 - ($str % 7));
        }

        $weeks[] = '<tr>' . $week . '</tr>';

        // Prepare for new week
        $week = '';
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Kalendarz</title>
    
   
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
    
     <a href="main_pacjent.php"> <button > Powrót </button> </a>
    <style>
        .container
        {
            font-family: 'Noto Sans', sans-serif;
            margin-top: 20px;
            
        }
        h3
        {
            margin-bottom: 30px;
        }
        th
        {
            height: 30px;
            text-align: center;
        }
        td
        {
            height: 200px;
            width:300px;
        }
        .today
        {
            background: orange;
            width:300px;
        }
         .myvisit
        {
            background: #7FFF00;
            width:300px;
        }
        
          .today_visit
        {
            background: yellow;
            width:300px;
            
        }
        
           .urlop
        {
            background: grey;
            width:300px;
            
        }
        
        th:nth-of-type(7), td:nth-of-type(7) 
        {
            color: red;
        }
        th:nth-of-type(6), td:nth-of-type(6) 
        {
            color: blue;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3><a href="?ym=<?php echo $prev; ?>">&lt;</a> <?php echo $html_title; ?> <a href="?ym=<?php echo $next; ?>">&gt;</a></h3>
        <table class="table table-bordered">
            <tr>
                
                <th>PN</th>
                <th>WT</th>
                <th>SR</th>
                <th>CZW</th>
                <th>PT</th>
                <th>SB</th>
                <th>ND</th>
            </tr>
            <?php
                foreach ($weeks as $week) 
                {
                    echo $week;
                }
            ?>
        </table>
    </div>
</body>
</html>
<?php
session_start();
$id=$_SESSION['Id'];
$miesiace;
$naglowek=0;
unset($_SESSION['pom']);
if(!isset($_SESSION['licznik']))
{
    $_SESSION['licznik']=0;
    $_SESSION['urlop']=NULL;
}


//funkcje

function usun(&$tab,$index,$size)
{
    for($j=$index;$j<$size;$j++)
    {
        if($j!=$size-1)
            $tab[$j]=$tab[$j+1];
        else
             $tab[$j]=NULL;
    }
}

function wyszukaj($tab,$size,$data)
{
    for($j=0;$j<$size;$j++)
    {
        if($tab[$j]==$data)
            return 0;
        
    }
    return 1;
}

function miesiace(&$tab)
{
    $y=date("Y"); //sprawdzamy czy luty ma 29 dni czy 28
    if($y%4==0)
        $luty=29;
    else
        $luty=28;
        
    $tab[0]=31; //marzec
    $tab[1]=$luty;  //luty
    $tab[2]=31;   //marzec
    $tab[3]=30;   //kwiecien
    $tab[4]=31;   //maj
    $tab[5]=30;   //czerwiec
    $tab[6]=31;   //lipiec
    $tab[7]=31;   //sierpien
    $tab[8]=30;   //wrzesien
    $tab[9]=31 ;  //pazdziernik
    $tab[10]=30;  //listopad
    $tab[11]=31; //grudzien
}

function odstep($miesiace,$odstep,$data) //odstep w miesiacach
{
    $d=date("j"); 
    $m=date("n");// aktualny miesiac
    $y=date("Y");
    
    if($m==12) //jezeli grudzien zmieniamy na styczen
       $m=0;
     
    // co jesli mamy np 30 stycznia?, wtedy nastepna data powinna byc 28/29 lutegoa nie 30 lutego
    //zakladamy ze mozna brac urlop z miesiecznym wyprzedzeniem
    //nie potrzeba w takim wypadku liczyc grudnia
    
     //echo $m." ".$miesiace[$m-1]." ".$miesiace[$m-1+$odstep].'</br>';
     
    if( $m!=12 && $d > $miesiace[$m-1+$odstep])//$m-1-aktualny miesiac np dla m=6 odpowiada to tablicy o indexie 5
    {
        $d=$miesiace[$m-1+$odstep];
    }
    
    $m+=$odstep;
    
    if($d<10)    
        $d="0".$d;
    if($m<10)    
        $m="0".$m;
        
    $dzien=$y.'-'.$m.'-'.$d;
    
    return $dzien;
}

function todata($data,$rest) //zmieniamy rok z postaci: 12/20 na np 06.12.20
{
  $month = substr($rest, -2); //20
  $year = substr($rest, 0, -5); //12
  if($data<10)
    $dej="0".$data; //06
  else
    $dej=$data; //12 itp
  
  return $year."-".$month."-".$dej; //06.12.20
}

function zaznacz($day,$tab,$r,&$week) //zmieniamy rok z postaci: 12/20 na np 06.12.20
{
 $k=0;
 $d2=substr($day,-2);
 $d3=substr($day,0,-3);


 if(substr($d2,0,1)==0)
    $d2=substr($d2,1);
    
  for($i=0;$i<$r;$i++)
  {
      if($tab[$i]==$day)
      {
            
            $week.= '<td class="zaznaczone"> <a href="kalendarz_urlop.php?ym='.$d3.'&d='.$d2.
            '"> <button class="block2">'.$d2; 
            $k++;
       
      }
  }
 return $k;
}

function wypisz($tab,$r) //zmieniamy rok z postaci: 12/20 na np 06.12.20
{
    for($i=0;$i<$r;$i++)
      {
         echo $tab[$i]." ";
      }
    
    echo '</br>';
}

function sprawdz_czy_urlop($tablica,$r,$data) //zapelniamy tablice danymi
{
  for($i=0;$i<$r;$i++)
  {
      if($tablica[$i]['dzien']==$data)
        return 0;
  }
  return 1;
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

////funkcje koniec




////pobieramy id przez get
if(isset($_GET['d']) && is_numeric($_GET['d']) && isset($_GET['ym']))
{
    $dzionek=$_GET['d'];
    if($dzionek<10)
    $dzionek="0".$dzionek;
    $ok=1;
    
    for($i=0;$i<$_SESSION['licznik'];$i++)
    {
       if( $_SESSION['urlop'][$i]==$_GET['ym']."-".$dzionek) 
       {
           usun($_SESSION['urlop'], $i, $_SESSION['licznik']);
           $_SESSION['urlop_pozostaly']++;
           $_SESSION['licznik']--;
           $ok=0;
           break;
       }
    }
    
    if($ok==1)
    {
        if($_SESSION['urlop_pozostaly']>0)
        {
            $_SESSION['urlop'][$_SESSION['licznik']]=$_GET['ym']."-".$dzionek;
            $_SESSION['licznik']++;
            
            $_SESSION['urlop_pozostaly']--;
            
        }
        else
            $naglowek=1;
        
    }
     //wypisz($_SESSION['urlop'],$_SESSION['licznik']);
    
}



miesiace($miesiace);
$wynik= odstep($miesiace,1,12);
//echo $wynik.'</br>';

//laczeniebaza danych

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
		tablica($urlopy,$rez_u); //wsadzamyurlopy do tablic
        
        
        // niewykorzystane dni urlopu lekarza
        if($_SESSION['licznik']==0)
        {
            $today = date("m.d");   
            if($today=="01.01") // na nowy rok nowedni urlopu
            {
                if($con->query("update lekarze set urlop=15 WHERE ID='$id'"))
				{
				    
				}
				else
					throw new Exception($con->error);
            }
            
    		$rez_u=$con->query("SELECT * FROM lekarze WHERE ID='$id'");  
    		if(!$rez_u) throw new Exception($con->error);
    		
    		
    		$row = mysqli_fetch_assoc($rez_u);
    		$_SESSION['urlop_pozostaly']=$row['urlop'];
        }
    			 

		
	    //najblizsze wizyty dla danego lekarza
	    
		$rez=$con->query("SELECT * FROM wizyty WHERE Lekarz_id='$id' ORDER BY Data,Czas");  
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
    		    $rez2=$con->query("SELECT * FROM customers WHERE ID='$iden'");  
    		    if(!$rez2) throw new Exception($con->error);
    		    
    		    $row=mysqli_fetch_assoc($rez2);
    		    $tab2[$i]=$row;
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
// For H3 title
$html_title = date('Y / m', $timestamp);

// Create prev & next month link     mktime(hour,minute,second,month,day,year)
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)-1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)+1, 1, date('Y', $timestamp)));


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
$day_pom=todata(1,$html_title);
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
    
    $day_pom=todata($day,$html_title);
    $day_pom2=substr($day_pom,0,-3);
   
   
    if(isset($_SESSION['licznik']))
    {
    $zm=zaznacz($date,$_SESSION['urlop'],$_SESSION['licznik'],$week); //zaznaczamy na czerwono
    if($zm>0)
        $pom=1;
    }
   
   
    while($i<$pom2) //terminy aktualnych wizyt
    {
        if ($date == $tab[$i]['Data'] && $today<=$tab[$i]['Data'] && $date>$wynik) //zlapanie dzisiejszej daty
        {
            $godzina=$tab[$i]['Czas'];
            $godzina=substr($godzina,0,-3);  //wycinamy sekundy
            $doktorek=$tab2[$i]['Name'];
            if($date!=$today) //jezeli dzis nie jest wizyta
            {
                
                $wysz=wyszukaj($_SESSION['urlop'],$_SESSION['licznik'],$date);
                if($wysz!=0)
                {
                if($i==0) //pierwszy
                {
                    $week .= '<td class="myvisit"> <a href=""> <button class="block">'. $day."</br></br> ".$godzina." ".$doktorek;

                    $pom=1;
                }
                else if($tab[$i]['Data']!=$tab[$i-1]['Data']) //reszta
                {
                    if(isset($_SESSION['licznik']))
                    $week .= '<td class="myvisit"><a href="kalendarz_urlop.php?ym='.$day_pom2.'&d='.$day.
                '"> <button class="block">'. $day."</br></br> ".$godzina." ".$doktorek;
                }
                else
                {
                     $week .="</br>".$godzina." ".$doktorek;
                }
                $pom=1;
                }
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
                     $week .="</br>".$godzina." ".$doktorek;
                }
                $pom=1;
            }
            
        } 
        
        $i++;
    }
    
    
    if ($today == $date && $pom==0) //zlapanie dzisiejszej daty
    {
        
        $week .= '<td class="today">' . $day."</br> "."Dzis"; 
    } 
   
    else 
    {
        if($pom==0 && $str%7==6)
            $week .= '<td>'. $day;
        elseif(sprawdz_czy_urlop($urlopy,$ile_u,$date)==0 && $pom==0)
        {
            $week .= '<td class="urlop">' . $day."</br> "."URLOP"; 
        }
        elseif($pom==0 && $date>$wynik)
        {
            $week .= '<td> <a href="kalendarz_urlop.php?ym='.$day_pom2.'&d='.$day.
                '"> <button class="block">'. $day;
               
        }
        else if($pom==0)
        {
            $week .= '<td>'.$day; 
        }
            
    }
    $week .= '</td></a>';
     
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
    
     <a href="main_pacjent.php"> <button > Powrót </button> </a></br>
     <a href="urlop2.php"> <button > DALEJ </button> </a>
   
</head>
<body>
    <h2>WYBIERZ DNI WOLNE</br> Do wykorzystania: <?php echo $_SESSION['urlop_pozostaly'] ?></h2>
    <?php
    if($naglowek==1)
    {?>
    <h4>Wyczerpano dostepne dni</h4>
    <?php
    } ?>
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

 <style>
    
    a {text-decoration: none;}
    a:hover {text-decoration: none;}
    
    .block, .block2
    {
    display: block;
    cursor: pointer;
    height: 200px;
    width:100%;
    border:none;
    }
    
    .block2
    {
        background-color:red;
    }

    .block:hover
    {
     background-color: red;
     color: black;
    }
    
    
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
        
        .zaznaczone
        {
            background: red;
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
        
        h2{text-align:center;}
        
        h4
        {
            text-align:center;
            color:red;
            
        }
    </style>
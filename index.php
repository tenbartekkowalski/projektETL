<?php
session_start(); 															//rozpoczęcie sesji umożliwiające pobranie wartości wysyłanych za pomocą funkcji $_POST oraz $_SESSION

	if(isset($_SESSION['ileopinii'])){										//sprawdzenie czy zmienna 'ileopinii' została wysłana by można było ją wyświetlić
$loadopinii= "Ilość pobranych opinii: ".$_SESSION['ileopinii'];				//jeśli tak, do nowej zmiennej $loadopinii zostaję dopisana ilość pobranych opinii
//$_SESSION['ileopinii1']=$_SESSION['ileopinii'];								//wysyłanie ilości opinii pod inną nazwą dla potrzeb działania transformacji danych
	}
else{
	$loadopinii="";															//jeśli nie została ustawiona zmienna - przypisujemy zmiennej pustą wartość
}
if(isset($_SESSION['load'])){												//analogicznie jak wyżej, sprawdzenie czy zmienna została ustawiona
	$check=$_SESSION['load'];
}
else{$check="";}


if(isset($_SESSION['nazwa'])){												//analogicznie jak wyżej, sprawdzenie czy zmienna została ustawiona
	$produkt=$_SESSION['nazwa'];
}
else
{$produkt="";}

if(isset($_SESSION['info'])){												//analogicznie jak wyżej, sprawdzenie czy zmienna została ustawiona
	$opis=$_SESSION['info'];
}
else{$opis="";}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>ETL</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
    Set height of the grid so .sidenav can be 100% (adjust if needed) */
    .row.content {height: 1500px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 676px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height: auto;} 
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav">
        <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href="clear.php">Aplikacja ETL</a></li>
	

	<form action="load.php" method="post">
	Kod produktu: <br/> <input type="list" name="product_code" /> <br/>
	<input type="submit" name="pelny" value="ETL"/>
	<br></br>
	<input type="submit" name="ekstrakt" value="E - Ekstraktuj Dane"/>
	<br></br>
	</form>	

	<form action="transform.php" method="post">
	<input type="submit" value="T - Transformuj wyniki"/>
	<br></br>
	</form>	
	
	<form action="logout2.php" method="post">
	<input type="submit" value="L - Załaduj kody do bazy"/>
	<br></br>
	</form>	
	<form action="connect.php" method="post">
	<input type="submit" value="Odczytaj z bazy"/>
	</form>	
	</div>
	
	
	
	<div class="col-sm-8">
      <hr>      
      <h3>Poniżej zostaną wyświetlone informacje na temat przebiegu procesu ETL:</h3>
	  <p><?php echo htmlspecialchars($loadopinii); ?></p>
	  <p><?php echo htmlspecialchars($check); ?></p>
	  <br>
	  <h2><?php echo htmlspecialchars($produkt); ?></h2>
	  <h3><?php echo htmlspecialchars($opis); ?></h3>
       
</div>
</div>
</div>

	  
</body>
</html>
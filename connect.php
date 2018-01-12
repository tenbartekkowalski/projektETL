<?php
session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>ETL</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
    .row.content {height: 1500px}
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
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
	<form Name="Lista" method="post" action="connect2.php">
	<div class="col-sm-8">
    <hr>
	<h2>Odczyt z bazy danych</h2>
    <select name="product_name">
    <option selected="selected">Wybierz produkt</option>lected">Wybierz produkt</option>
<?php
error_reporting(0);
$tabela="id4228932_ceneo";						//nazwa bazy danych do ktorej się łaczymy
$tabela1="ceneo";								//nazwa tabeli w bazie danych
$polaczenie = mysqli_connect('localhost', 'id4228932_user',"password", $tabela);	//nawiazanie polaczenia z baza danych
$polaczenie ->set_charset("utf8");				//ustawienia kodowania połączenia
$sql = 'SELECT DISTINCT opis1 FROM `ceneo`';	//zapytanie pobierające Unikalne wartości z pola opis1 czyli nazwy przedmiotów załadowanych do bazy danych
$polaczenie->query($sql);						//wykonanie powyższego zapytania
$test=$polaczenie->query($sql);					//zdublowane wykonanie połączenia ze względu na występujące błedy
    while($wpis = $test->fetch_assoc()) {		//funkcja pobierająca wiersze wyników jako tablicę
        echo "<option  value='". $wpis['opis1']."'>".$wpis['opis1']."</option>";	//wypisanie nazw pobranych przedmiotów z bazy jako element listy
    }
	mysqli_close($polaczenie);					//komenda zakończająca połaczenie z bazą
?>
	</select>
	<input type="submit" name="product" value="Wybierz">
	</form>
	<br></br>
	<form action="cleardatabase.php" method="post">
	<input type="submit" value="Wyczyść bazę danych"/>
	</form>
	<br></br>
	<br>
	<form action="clear.php" method="post">
	<input type="submit" value="Strona główna"/>
	</form>
	</div>
</div>

	</body>
</html>



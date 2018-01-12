<?php
session_start();
?>
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
<?php
error_reporting(0);
if (isset($_SESSION['pomocnicza'])){								//sprawdzenie czy została wykonana transforamcja danych											
$nazwa=$_SESSION['nazwa'];											//odebranie nazwy przedmiotu celem zaladowania do bazy
$ileopinii=$_SESSION['ileopinii'];									//pobrana ilosc opinii
$nowatabela= $_SESSION['tabelatransform'];							//pobranie wypełnionej tabeli gotowej do załadowania do bazy danych
$tabela="id4228932_ceneo";											//przypisanie zmiennej nazwy tabeli dla skrócenia komendy poniżej
$tabela1="ceneo";													
$opis=$_SESSION['info'];											//pobranie dodatkowego opisu produktu
$polaczenie = mysqli_connect('localhost', 'id4228932_user',"password", $tabela);	//nawiązanie połączenia z bazą  danych, pierwsza wartość to adres (localhost ponieważ baza jest na tym samym serwerze co strona), nazwa użytkownika, hasło dostępu oraz nazwa tabeli do której chcemy się dostać
$polaczenie ->set_charset("utf8");									//ustawienie połączenia z bazą w kodowaniu utf8 dzięki czemu załadowane zostaną polskie znaki
$liczniczek=0;
if (!$polaczenie) {													//na wypadek problemów połączenia z bazą zostaną wyświetlone kody błedów pomagające zdiagnozować problem
    die("Connection failed: " . mysqli_connect_error());
}
while ($liczniczek<=$ileopinii){									//pętla w której dodawane są po kolei kolejne wartości w odpowiednie miejsca w bazie danych
	$wpisuz=print_r($nowatabela[1][$liczniczek],true);				//uzytkownik
	$wpisre=print_r($nowatabela[2][$liczniczek],true);				//treść recenzji
	$wpisza=print_r($nowatabela[3][$liczniczek],true);				//zalety
	$wpiswa=print_r($nowatabela[4][$liczniczek],true);				//wady
	$wpisoc=print_r($nowatabela[5][$liczniczek],true);				//ocena
	$wpispo=print_r($nowatabela[6][$liczniczek],true);				//polecam/ nie polecam
	$wpisdata=print_r($nowatabela[7][$liczniczek],true);			//data komentarza
	$idop=print_r($nowatabela[8][$liczniczek],true);				//id opinii
	$plus=print_r($nowatabela[9][$liczniczek],true);				//przydatna opinia
	$minus=print_r($nowatabela[10][$liczniczek],true);				//nieprzydatna opinia
	$sql="INSERT INTO ceneo (idopinia,uzytkownik,recenzja,zalety,wady,ocena,polecenie,opis1,opis2,data,opiniaplus,opiniaminus)
	VALUES ($idop, '$wpisuz','$wpisre','$wpisza','$wpiswa','$wpisoc','$wpispo','$nazwa','$opis','$wpisdata','$plus','$minus')";	//treść zapytania sql które WSTAWIA W POLE XX WARTOŚĆ z odpowiedniej zmiennej
	$polaczenie->query($sql);										//wykonanie powyższego zapytania sql
	$liczniczek++;
}
	mysqli_close($polaczenie);										//komenda zakończające połaczenie z bazą danych
	unset($_SESSION['tabelatransform']);							
	unset($_SESSION['pomocnicza']);									//wyzerowanie zmiennych uzywanych przy ładowaniu do bazy dzięki czemu nie będzie możliwe wykoania go ponownie bez pobrania nowych danych
	$_SESSION['load']="Opinie załadowano do bazy!";					//komunikat któy będzie wyświetlony na stronie tytułowej
die("<script>location.href = 'https://bartektest.000webhostapp.com/index.php'</script>");	//polecenie przenoszące na stronę główną
}
else{																//w przypadku braku potwierdznia wykonania transforamcji danych zostaje wypisany komunikat o konieczności wykonania poprzednich kroków
}
?>
<div class="col-sm-8">
      <hr>
      <h2>Aplikacja ETL</h2>
	  <h2><p><?php echo "Proszę wykonać poprzednie kroki" ?></p></h2>
	  <h2><p><?php echo "W kolejności:" ?></p></h2>
	  <h2><p><?php echo "Extract -> Transform -> Load" ?></p></h2>
      <br>
	  <br>
	  <form action="clear.php" method="post">
	<input type="submit" value="Strona główna"/>
	</form>

       
</div>
</body>
</html>

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
  
<?php
error_reporting(0);
$tabela="id4228932_ceneo";
$tabela1="ceneo";
$polaczenie = mysqli_connect('localhost', 'id4228932_user',"password", $tabela);
$polaczenie ->set_charset("utf8");
	if(isset($_POST['product_name'])){				//spradzenie czy został wybrany przdmiot (a nie weszliśmy po prostu wchodząc na adress connect2.php
	$product_name=$_POST['product_name'];			//pobranie nazwy przedmiotu do zmiennej
	}
	$sql = "SELECT * FROM ceneo WHERE opis1='".$product_name."'";		//zapytanie pobierające Wszystkie wartości z tabeli ceneo dla danej Nazwy
	$sql2 = "SELECT COUNT(opis1) as total FROM ceneo WHERE opis1='".$product_name."'"; 	//zapytanie pobierające ilosc wartości z tabeli ceneo dla danej Nazwy
	$polaczenie->query($sql);											//dwie komendy wykoannia powyzszego zapytania
	$polaczenie2=$polaczenie->query($sql2);								//stworzenie nowego połaczenia które wykona inne polecenie SQL
	$test=$polaczenie->query($sql);
	while($wpis2 = $polaczenie2->fetch_assoc()) {
       $ilosc=$wpis2['total'];											//odczytana ilosc opinii jest zapisywana jako total i musi tez byc jako taka odczytana
    }
	?>
	<div class="col-sm-8">
	<form action="connectcsv.php" method="post">
	<input type="submit" value="Zapisz do pliku csv"/>
	</form>
      <hr>
	  <h2>Załadowano wyniki! </h2>
	  <h2><?php echo $ilosc; ?>  opinii</h2>
	  <h2><?php echo $_POST['product_name'];?></h2>
	<table border= "2" class="striped">
            <tr class="header">
                <td>ID opinii</td>
                <td>Użytkownik</td>
                <td>Recenzja</td>
				<td>Zalety</td>
				<td>Wady</td>
				<td>Ocena</td>
				<td>Polecenie</td>
				<td>Plus</td>
				<td>Minus</td>
				<td>Data Opinii</td>
            </tr>
            <?php													//wyżej zostały wypisana  nazwa wybranego przedmiotu oraz nazwy komórek opisujące tabelę
$polaczenie1 = mysqli_connect('localhost', 'id4228932_user',"password", $tabela);
$polaczenie1 ->set_charset("utf8");
$sql1 = "SELECT DISTINCT opis2 FROM ceneo WHERE opis1='".$product_name."'";	//wybranie pojedynczej wartości dodatkowego opisu
$polaczenie1->query($sql1);
$test1=$polaczenie1->query($sql1);
    while($wpis1 = $test1->fetch_assoc()) {
       $_SESSION['opisproduktu']=$wpis1['opis2'];
    }
if(isset($_SESSION['opisproduktu'])){
	echo $_SESSION['opisproduktu'];								//wypisanie pobranego dodatkowego opisu jeśli był ustwiony
}
mysqli_close($polaczenie1);
    while ($wpis = $test->fetch_assoc()){						//pętla wypisująca wszystkie wartości do tabeli
        echo "<tr>";
        echo "<td>".$wpis["idopinia"]."</td>";
        echo "<td>".$wpis["uzytkownik"]."</td>";
        echo "<td>".$wpis["recenzja"]."</td>";
        echo "<td>".$wpis["zalety"]."</td>";
        echo "<td>".$wpis["wady"]."</td>";
		echo "<td>".$wpis["ocena"]."</td>";
        echo "<td>".$wpis["polecenie"]."</td>";
		echo "<td>".$wpis["opiniaplus"]."</td>";
		echo "<td>".$wpis["opiniaminus"]."</td>";
		echo "<td>".$wpis["data"]."</td>";
        echo "</tr>";
    }
	unset ($_SESSION['opisproduktu']);
$_SESSION['product_name']=$product_name;				//wysłanie zmiennej nazwy produktu na potrzeby zapisu do pliku csv
?>
    </table>
		

	<br></br>
	
<footer class="container-fluid" style="color:red">
 <form action="clear.php" method="post">
<input type="submit" value="Strona główna"/>
</form>	
</footer>
</body>
</html>


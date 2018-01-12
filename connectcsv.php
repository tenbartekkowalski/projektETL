<?php
session_start();
$tabela="id4228932_ceneo";
$polaczenie = mysqli_connect('localhost', 'id4228932_user',"password", $tabela);
$product_name=$_SESSION['product_name'];												//pobranie nazwy produktu z poprzedniej strony
$select = "SELECT * FROM ceneo WHERE opis1='".$product_name."'";						//zapytanie pobierające wszystkie wartości dla pobranej nazwy przedmiotu
header("Content-type: text/csv; charset=utf-8" );										//ustalenie że plik wyjściowy to plik tekstowy csv w kodowaniiu utf8
header("Content-Disposition: attachment; filename=data.csv");							//plik zostanie rozpoznany jako załącznik pod nazwą data.csv
$zapis = fopen("php://output","w");														//otwiera plik do zapisu, wartość W oznacza że tworzy nowy plik
fputcsv($zapis, array('idopinia','uzytkownik','recenzja','zalety','wady','ocena','polecenie','nazwa','opis','data','plus','minus'));	//zapisanie do pliku ($zapis) tabeli z nazwami atrybutów dla opisu pobranych danych
$wynik=mysqli_query($polaczenie, $select);												//wykonanie zapytania sql czyli pobrania wszystkich opinii wraz ze wszystkimi detalami 
while($wpis=mysqli_fetch_assoc($wynik)){
	fputcsv($zapis,$wpis);																//zapis pobranych danych do pliku
	}
fclose($zapis);																			//zamknięcie zapisywania pliku
?>




<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
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
ini_set('max_execution_time', 80); 														//ustawienie maksymalnego czasu wykonywania skryptu php - 80 sekund ponieważ tyle maksymalnie obsługuje używany przez nas hosting		
unset($_SESSION['pomocnicza']);															//funkcja "uwalniająca" zmienną służacą do sprawdzenia czy 
$ilosckodow=1;																			//ustawienie licznika na 1
$adres="";																				//utworzenie/wyczyszczenie zmiennej $adres używanej do połaczenia ze stronu wybranego przedmiotu
if(!empty($_POST['product_code'])){														//warunek sprawdzający czy został wysłany kod produktu który jest jednocześnie częścią adresu potrzebnego do pobrania opinii z Ceneo
$adres=$_POST['product_code'];															//przypisanie przesłanego numeru produktu do zmniennej $adres
$html = file_get_contents('https://www.ceneo.pl/'."$adres");							// funkcja php file_get_contents łączy się do wskazanego adresu czyli ceneo.pl + numer produktu
$licznik=1;																				//ustawienie licznika na 1
$ekstrakt='';								
$ekstrakt1='';
$nazwa='';																				//blok w którym tworzymy/czyścimy zmienne które będą użyte w dalszej części pliku 
preg_match_all('/<span itemprop="reviewCount">(.*?)<\/span>/is', $html, $matches);		//funkcja która pobiera dane (.*?) - wszstkie znaki spomiędzy znaczników <span itemprop="reviewCount"></span>, ze zmiennej $html (w tym przypadku jest to strona produktu i wstawia wartość do zmiennej $matches
foreach ($matches[1] as $node) {														//pętla która dla kążdej znalezionej wartości - w tym przypadku jest to ilość opinii na temat danego produktu
	$iloscopinii=$node;																	//przypisanie do zmiennej $iloscopinii pobranej ilości opinii
}
$dziel=floor($iloscopinii/10); 															//zmienna pomocnicza wskazująca ile stron opinii należy przeszukać - 10 opinii na pojedyncza stronę
	if($dziel>20)
	{$dziel-20;}
while ($licznik <= $dziel+1) 															//pętla wykonująca przeszukanie każdej kolejnej strony opinii produktu
{
	$html = file_get_contents('https://www.ceneo.pl/'."$adres".'/opinie-'.$licznik.'');	//adres każdej strony z opinią
	preg_match_all('/<div class="reviewer-cell"(.*?)div class="product-review-toolbar">/s', $html, $matches);	//przeszukanie strony w celu znalezienia bloku komentarza
		foreach ($matches[1] as $node) {												//pętla która kążdy kolejny znaleziony blok opinii przypisuje jako zmienna $node w postaci wycinka kodu ze strony html 
			$wpis=$node;																//zastosowanie pomocniczej zmiennej $wpis dla wprowadzania danych do zmiennej $ekstrakt
			$ekstrakt.=$wpis;															// .= [powoduje dodanie wartości zmiennej $wpis do zmiennej $ekstrakt
			$ilosckodow++;																// zwiekszanie licznika zliczającego ilość opiniii na temat tego produktu
		}
	$licznik++;																			//zwiększanie licznika przeszukiwanej strony
	}
preg_match('/<div class="ProductSublineTags">(.*?)<\/div>/s', $html, $matches1);		//przeszukiwanie strony w poszukiwaniu ddoatkowych informacji o produkcie - detale
preg_match('/<title>(.*?)<\/title>/s', $html, $matches2);								//przeszukiwanie strony w poszukiwaniu tytułu danego przedmiotu
$ekstrakt1=print_r($matches1[1],true);													//przypisanie do zmiennej ekstrakt1 opisu przedmiotu - funkcja print_r pozwala zapisać tabelę(array) jako tekst
$nazwa=print_r($matches2[1],true);														//przypisanie do zmiennej $nazwa nazwy produktu
$nazwa=substr($nazwa, 0, -36);															//funkcja służąca usunięciu 36 znaków od końca nazwy z racji tego, że ceneo dodaje do nazwy " - Opinie i ceny na Ceneo.pl"
$_SESSION['nazwa'] =$nazwa;	
$_SESSION['info'] =$ekstrakt1;
$_SESSION['ekstrakt'] = $ekstrakt;
$_SESSION['ileopinii'] = $iloscopinii;													//wysłanie nazwy produktu, opisu, ilosci opinii do wykorzystania na kolejnych stronach
if (isset($_POST['pelny'])) {															//sprawdzenie czy cykl powinien zostać w pełni automatycznie
	$skrypt=3;																			//zmienna pomocnicza pozwalająca określić w kolejnym pliku czy proces zostanie wykonany w całości czy pojedynczo
	$_SESSION['skrypt']=$skrypt;														//wysłanie ww. zmiennej do kolejnego pliku
	die("<script>location.href = 'https://bartektest.000webhostapp.com/transform.php'</script>");	//funkcja służąca do przejścia na kolejną strone - transforumującą wyniki do formy nadającej się do użycia
	}
else if (isset($_POST['ekstrakt'])) {													// warunek sprawdzający czy skrypt będzie wykonywany ręcznie krok po kroku
	$skrypt=1;																			//zmienna pomocnicza pozwalająca określić w kolejnym pliku czy proces zostanie wykonany w całości czy pojedynczo
	$_SESSION['skrypt']=$skrypt;														//wysłanie ww. zmiennej do kolejnego pliku
	$true="Załadowano ".($iloscopinii)." opinii.";										//Przypisanie do zmiennej ilości opinii służacej do poinformowania użytkownika ile opinii zostało pobranych
	}
}
else{
	$false="Proszę wpisać kod produktu!";												//w przypadku nie przesłania kodu produktu - komunikat o konieczności podania kodu produktu
}																						//ponizsza instrukcja służy sprawdzeniu czy kod produktu został przesłany, jeśli tak - wypisanie ilości opinii, jeśli nie - komunikat o koniecznosci wpisania kodu produktu
?>																			
<div class="col-sm-8">
      <hr>
      <h2>Aplikacja ETL</h2>
	  <h2><p><?php if (!empty($_POST['product_code'])) { echo $true;} else{echo $false;} ?></p></h2>
     <br>
	 <br>
	 <form action="index.php" method="post">
	 <input type="submit" value="Strona główna"/>
	 </form> 
</div>
</body>
</html>
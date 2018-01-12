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
if (isset($_SESSION['skrypt'])){									//spradzenie czy został wykonany skrypt pobrania danych
$transform= $_SESSION['ekstrakt'];									//przypisanie do nowej zmiennej $transform wszystkich pobranych opinii w porzpedniej stronie
$ile=$_SESSION['ileopinii'];										//pobranie ilosci opinii przeslanych ze strony index.php w celu przeslania ich do kolejnej strony
$test=$_SESSION['skrypt'];											//zmienna służaca do sprawdzenia czy wykoanny zostanie cały skrpt czy tylko jeden krok
$html=$transform;													//przypisanie wyciągniętych opinii do kolejnej zmiennej ze względów bezpieczeństwa
$tabelatransform= array												//utworzenie tabeli w której będą składowane wyniki po przeprowadzeniu transformacji/oczyszczania danych
(
array(""), 	//0 id number
array(""), 	//1 uzytkownik
array(""), 	//2 recenzja
array(""), 	//3 zalety
array(""),	//4 wady
array(""), 	//5 ocena
array(""), 	//6 polecenie
array(""), 	//7 data komentarza
array("") 	//8 id komentarza
);
$pattern=('/<div class="reviewer-name-line">(.*?)<\/div>/is');		//wyszukanie nazwy użtkownika komentarza
$pattern1=('/<span class="review-score-count">(.*?)<\/span>/is');   //ocena
$pattern2=('/<p class="product-review-body">(.*?)<\/p>/is');		//tresc opinii
$pattern3=('/<div class="pros-cell">(.*?)<\/div>/is');				//wypisane zalety
$pattern4=('/div class="cons-cell">(.*?)<\/div>/is');				//wypisane wady
$pattern5=('/<span id="(.*?)<\/span>/is'); 							//unikalne id opinii
$pattern6=('/<div class="product-review-summary">(.*?)<\/div>/is'); //Polecam/nie polecam
$pattern7=('/<time datetime="(.*?)<\/time>/is');					// data wystawienia
$pattern8=('/<span id="votes-yes-(.*?)<\/span>/is');				//ilość osób która uznała opinię za przydatną
$pattern9=('/<span id="votes-no-(.*?)<\/span>/is');					//ilość osób która uznała opinię za nieprzydatną
										//poniżej wykonanie poleceń mających na celu selekcję i oddzielenie poszczególnych danych ze wszystkich pobranych opinii: 
preg_match_all($pattern, $html, $m); 	// m - uzytkownik
preg_match_all($pattern1, $html, $m1); 	//m1 - ocena
preg_match_all($pattern2, $html, $m2); 	//m2 - recenzja
preg_match_all($pattern3, $html, $m3); 	//m3 - zalety
preg_match_all($pattern4, $html, $m4); 	//m4 - wady
preg_match_all($pattern5, $html, $m5); 	//m5 - id komentarza
preg_match_all($pattern6, $html, $m6); 	//m6 - polecenie
preg_match_all($pattern7, $html, $m7); 	//m7 - data komentarza
preg_match_all($pattern8, $html, $m8); 	//m8 - opinia na plus
preg_match_all($pattern9, $html, $m9); 	//m9 - opinia na minus
$licznik=1;															//ustawienie licznika pętli na 1
foreach ($m8[1] as $node ) {										//pętla wpisująca ilość osób która uznała opinię za przydatną do tabeli tabelatransform
	$wpis=$node;													//przypisanie zmiennej wpis wartości wyszukanej wartosci
	$wpis=strip_tags($wpis);										//oczyszczenie z tagów HTML
	$wpis=ltrim($wpis);												//wycięcię pustych znaków (spacji) znajdujących się przed pobrana wartością
	$wpis=substr($wpis,9);											//usunięcie początkowych 9 niepotrzebnych znaków które zostały pobrane z opinii
	$tabelatransform[9][$licznik]=rtrim(strip_tags($wpis));			//wpisanie oczyszczonej wartości do tabeli w odpowiednie miejsce wraz z dodatkowym pozbyciem się tagów i usunięciem pustych znaków za pobrana wartością
	$licznik++;														//zwiększenie licznika pętlio 1
	} 
$licznik=1;															//ustawienie licznika na 1
foreach ($m9[1] as $node ) {										//pętla wpisująca ilość osób która uznała opinię za nieprzydatną do tabeli tabelatransform
	$wpis=$node;													//przypisanie zmiennej wpis wartości wyszukanej wartosci
	$wpis=strip_tags($wpis);										//oczyszczenie z tagów HTML
	$wpis=ltrim($wpis);												//wycięcię pustych znaków (spacji) znajdujących się przed pobrana wartością
	$wpis=substr($wpis,9);											//usunięcie początkowych 9 niepotrzebnych znaków które zostały pobrane z opinii
	$tabelatransform[10][$licznik]=rtrim(strip_tags($wpis));		//wpisanie oczyszczonej wartości do tabeli w odpowiednie miejsce wraz z dodatkowym pozbyciem się tagów i usunięciem pustych znaków za pobrana wartością
	$licznik++;														
	} 
$licznik=1;															
foreach ($m[1] as $node ) {											//pętla wpisująca nazwy użytkowników do tabeli tabelatransform	
	$wpis=$node;													//przypisanie zmiennej wpis wartości wyszukanej wartosci		
	$wpis=strip_tags($wpis);										
	$wpis=ltrim($wpis);												
	$tabelatransform[1][$licznik]=(strip_tags($wpis));				//wpisanie oczyszczonej wartości do tabeli w odpowiednie miejsce wraz z dodatkowym pozbyciem się tagów 
	$licznik++;
	} 
	$licznik=1;
foreach ($m7[1] as $node ) {										//pętla wpisująca nazwy użytkowników do tabeli tabelatransform
//data komentarza
	$wpis=$node;													//przypisanie zmiennej wpis wartości wyszukanej wartosci
	$wpis=strip_tags($wpis);										//oczyszczenie z tagów HTML
	$wpis=ltrim($wpis);												//wycięcię pustych znaków (spacji) znajdujących się przed  pobrana wartością
	$wpis=substr($wpis,0,19);										//usunięcie początkowych 19 niepotrzebnych znaków które zostały pobrane z opinii
	$tabelatransform[7][$licznik]=rtrim(strip_tags($wpis));			//wpisanie oczyszczonej wartości do tabeli w odpowiednie miejsce wraz z dodatkowym pozbyciem się tagów i usunięciem pustych znaków za pobrana wartością
	$licznik++;
	} 
	$licznik=1;
foreach ($m2[1] as $node ) {										//pętla wpisująca treść opinii do tabeli tabelatransform
	$wpis=$node;													//przypisanie zmiennej wpis wartości wyszukanej wartosci
	$tabelatransform[2][$licznik]=strip_tags($wpis);				//wpisanie oczyszczonej wartości do tabeli w odpowiednie miejsce wraz z dodatkowym pozbyciem się tagów
	$licznik++;
	} 
	$licznik=1;
foreach ($m3[1] as $node ) {										//pętla wpisująca zalety do tabeli tabelatransform
	$wpis=$node;													//przypisanie zmiennej wpis wartości wyszukanej wartosci
	$wpis=strip_tags($wpis);										//oczyszczenie z tagów HTML
	$wpis=ltrim($wpis);												//wycięcię pustych znaków (spacji) znajdujących się przed pobrana wartością
	$wpis=substr($wpis,6);											//usunięcie początkowych 6 niepotrzebnych znaków które zostały pobrane z opinii
	$wpis=rtrim($wpis);												//wycięcię pustych znaków (spacji) znajdujących się za pobrana wartością
	$tabelatransform[3][$licznik]=ltrim(strip_tags($wpis));			//wpisanie oczyszczonej wartości do tabeli w odpowiednie miejsce wraz z dodatkowym pozbyciem się tagów i usunięciem pustych znaków przed pobrana wartością
	$licznik++;
	} 
	$licznik=1;
foreach ($m4[1] as $node ) {										//pętla wpisująca wady do tabeli tabelatransform
	$wpis=$node;													//przypisanie zmiennej wpis wartości wyszukanej wartosci
	$wpis=strip_tags($wpis);										//oczyszczenie z tagów HTML
	$wpis=ltrim($wpis);												//wycięcię pustych znaków (spacji) znajdujących się przed pobrana wartością
	$wpis=substr($wpis,4);											//usunięcie początkowych 4 niepotrzebnych znaków które zostały pobrane z opinii
	$wpis=rtrim($wpis);												//wycięcię pustych znaków (spacji) znajdujących się za pobrana wartością
	$tabelatransform[4][$licznik]=ltrim(strip_tags($wpis));			//wpisanie oczyszczonej wartości do tabeli w odpowiednie miejsce wraz z dodatkowym pozbyciem się tagów i usunięciem pustych znaków przed pobrana wartością
	$licznik++;
	} 
	$licznik=1;
foreach ($m1[1] as $node ) {										//pętla wpisująca ocenę użytkowników do tabeli tabelatransform
	$wpis=$node;													//przypisanie zmiennej wpis wartości wyszukanej wartosci
	$tabelatransform[5][$licznik]=(strip_tags($wpis));				//wpisanie oczyszczonej wartości do tabeli w odpowiednie miejsce wraz z dodatkowym pozbyciem się tagów
	$licznik++;
	} 
$i=0;																//dodatkowy licznik na potrzeby kolejnej pętli
$licznik=1;
foreach ($m5[1] as $node ) {										//pętla wpisująca unikalne id komentarza do tabeli tabelatransform
	$wpis=$node;													//przypisanie zmiennej wpis wartości wyszukanej wartosci
	$wpis=preg_replace('/[^0-9.]+/', '', $wpis);					//funkcja zostawiająca tylko liczby
	$wpis=substr($wpis, 0, -1); 									//usunięcie nadmiarowy znak z końca wartości
	++$i;															
    if($i==1){														//pętla warunkowa sprawdzająca który raz występuje to samo ID komentarza
		$tabelatransform[8][$licznik]=(strip_tags($wpis));			//wpisanie oczyszczonej wartości do tabeli w odpowiednie miejsce wraz z dodatkowym pozbyciem się tagów
		$licznik++;
    }
		if($i==2){													//w przypadku kolejnego wystąpienie wartość nie jest przypisywana
        $i=0;
		}
	} 
$licznik=1;
foreach ($m6[1] as $node ) {										//pętla wpisująca polecam/nie polecam do tabeli tabelatransform
	$wpis=$node;													//przypisanie zmiennej wpis wartości wyszukanej wartosci
	$wpis=strip_tags($wpis);										//oczyszczenie z tagów HTML
	$wpis=rtrim($wpis);												//wycięcię pustych znaków (spacji) znajdujących się za pobrana wartością
	$tabelatransform[6][$licznik]=ltrim(strip_tags($wpis));			//wpisanie oczyszczonej wartości do tabeli w odpowiednie miejsce wraz z dodatkowym pozbyciem się tagów i usunięciem pustych znaków przed pobrana wartością
	$licznik++;
	} 
$ile=$_SESSION['ileopinii'];										//pobranie ilosci opinii do nowej zmiennej
$_SESSION['tabelatransform'] = $tabelatransform;					//wysłanie wypełnionej tabeli do użycia na kolejnej strony celem dodania do bazy danych
$_SESSION['ileopinii2']=$ile;										//wysłanie ilości opinii do użycia na kolejnej strony
$_SESSION['pomocnicza']=7;											//przypisanie byle jakiej wartości celem sprawdzenia czy transformacja została wykonana w całości
$_SESSION['transform']="Transformacja wykonana!";					//komunikat który zostanie odebrany przez strone index.php
unset($_SESSION['ekstrakt']);										//wyzerowanie zmiennej ekstrakt aby nie mozna bylo wykonac kolejny raz transformacji bez podania nowego produktu
//unset($_SESSION['skrypt']);											//jak wyżej - wyzerowanei zapobiegnie możliwości powtórnego przejścia bez nowych danych
if ($test>2) {														//sprwadzenie czy wykoywany jest caly proces automatycznie
	die("<script>location.href = 'https://bartektest.000webhostapp.com/logout2.php'</script>");	//funkcja przełączająca do strony logout2.php służącej wpisywaniu tabeli do bazy danych
}
else {
	$true="Dane oczyszczone - Transformacja udana";					//jeśli operacja wykonywana jest krok po kroku to przypisany komunikat o udanej transformacji danych
} 
}
else{
	$false="Operacja niemożliwa, zacznij od ekstrakcji danych!";	//komunikat w przypadku braku odebrania zmiennej skrypt która określa czy zostało wykonane pobranie danych (Extract)
}
?>
<div class="col-sm-8">
      <hr>
      <h2>Aplikacja ETL</h2>
      
	  <h2><p><?php if (isset($_SESSION['skrypt'])){echo $true; }else{echo $false;}  ?></p></h2>
      <br>  
	  <br>
	<form action="clear.php" method="post">
	<input type="submit" value="Strona główna"/>
	</form>
	</div>
</body>
</html>

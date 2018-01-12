<?php
session_start();
$polaczenie = mysqli_connect('localhost', 'id4228932_user',"password", "id4228932_ceneo"); 	//nawiązane połączenia z bazą danych	
$czyszczenie = $polaczenie->query( "TRUNCATE TABLE ceneo");									//zapytanie SQL usuwający zawartość tabeli
die("<script>location.href = 'https://bartektest.000webhostapp.com/connect.php'</script>");	//przekierowanie na stronę główną
?>

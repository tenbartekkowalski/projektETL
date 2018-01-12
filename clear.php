<?php
session_start();
session_unset();		//wyczyszczenie wszystkich zmiennych globalnych w sesji
die("<script>location.href = 'https://bartektest.000webhostapp.com/index.php'</script>");	//automatyczne odesłanie do strony głównej
?>

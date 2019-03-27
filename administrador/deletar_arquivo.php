<?php require_once("../configuracoes.php"); 
mysql_query("UPDATE $_POST[tabela] SET $_POST[coluna]='' WHERE codigo=$_POST[codigo]");

if(is_file($_POST['arquivo'])) {
	unlink($_POST['arquivo']);
} ?>
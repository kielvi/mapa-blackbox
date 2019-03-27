<?php //NOCACHE
@header('Expires: '. gmdate('D, d M Y H:i:s') .' GMT');
@header('Last Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
@header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
@header('Pragma: no-cache');
@header('Expires: 0');
@header('Content-Type: text/html; charset=ISO-8859-15');
require_once('../configuracoes.php');

$cod_cidade = intval($_REQUEST['cod_cidade']);
$cod_bairro = intval($_REQUEST['cod_bairro']);
$query = mysql_query("SELECT * FROM imoveis_bairros WHERE cod_cidade='{$cod_cidade}' ORDER BY bairro ASC");

print('<option value="">Selecione</option>' . PHP_EOL);
while($dados = mysql_fetch_object($query)) {
	echo '<option value="'.$dados->codigo.'" '.(isset($cod_bairro) ? ($dados->codigo==$cod_bairro ? "selected" : "") : "").'>'.utf8_decode($dados->bairro).'</option>';
} ?>
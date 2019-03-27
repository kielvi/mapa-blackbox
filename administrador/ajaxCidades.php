<?php //NOCACHE
@header('Expires: '. gmdate('D, d M Y H:i:s') .' GMT');
@header('Last Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
@header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
@header('Pragma: no-cache');
@header('Expires: 0');
@header('Content-Type: text/html; charset=ISO-8859-15');
require_once('../configuracoes.php');

$cod_estado = intval($_REQUEST['cod_estado']);
$cidade = intval($_REQUEST['cidade']);
$query_cidades = mysql_query("SELECT * FROM cidades WHERE cod_estado='{$cod_estado}' ORDER BY cidade ASC");

print('<option value="">Selecione</option>' . PHP_EOL);
while($dados = mysql_fetch_object($query_cidades)) {
	echo '<option value="'.$dados->codigo.'" '.(isset($cidade) ? ($dados->codigo==$cidade ? "selected" : "") : "").'>'.utf8_decode($dados->cidade).'</option>';
} ?>

<?php //@header("Content-type: text/javascript");


error_reporting(0);
header('Content-Type: application/json');
require_once("configuracoes.php");

$data = array();
$mapa = mysql_query("SELECT * FROM mapa ORDER BY titulo ASC");
try {
  $i=0; 
  while($row_mapa = mysql_fetch_object($mapa)){
  	
		$data[$i]["lat"] = $row_mapa->lat;
		$data[$i]["lng"] = $row_mapa->lng;
		$data[$i]["data"]["title"] = "$row_mapa->titulo";
		$data[$i]["data"]["subtitle"] = "$row_mapa->subtitulo";
		$data[$i]["data"]["address"] = "$row_mapa->endereco<br>$row_mapa->bairro<br>$row_mapa->cidade -- $row_mapa->estado";
/*
$row_mapa->numero
 */
		$data[$i]["data"]["images"] = array();
		$query_fotos = mysql_query("SELECT * FROM mapa_fotos WHERE cod_mapa='$row_mapa->codigo' ORDER BY ordem ASC");
		while($row_foto = mysql_fetch_object($query_fotos)) {
			$data[$i]["data"]["images"][] = $row_foto->foto;
		}
		$data[$i]["title"] = "$row_mapa->titulo, $row_mapa->subtitulo";

		$i++; 
	}
} catch (Exception $e) {
  var_dump($e);
}

function getInfowindowContent( $row_mapa, $App, $selectEmpreendimento ){
	
	ob_start();
	extract( (array) $row_mapa );
	include '_mapaJSON_template.php';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

echo json_encode($data); 
?>
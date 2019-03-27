<?php require_once("../configuracoes.php");

$query_andamento = mysql_query("SELECT * FROM empreendimentos_andamento WHERE cod_empreendimento='{$cod_empreendimento}' ORDER BY codigo DESC");
$row_andamento = mysql_fetch_object($query_andamento);

$output = array(
	'concluido'=>($row_andamento->concluido<>'' ? $row_andamento->concluido : 0), 
	'acabamentointerno'=>$row_andamento->acabamentointerno, 
	'revestimentoexterno'=>$row_andamento->revestimentoexterno, 
	'reboco'=>$row_andamento->reboco, 
	'alvenaria'=>$row_andamento->alvenaria, 
	'estrutural'=>$row_andamento->estrutural,
	'concretagem'=>$row_andamento->concretagem, 
	'blocos'=>$row_andamento->blocos, 
	'fundacao'=>$row_andamento->fundacao
); 

echo json_encode($output);

?>
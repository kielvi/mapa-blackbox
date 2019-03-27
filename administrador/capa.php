<?php require_once("sessao.php");

function saudacao() {
	$hora = date("H");
	switch($hora) {
		case ($hora<=12): $saudacao = "Bom dia"; break;
		case ($hora>=13 && $hora<18): $saudacao = "Boa tarde"; break;
		case ($hora>=18): $saudacao = "Boa noite"; break;
		default: $saudacao = "Ola"; break;
	}
 	return $saudacao;
}

$nome = explode(" ", $_SESSION['nomeUsuario']); ?>

<link rel="stylesheet" href="css/capa.css">

<script type="text/javascript">
	$(window).bind('load', function () {
		$("h1").addClass('h1H')
	})
</script>

<div class="container-fluid capa">
	<h1 data-letters="<?php echo saudacao()." ".$nome[0]; ?>!"><?php echo saudacao()." ".$nome[0]; ?>!</h1>
	<h5>ESCOLHA A OPÇÃO QUE DESEJA ADMINISTRAR NO MENU SUPERIOR ESQUERDO</h5>
</div>
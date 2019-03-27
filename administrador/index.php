<?php
 require_once("sessao.php"); 
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<title>Administrador de Conteúdos</title>
	</head>
	<body class="index">
		<div id="pagewrap" class="conteudo-all">
			<header>
				<button class="menu-button" id="open-button"> </button>
				<div class="menu-wrap"><?php require_once("menu.php"); ?></div>
				<div class="logout"><a href="logout.php"><i class="fa fa-user-times" aria-hidden="true"></i></a></div>
				<div class="usuario"><a href="adicionar_admin_usuarios.php?codigo=<?php echo $codigoUsuario; ?>&acao=editar&nomeDaPagina=admin_usuarios&tabela=1" target="paginaTarget"><figure><img src="<?php echo (is_file($usuario->foto) ? $usuario->foto : "images/nophoto.svg"); ?>" class="img-fluid img-circle"></figure><p class="hidden-sm-up hidden-xs-up"><?php echo $usuario->nome; ?></p></a></div>
			</header>
			<div class="conteudo">
				<iframe id="paginaTarget" name="paginaTarget" src="capa.php" width="100%" height="100%" allowtransparency="true" marginwidth="0" marginheight="0" scrolling="yes" frameborder="0"></iframe>
			</div>
		</div>
	</body>

	<script type="text/javascript" src="js/menu/snap.svg-min.js"></script>
	<script type="text/javascript" src="js/menu/classie.js"></script>
	<script type="text/javascript" src="js/menu/menu.js"></script>
</html>
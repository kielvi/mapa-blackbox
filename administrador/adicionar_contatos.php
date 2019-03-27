<?php require_once("sessao.php");

if($acao=="editar") { $row = mysql_fetch_object(mysql_query("SELECT * FROM $nomeDaPagina WHERE codigo='$codigo'")); }

if(isset($acaoForm)) {
	// INSERE NOVO REGISTRO NO BANCO
	if($acaoForm=="adicionar") {
		mysql_query("INSERT INTO $nomeDaPagina (nome, email, tipo) VALUES ('$nome', '$email', '$tipo')");
	}
	// ALTERA UM REGISTRO NO BANCO
	if($acaoForm=="editar") { 
		// ALTERA OS DADOS NO BANCO DE DADOS
		mysql_query("UPDATE $nomeDaPagina SET nome='$nome', email='$email' WHERE codigo='$codigo'");
	}
	echo "<script>$(location).attr({'href':'$urlPadrao', 'target':'paginaTarget'})</script>";
} ?>

<link rel="stylesheet" href="css/adicionar.css">
<link rel="stylesheet" href="css/responsive.css">

<div class="content adicionar-bg">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="capa.php" target="paginaTarget">Página Inicial</a></li>
		<li class="breadcrumb-item hidden-xs-down"><a href="<?php echo $urlPadrao; ?>" target="paginaTarget">Listagem de <?php echo $sql->admPagina($tabela); ?></a></li>
		<li class="breadcrumb-item active"><?php echo ucfirst($acao)." ".$sql->admPagina($tabela); ?></li>
	</ol>

	<div class="container-fluid">
		<div class="col-md-12"><h1><?php echo ucfirst($acao)." <b>".$sql->admPagina($tabela); ?></b></h1></div>
		<div class="container adicionar">
			<form id="form-adicionar" name="form-adicionar" method="post" action="" enctype="multipart/form-data">
				<input id="acaoForm" name="acaoForm" type="hidden" value="<?php echo $acao; ?>" />
				<div class="col-md-6 form-group">
					<label for="nome">Nome</label>
					<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $row->nome; ?>" required>
				</div>
				<div class="col-md-6 form-group">
					<label for="email">E-mail</label>
					<input type="email" class="form-control" id="email" name="email" value="<?php echo $row->email; ?>" required>
				</div>
				<div class="col-md-12 adicionar-buttons" align="center">
					<button type="button" onclick="$(location).attr({'href':'<?php echo $urlPadrao; ?>', 'target':'paginaTarget'});">Cancelar</button>
					<button type="submit">Salvar</button>
				</div>

			</form>
		</div>
	</div>
</div>
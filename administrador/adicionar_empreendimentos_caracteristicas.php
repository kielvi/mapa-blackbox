<?php require_once("sessao.php");

if($acao=="editar") { $row = mysql_fetch_object(mysql_query("SELECT * FROM $nomeDaPagina WHERE codigo='$codigo'")); }

if(isset($acaoForm)) {
	// INSERE NOVO REGISTRO NO BANCO
	if($acaoForm=="adicionar") {
		mysql_query("INSERT INTO $nomeDaPagina (ordem, empreendimento, caracteristica) VALUES ('$ordem', '$empreendimento', '$caracteristica')");		
	}
	// ALTERA UM REGISTRO NO BANCO
	if($acaoForm=="editar") { 
		// ALTERA OS DADOS NO BANCO DE DADOS
		mysql_query("UPDATE $nomeDaPagina SET ordem='$ordem', empreendimento='$empreendimento', caracteristica='$caracteristica' WHERE codigo='$codigo'");
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
				<div class="col-md-4 form-group">
					<label for="ordem">Ordem</label>
					<select name="ordem" class="form-control" required>
						<?php for($o=99; $o>=0; $o--) { ?>
							<option value="<?php echo $o; ?>" <?php echo (isset($row->ordem) ? $row->ordem==$o ? "selected":false : ''); ?>><?php echo $o; ?></option>
						<?php } ?>
					</select>
					<p class="help-block">Quanto menor o valor, maior visibilidade.</p>
				</div>
				<div class="col-md-4 form-group">
					<label for="empreendimento">Empreendimento</label>
					<select id="empreendimento" name="empreendimento" class="form-control" required>
						<option>Selecione</option>
						<?php $query_empreendimentos = mysql_query("SELECT * FROM empreendimentos ORDER BY titulo ASC"); 
						while($row_empreendimentos = mysql_fetch_object($query_empreendimentos)) { ?>
							<option value="<?php echo $row_empreendimentos->codigo; ?>" <?php echo ($row->empreendimento==$row_empreendimentos->codigo ? "selected" : false); ?>><?php echo $row_empreendimentos->titulo; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-4 form-group">
					<label for="caracteristica">Categoria</label>
					<input type="text" class="form-control" id="caracteristica" name="caracteristica" value="<?php echo $row->caracteristica; ?>" required>
				</div>
				<div class="col-md-12 adicionar-buttons" align="center">
					<button type="submit">Salvar</button>
					<button type="button" onclick="$(location).attr({'href':'<?php echo $urlPadrao; ?>', 'target':'paginaTarget'});">Cancelar</button>
				</div>

			</form>
		</div>
	</div>
</div>
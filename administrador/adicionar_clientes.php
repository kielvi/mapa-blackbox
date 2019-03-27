<?php require_once("sessao.php");

if($acao=="editar") { $row = mysql_fetch_object(mysql_query("SELECT * FROM $nomeDaPagina WHERE codigo='$codigo'")); } ?>

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
				<div class="col-md-12 form-group">
					<label for="titulo">Cliente</label>
					<input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $row->titulo; ?>" required>
				</div>
				<div class="col-md-12 form-group">
					<input type="file" id="input-file-now" name="arquivo" class="dropify" <?php echo ($row->foto<>'' ? "data-default-file='../$row->foto'" : ''); ?> data-max-file-size="2M" data-allowed-file-extensions="png jpg jpeg" />
				</div>
				<div class="col-md-12 adicionar-buttons" align="center">
					<button type="button" onclick="$(location).attr({'href':'<?php echo $urlPadrao; ?>', 'target':'paginaTarget'});">Cancelar</button>
					<button type="submit">Salvar</button>
				</div>

			</form>
		</div>
	</div>
</div>


<script type="text/javascript">
$('.dropify').dropify({
	messages: {
        'default': 'Arraste e solte um arquivo aqui ou clique para selecionar',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
      	'error':   'Ooops, algo errado foi anexado.'
	}
});

$("textarea").jqte();

$('input[id="input-file-now"]').change(function(ev) { $('#paginaTarget').height($('#paginaTarget').contents().height()); });
</script>

<?php 

// DIRETORIO DO ARQUIVO ANEXO
$diretorio = "../fotos_".$sql->nomeDaPagina()."/";

if(isset($acaoForm)) {
	// INSERE NOVO REGISTRO NO BANCO
	if($acaoForm=="adicionar") {
		mysql_query("INSERT INTO $nomeDaPagina (titulo) VALUES ('$titulo')");
		$lastid = mysql_fetch_array(mysql_query("SELECT LAST_INSERT_ID()"));
		$codigo = $lastid[0];


		// ENVIA O ARQUIVO SE EXISTIR
		if(!empty($_FILES)) {
			$arquivoTemporario = $_FILES['arquivo']['tmp_name'];
			$arquivoParts  = pathinfo($_FILES['arquivo']['name']);
			$extensao = $arquivoParts['extension'];
			$destinoFinal = $diretorio.$codigo.".".$extensao;

			move_uploaded_file($arquivoTemporario, $destinoFinal);
			mysql_query("UPDATE $nomeDaPagina SET foto='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
		}
		
	}
	// ALTERA UM REGISTRO NO BANCO
	if($acaoForm=="editar") { 
		// ENVIA O ARQUIVO SE EXISTIR
		if(!empty($_FILES) && !empty($_FILES['arquivo']['tmp_name'])) {
			// DELETA O ARQUIVO ANTIGO
			$selectArq = mysql_fetch_object(mysql_query("SELECT * FROM $nomeDaPagina WHERE codigo='{$codigo}'"));
			$arquivoOld = $diretorio.$selectArq->foto;	
			if($arquivoOld<>'' && is_file($arquivoOld)) { unlink($arquivoOld); }

			if(!empty($_FILES['arquivo']['tmp_name'])) {
				$arquivoTemporario = $_FILES['arquivo']['tmp_name'];
				$arquivoParts  = pathinfo($_FILES['arquivo']['name']);
				$extensao = $arquivoParts['extension'];
				$destinoFinal = $diretorio.$codigo.".".$extensao;

				move_uploaded_file($arquivoTemporario, $destinoFinal);
			}
		}

		// ALTERA OS DADOS NO BANCO DE DADOS
		mysql_query("UPDATE $nomeDaPagina SET titulo='$titulo' WHERE codigo='$codigo'");
	}
	echo "<script>$(location).attr({'href':'$urlPadrao', 'target':'paginaTarget'})</script>";
} ?>
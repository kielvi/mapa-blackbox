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

		<div class="col-md-12 mt-2">
			<div class="alert alert-warning text-center" role="alert">
				<strong>Somente vídeos YOUTUBE</strong>
			</div>
		</div>

		<div class="container adicionar">
			<form id="form-adicionar" name="form-adicionar" method="post" action="" enctype="multipart/form-data">
				<input id="acaoForm" name="acaoForm" type="hidden" value="<?php echo $acao; ?>" />

				<div class="row">
					<div class="col-md-4 form-group">
						<label for="ordem">Ordem</label>
						<select name="ordem" class="form-control" required>
							<?php for($o=99; $o>=0; $o--) { ?>
								<option value="<?php echo $o; ?>" <?php echo (isset($row->ordem) ? $row->ordem==$o ? "selected":false : ''); ?>><?php echo $o; ?></option>
							<?php } ?>
						</select>
						<p class="help-block">Quanto menor o valor, maior visibilidade.</p>
					</div>
					<div class="col-md-3 form-group">
						<label for="cod_empreendimento">Empreendimento</label>
						<select id="cod_empreendimento" name="cod_empreendimento" class="form-control" required>
							<option>Selecione</option>
							<?php $query_empreendimentos = mysql_query("SELECT * FROM empreendimentos ORDER BY titulo ASC"); 
							while($row_empreendimentos = mysql_fetch_object($query_empreendimentos)) { ?>
								<option value="<?php echo $row_empreendimentos->codigo; ?>" <?php echo ($row->cod_empreendimento==$row_empreendimentos->codigo ? "selected" : false); ?>><?php echo $row_empreendimentos->titulo; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-5 form-group">
						<label for="titulo">Título</label>
						<input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $row->titulo; ?>" required>
					</div>
					<div class="col-md-12 form-group">
						<label for="url">URL do vídeo</label>
						<input type="text" class="form-control" id="url" name="url" value="<?php echo $row->url; ?>" required>
					</div>
					<div class="col-md-12 form-group">
						<label for="descricao">Descrição</label>
						<input type="text" class="form-control" id="descricao" name="descricao" value="<?php echo $row->descricao; ?>">
					</div>
				</div>

				<div class="col-md-12 adicionar-buttons" align="center">
					<button type="submit">Salvar</button>
					<button type="button" onclick="$(location).attr({'href':'<?php echo $urlPadrao; ?>', 'target':'paginaTarget'});">Cancelar</button>
				</div>

			</form>
		</div>
	</div>
</div>


<script type="text/javascript">
var dropify = $('.dropify-foto');

$('.dropify').change(function(ev) { $('#paginaTarget').height($('#paginaTarget').contents().height()); });

dropify.dropify({
	messages: {
        'default': 'Arraste e solte um arquivo aqui ou clique para selecionar',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
      	'error':   'Ooops, algo errado foi anexado.'
	}
});


dropify.on('dropify.beforeClear', function(event, element) {
    return confirm("Tem certeza que deseja deletar?");

}).on('dropify.afterClear', function(event, element){
	var arquivo = element.element.dataset.defaultFile;
});
</script>

<?php // DIRETORIO DA FACJADA
$diretorio = "../fotos_".$sql->nomeDaPagina()."/";

if(isset($acaoForm)) {
	// INSERE NOVO REGISTRO NO BANCO
	if($acaoForm=="adicionar") {
		mysql_query("INSERT INTO $nomeDaPagina (cod_empreendimento, ordem, titulo, url, descricao) VALUES ('$cod_empreendimento', '$ordem', '$titulo', '$url', '$descricao')");
	}

	// ALTERA UM REGISTRO NO BANCO
	if($acaoForm=="editar") { 
		mysql_query("UPDATE $nomeDaPagina SET cod_empreendimento='$cod_empreendimento', ordem='$ordem', titulo='$titulo', url='$url', descricao='$descricao' WHERE codigo='$codigo'");
	}

	echo "<script>$(location).attr({'href':'$urlPadrao', 'target':'paginaTarget'})</script>";
} ?>
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
				<div class="col-md-5 form-group" style="min-height: 500px;">
					<input type="file" id="input-file-now" name="arquivo" class="dropify" <?php echo ($row->foto<>'' ? "data-default-file='../$row->foto'" : ''); ?> data-max-file-size="1M" data-allowed-file-extensions="jpg png" />
				</div>
					<div class="col-md-3 form-group">
						<label for="ordem">Ordem</label>
						<select name="ordem" class="form-control">
							<option value="0">0</option>
							<?php for($o=1; $o<=100; $o++) { ?>
								<option value="<?php echo $o; ?>" <?php echo ($row->ordem==$o ? "selected":false); ?>><?php echo $o; ?></option>
							<?php } ?>
						</select>
						<small>Quanto menor o valor, maior visibilidade.</small>
					</div>
					<div class="col-md-4 form-group">
						<label for="cod_empreendimento">Empreendimento</label>
						<select id="cod_empreendimento" name="cod_empreendimento" class="form-control" required>
							<option>Selecione</option>
							<?php $query_empreendimentos = mysql_query("SELECT * FROM empreendimentos ORDER BY titulo ASC"); 
							while($row_empreendimentos = mysql_fetch_object($query_empreendimentos)) { ?>
								<option value="<?php echo $row_empreendimentos->codigo; ?>" <?php echo ($row->cod_empreendimento==$row_empreendimentos->codigo ? "selected" : false); ?>><?php echo $row_empreendimentos->titulo; ?></option>
							<?php } ?>
						</select>
						<small>&nbsp;</small>
					</div>
				<? /*<div class="col-md-3 form-group">
					<label for="area">Area</label>
					<select id="area" name="area" class="form-control" required>
						<option>Selecione</option>
						<option value="oportunidades" <?php echo ($row->area=="" ? "selected" : false); ?>>Oportunidades</option>
					</select>
				</div>*/ ?>
				<div class="col-md-7 form-group">
					<label for="titulo">Titulo</label>
					<input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $row->titulo; ?>" >
				</div>
				<div class="col-md-7 form-group">
					<label for="slogan">Slogan</label>
					<input type="text" class="form-control" id="slogan" name="slogan" value="<?php echo $row->slogan; ?>" >
				</div>
				<div class="col-md-7 form-group">
					<label for="url">Link</label>
					<input type="url" class="form-control" id="url" name="url" value="<?php echo $row->url; ?>">
				</div>
				<div class="col-md-7 form-group">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="dataAtiva" <?php echo ($row->dataAtiva=="S" ? 'value="S" checked' : ''); ?>>
							Adicionar data de início e fim para o slide
						</label>
					</div>
				</div>

				<div id="data" <?php echo ($row->dataAtiva=="N" || !isset($row->dataAtiva) ? 'class="invisible"' : ''); ?>>
					<div class="col-md-4 form-group">
						<label for="dataInicio">Data de Início</label>
						<input type="text" class="form-control" id="dataInicio" name="dataInicio" maxlength="10" onkeypress="barra(this)" value="<?php echo ($row->dataInicio<>"" ? $App->Data($row->dataInicio) : date("d/m/Y")); ?>">
					</div>
					<div class="col-md-3 form-group">
						<label for="dataFinal">Data de Fim</label>
						<input type="text" class="form-control" id="dataFinal" name="dataFinal" maxlength="10" onkeypress="barra(this)" value="<?php echo ($row->dataFinal<>"" ? $App->Data($row->dataFinal) : date("d/m/").(date("Y")+1)); ?>">
					</div>
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
$(document).on('change', 'input[name=dataAtiva]', function(event) {
	event.preventDefault();
	
	$("#data").hasClass('invisible')==true ? $("#data").removeClass('invisible') : $("#data").addClass('invisible');
});


$('.dropify').dropify({
	messages: {
        'default': 'Arraste e solte um arquivo aqui ou clique para selecionar',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
      	'error':   'Ooops, algo errado foi anexado.'
	}
});
$('input[id="input-file-now"]').change(function(ev) { $('#paginaTarget').height($('#paginaTarget').contents().height()); });
</script>

<?php 

// DIRETORIO DO ARQUIVO ANEXO
$diretorio = "../fotos_".$sql->nomeDaPagina()."/";

if(isset($acaoForm)) {
	$dataInicio = $App->DataHoraAmerican($dataInicio);
	$dataFinal  = $App->DataHoraAmerican($dataFinal);

	// INSERE NOVO REGISTRO NO BANCO
	if($acaoForm=="adicionar") {

		mysql_query("INSERT INTO $nomeDaPagina (area, ordem, cod_empreendimento, titulo, slogan, url, dataAtiva, dataInicio, dataFinal) VALUES ('$area'. '$ordem', '$cod_empreendimento', '$titulo', '$slogan', '$url', '$dataAtiva', '$dataInicio', '$dataFinal')");
		$lastid = mysql_fetch_array(mysql_query("SELECT LAST_INSERT_ID()"));
		$codigo = $lastid[0];

		// CRIA A PASTA DE ENVIO DE MAIS FOTOS
		//if(!is_dir($diretorio.$codigo)) { mkdir($diretorio.$codigo, 0777); }

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
		if($cod_empreendimento == "Selecione") $cod_empreendimento = 0;
		$sql = "UPDATE $nomeDaPagina SET area='$area', ordem='$ordem', cod_empreendimento='$cod_empreendimento', titulo='$titulo', slogan='$slogan', url='$url', dataAtiva='$dataAtiva', dataInicio='$dataInicio', dataFinal='$dataFinal' WHERE codigo='$codigo'";
		$result = mysql_query($sql);
	}
	echo "<script>$(location).attr({'href':'$urlPadrao', 'target':'paginaTarget'})</script>";
} ?>
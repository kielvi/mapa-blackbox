<?php require_once("sessao.php");

if($acao=="editar") { $row = mysql_fetch_object(mysql_query("SELECT * FROM $nomeDaPagina WHERE codigo='$codigo'")); } ?>

<link rel="stylesheet" href="css/adicionar.css">
<link rel="stylesheet" href="css/responsive.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.1/css/bootstrap-slider.min.css">
<style type="text/css">
.slider.slider-horizontal {
	width: 100%;
}
.slider-handle {
    width: 24px;
    height: 25px;
    margin-top: -4px;
    border-radius: 0!important;
    background: url(../assets/images/empreendimento-andamento-slide-off.svg);
    background-size: cover;
    box-shadow: none;
    border-radius:0;
}
.tooltip {
	margin-left: -16px!important;
}
.tooltip-inner {
	padding: 3px 10px!important;
	background-color: #0c4065;
}
</style>
<div class="content adicionar-bg">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="capa.php" target="paginaTarget">Página Inicial</a></li>
		<li class="breadcrumb-item hidden-xs-down"><a href="<?php echo $urlPadrao; ?>" target="paginaTarget">Listagem de <?php echo $sql->admPagina($tabela); ?></a></li>
		<li class="breadcrumb-item active"><?php echo ucfirst($acao)." ".$sql->admPagina($tabela); ?></li>
	</ol>

	<div class="container-fluid">
		<div class="col-md-12"><h1><?php echo ucfirst($acao)." <b>".$sql->admPagina($tabela); ?></b></h1></div>

		<div class="col-md-12 mt-2">
			<div class="alert alert-warning" role="alert">
				<strong>Fachada em PNG somente | </strong> Tamanho: 600x667px ou superior, seguindo a mesma proporção
			</div>
		</div>

		<div class="container adicionar">
			<form id="form-adicionar" name="form-adicionar" method="post" action="" enctype="multipart/form-data">
				<input id="acaoForm" name="acaoForm" type="hidden" value="<?php echo $acao; ?>" />

				<div class="row">
					<div class="col-md-12 form-group">
						<label for="foto">Foto</label>
						<input type="file" id="foto" name="foto" data-codigo="<?php echo $row->codigo; ?>" data-tabela="<?php echo $nomeDaPagina; ?>" class="dropify dropify-foto" <?php echo ($row->foto<>'' ? "data-default-file='../$row->foto'" : ''); ?> data-max-file-size="1M" data-allowed-file-extensions="png" />
					</div>
					<div class="col-md-12 form-group">
						<label for="cod_empreendimento">Empreendimento</label>
						<select id="cod_empreendimento" name="cod_empreendimento" class="form-control cod_empreendimento" required>
							<option>Selecione</option>
							<?php $query_empreendimentos = mysql_query("SELECT * FROM empreendimentos ORDER BY titulo ASC"); 
							while($row_empreendimentos = mysql_fetch_object($query_empreendimentos)) { ?>
								<option value="<?php echo $row_empreendimentos->codigo; ?>" <?php echo ($row->cod_empreendimento==$row_empreendimentos->codigo ? "selected" : false); ?>><?php echo $row_empreendimentos->titulo; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-12">
						
						<div class="col-12 form-group">
							<label for="concluido">Concluído</label><br>
							<input id="concluido" name="concluido" data-slider-id='concluido' data-slider-value="<?php echo $row->concluido; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
						</div>
						<div class="row">
							<div class="col-md-4 form-group">
								<label for="acabamentointerno">Revestimento externo</label><br>
								<input id="acabamentointerno" name="acabamentointerno" data-slider-id='acabamentointerno' data-slider-value="<?php echo $row->acabamentointerno; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" />
							</div>
							<div class="col-md-4 form-group">
								<label for="revestimentoexterno">Revestimento interno</label><br>
								<input id="revestimentoexterno" name="revestimentoexterno" data-slider-id='revestimentoexterno' data-slider-value="<?php echo $row->revestimentoexterno; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="reboco">Esquadrias</label><br>
								<input id="reboco" name="reboco" data-slider-id='reboco' data-slider-value="<?php echo $row->reboco; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="alvenaria">Instalações elétricas e hidráulicas </label><br>
								<input id="alvenaria" name="alvenaria" data-slider-id='alvenaria' data-slider-value="<?php echo $row->alvenaria; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="estrutural">Alvenaria</label><br>
								<input id="estrutural" name="estrutural" data-slider-id='estrutural' data-slider-value="<?php echo $row->estrutural; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="concretagem">Supraestrutura</label><br>
								<input id="concretagem" name="concretagem" data-slider-id='concretagem' data-slider-value="<?php echo $row->concretagem; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="blocos">Infraestrutura</label><br>
								<input id="blocos" name="blocos" data-slider-id='blocos' data-slider-value="<?php echo $row->blocos; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="fundacao">Serviços preliminares</label><br>
								<input id="fundacao" name="fundacao" data-slider-id='fundacao' data-slider-value="<?php echo $row->fundacao; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
						</div>

<!-- 
BACKUP DA ORDEM E NOMES ANTIGOS
							<div class="col-md-4 form-group">
								<label for="acabamentointerno">Acabamentos internos</label><br>
								<input id="acabamentointerno" name="acabamentointerno" data-slider-id='acabamentointerno' data-slider-value="<?php echo $row->acabamentointerno; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" />
							</div>
							<div class="col-md-4 form-group">
								<label for="revestimentoexterno">Revestimento externo</label><br>
								<input id="revestimentoexterno" name="revestimentoexterno" data-slider-id='revestimentoexterno' data-slider-value="<?php echo $row->revestimentoexterno; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="reboco">Reboco</label><br>
								<input id="reboco" name="reboco" data-slider-id='reboco' data-slider-value="<?php echo $row->reboco; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="alvenaria">Alvenaria</label><br>
								<input id="alvenaria" name="alvenaria" data-slider-id='alvenaria' data-slider-value="<?php echo $row->alvenaria; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="estrutural">Estrutural</label><br>
								<input id="estrutural" name="estrutural" data-slider-id='estrutural' data-slider-value="<?php echo $row->estrutural; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="concretagem">Concretagem de lage</label><br>
								<input id="concretagem" name="concretagem" data-slider-id='concretagem' data-slider-value="<?php echo $row->concretagem; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="blocos">Blocos e baldrames</label><br>
								<input id="blocos" name="blocos" data-slider-id='blocos' data-slider-value="<?php echo $row->blocos; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
							<div class="col-md-4 form-group">
								<label for="fundacao">Fundação</label><br>
								<input id="fundacao" name="fundacao" data-slider-id='fundacao' data-slider-value="<?php echo $row->fundacao; ?>" data-provide="slider" data-slider-tooltip="always" data-slider-tooltip-position="bottom" data-slider-min="0" data-slider-max="100" data-slider-step="1" type="text" /><br><br>
							</div>
 -->


				</div>

				<div class="col-md-12 adicionar-buttons" align="center">
					<button type="button" onclick="$(location).attr({'href':'<?php echo $urlPadrao; ?>', 'target':'paginaTarget'});">Cancelar</button>
					<button type="submit">Salvar</button>
				</div>

			</form>
		</div>
	</div>
</div>

<script type="text/javascript" src="../assets/js/bootstrap-slider.js"></script>
<script type="text/javascript">
$(document).on('change', '.cod_empreendimento', function(event) {
	event.preventDefault();
	
	$.ajax({
		url: 'empreendimentos_andamento.php?cod_empreendimento='+event.target.value,
		dataType: 'json',
		statusCode: {
    		200: function(data) { 

    			var slider = $("#concluido").slider();
    			slider.destroy();
    			slider.setAttribute('value', data.concluido);



    		}
    	}
	})
});


$('.dropify').change(function(ev) { $('#paginaTarget').height($('#paginaTarget').contents().height()); });
$('.dropify').dropify({
	messages: {
        'default': 'Arraste e solte um arquivo aqui ou clique para selecionar',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
      	'error':   'Ooops, algo errado foi anexado.'
	}
});

$('.dropify').on('dropify.beforeClear', function(event, element) {
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
		mysql_query("INSERT INTO $nomeDaPagina (cod_empreendimento, concluido, acabamentointerno, revestimentoexterno, reboco, alvenaria, estrutural, concretagem, blocos, fundacao) VALUES ('$cod_empreendimento', '$concluido', '$acabamentointerno', '$revestimentoexterno', '$reboco', '$alvenaria', '$estrutural', '$concretagem', '$blocos', '$fundacao')");
		$lastid = mysql_fetch_array(mysql_query("SELECT LAST_INSERT_ID()"));
		$codigo = $lastid[0];

		// CRIA A PASTA DE ENVIO DE MAIS FOTOS
		if(!is_dir($diretorio)) { mkdir($diretorio, 0777); }

		if(!empty($_FILES)) {
			if(!empty($_FILES["foto"])) {
				$tmp_name = $_FILES['foto']['tmp_name'];
				$arquivoParts  = pathinfo($_FILES['foto']['name']);
				$extensao = $arquivoParts['extension'];
				$destinoFinal = $diretorio.$codigo.".".$extensao;

				move_uploaded_file($tmp_name, $destinoFinal);

				if(is_file($destinoFinal)) {
					mysql_query("UPDATE $nomeDaPagina SET foto='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
				}
			}
		}
	}

	// ALTERA UM REGISTRO NO BANCO
	if($acaoForm=="editar") { 

		mysql_query("UPDATE $nomeDaPagina SET cod_empreendimento='$cod_empreendimento', concluido='$concluido', acabamentointerno='$acabamentointerno', revestimentoexterno='$revestimentoexterno', reboco='$reboco', alvenaria='$alvenaria', estrutural='$estrutural', concretagem='$concretagem', blocos='$blocos', fundacao='$fundacao' WHERE codigo='$codigo'");

		if(!empty($_FILES)) {
			if(!empty($_FILES["foto"]) && !empty($_FILES['foto']['tmp_name'])) {
				// DELETA O ARQUIVO ANTIGO
				$arqOld = "../".$row->foto;	
				$arqOld<>'' && is_file($arqOld) ? unlink($arqOld) : false;

				if(!empty($_FILES['foto']['tmp_name'])) {
					$tmp_name = $_FILES['foto']['tmp_name'];
					$arquivoParts  = pathinfo($_FILES['foto']['name']);
					$extensao = $arquivoParts['extension'];
					$destinoFinal = $diretorio.$codigo.".".$extensao;

					move_uploaded_file($tmp_name, $destinoFinal);

					mysql_query("UPDATE $nomeDaPagina SET foto='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
				}
			}
		}
	}

	echo "<script>$(location).attr({'href':'$urlPadrao', 'target':'paginaTarget'})</script>";
} ?>
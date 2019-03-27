<?php require_once("sessao.php");



if($acao=="editar") { $row = mysql_fetch_object(mysql_query("SELECT * FROM $nomeDaPagina WHERE codigo='$codigo'")); } ?>

<link rel="stylesheet" href="css/adicionar.css">
<link rel="stylesheet" href="css/responsive.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<div class="content adicionar-bg">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="capa.php" target="paginaTarget">Página Inicial</a></li>
		<li class="breadcrumb-item hidden-xs-down"><a href="<?php echo $urlPadrao; ?>" target="paginaTarget">Listagem de <?php echo $sql->admPagina($tabela); ?></a></li>
		<li class="breadcrumb-item active"><?php echo ucfirst($acao)." ".$sql->admPagina($tabela); ?></li>
	</ol>

	<div class="container-fluid">
		<div class="col-md-12"><h1><?php echo ucfirst($acao)." <b>".$sql->admPagina($tabela); ?></b></h1></div>

		<div class="col-md-4 mt-2">
			<div class="alert alert-warning" role="alert">
				<strong>Fachada (PNG somente):</strong><br>
				Tamanho: 261x429 pixels ou maior seguindo a mesma proporção
			</div>
		</div>
		<div class="col-md-4 mt-2">
			<div class="alert alert-warning" role="alert">
				<strong>Foto da fachada (JPG somente):</strong><br>
				Tamanho: máximo de 1000 pixels
			</div>
		</div>
		<div class="col-md-4">
			<div class="alert alert-warning" role="alert">
				<strong>Logo (PNG somente):</strong><br>
				Tamanho: 214x84 pixels ou maior seguindo a mesma proporção
			</div>
		</div>

		<div class="container adicionar">
			<form id="form-adicionar" name="form-adicionar" method="post" action="" enctype="multipart/form-data">
				<input id="acaoForm" name="acaoForm" type="hidden" value="<?php echo $acao; ?>" />

				<div class="row">

					<div class="col-md-4 form-group">
						<label for="fachada">Fachada</label>
						<input type="file" id="fachada" name="fachada" data-codigo="<?php echo $row->codigo; ?>" data-tabela="<?php echo $nomeDaPagina; ?>" class="dropify dropify-fachada" <?php echo ($row->fachada<>'' ? "data-default-file='../$row->fachada'" : ''); ?> data-max-file-size="1M" data-allowed-file-extensions="png" />
					</div>
					<div class="col-md-4 form-group">
						<label for="fachada_foto">Foto da fachada</label>
						<input type="file" id="fachada_foto" name="fachada_foto" class="dropify dropify-fachada_foto" <?php echo ($row->fachada_foto<>'' ? "data-default-file='../$row->fachada_foto'" : ''); ?> data-max-file-size="1M" data-allowed-file-extensions="jpg" />
					</div>
					<div class="col-md-4 form-group">
						<label for="logo">Logo</label>
						<input type="file" id="logo" name="logo" class="dropify dropify-logo" <?php echo ($row->logo<>'' ? "data-default-file='../$row->logo'" : ''); ?> data-max-file-size="1M" data-allowed-file-extensions="png" />
					</div>

					<div class="col-md-4 form-group">
						<label for="ordem">Ordem</label>
						<select name="ordem" class="form-control">
							<option value="0">0</option>
							<?php for($o=1; $o<=100; $o++) { ?>
								<option value="<?php echo $o; ?>" <?php echo ($row->ordem==$o ? "selected":false); ?>><?php echo $o; ?></option>
							<?php } ?>
						</select>
						<p class="help-block">Quanto menor o valor, maior visibilidade.</p>
					</div>
					<div class="col-md-5 form-group">
						<label for="titulo">Empreendimento</label>
						<input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $row->titulo; ?>" required>
					</div>
					<div class="col-md-3 form-group">
						<label for="categoria">Categoria</label>
						<select name="categoria" class="form-control" required>
							<option value="">Selecione</option>
							<?php $query_categoria = mysql_query("SELECT * FROM empreendimentos_categorias");
							while($row_categoria = mysql_fetch_object($query_categoria)) { ?>
								<option value="<?php echo $row_categoria->codigo; ?>" <?php echo ($row->categoria==$row_categoria->codigo ? "selected":false); ?>><?php echo $row_categoria->categoria; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2 form-group">
						<label for="suites">Suítes</label>
						<input type="text" class="form-control" id="suites" name="suites" value="<?php echo $row->suites; ?>">
					</div>
					<div class="col-md-2 form-group">
						<label for="aptos">Aptos por andar</label>
						<input type="text" class="form-control" id="aptos" name="aptos" value="<?php echo $row->aptos; ?>">
					</div>
					<div class="col-md-2 form-group">
						<label for="tamanho">tamanho (m2)</label>
						<input type="text" class="form-control" id="tamanho" name="tamanho" value="<?php echo $row->tamanho; ?>">
					</div>
					<div class="col-md-2 form-group">
						<label for="vagas">Vagas</label>
						<input type="text" class="form-control" id="vagas" name="vagas" value="<?php echo $row->vagas; ?>">
					</div>
					<div class="col-md-2 form-group">
						<label for="lat">Latitude</label>
						<input type="text" class="form-control" id="lat" name="lat" value="<?php echo $row->lat; ?>">
					</div>
					<div class="col-md-2 form-group">
						<label for="longitude">Longitude</label>
						<input type="text" class="form-control" id="lng" name="lng" value="<?php echo $row->lng; ?>">
					</div>
					<div class="col-md-8 form-group">
						<label for="longitude">Endereço</label>
						<input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo $row->endereco; ?>">
					</div>
					<div class="col-md-4 form-group">
						<label for="bairro">Bairro</label>
						<select type="text" class="form-control js-select2-tag" id="bairro" name="bairro" value="<?php echo $row->bairro; ?>">
							<?php 
								$bairros_sql = mysql_query("SELECT DISTINCT(bairro) as nome FROM $nomeDaPagina WHERE bairro != '';"); 
								while($_bairro = mysql_fetch_object($bairros_sql)) {
								?>
								<option value="<?=$_bairro->nome?>" <?php if($_bairro->nome == $row->bairro){?> selected <?php } ?> ><?=$_bairro->nome?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-12 form-group">
						<label for="descricao">O empreendimento</label>
						<textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo $row->descricao; ?></textarea>
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
var dropifyFachada = $('.dropify-fachada');
var dropifyFotoFachada = $('.dropify-fachada_foto');
var dropifyLogo = $('.dropify-logo');

$('.dropify').change(function(ev) { $('#paginaTarget').height($('#paginaTarget').contents().height()); });

dropifyFachada.dropify({
	messages: {
        'default': 'Arraste e solte um arquivo aqui ou clique para selecionar',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
      	'error':   'Ooops, algo errado foi anexado.'
	}
});
dropifyFotoFachada.dropify({
	messages: {
        'default': 'Arraste e solte um arquivo aqui ou clique para selecionar',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
      	'error':   'Ooops, algo errado foi anexado.'
	}
});
dropifyLogo.dropify({
	messages: {
        'default': 'Arraste e solte um arquivo aqui ou clique para selecionar',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
      	'error':   'Ooops, algo errado foi anexado.'
	}
});

$("textarea").jqte();


dropifyFachada.on('dropify.beforeClear', function(event, element) {
    return confirm("Tem certeza que deseja deletar?");

}).on('dropify.afterClear', function(event, element){
	var arquivo = element.element.dataset.defaultFile;

	/*$.ajax({
		method: "POST",
		url: "deletar_arquivo.php",
		data: { 
			coluna: element.input[0].name, 
			arquivo: arquivo, 
			codigo: element.input[0].dataset.codigo, 
			tabela: element.input[0].dataset.tabela 
		}
	});*/

});

$('.js-select2-tag').select2({ tags: true });

</script>

<?php // DIRETORIO DA FACJADA
$diretorio = "../fotos_".$sql->nomeDaPagina()."/";

if(isset($acaoForm)) {
	// INSERE NOVO REGISTRO NO BANCO
	if($acaoForm=="adicionar") {
		mysql_query("INSERT INTO $nomeDaPagina (titulo, ordem, categoria, suites, aptos, tamanho, vagas, descricao, lat, lng, endereco, bairro) VALUES ('$titulo', '$ordem', '$categoria', '$suites', '$aptos', '$tamanho', '$vagas', '$descricao', '$lat', '$lng', '$endereco', '$bairro')");
		$lastid = mysql_fetch_array(mysql_query("SELECT LAST_INSERT_ID()"));
		$codigo = $lastid[0];

		// CRIA A PASTA DE ENVIO DE MAIS FOTOS
		if(!is_dir($diretorio)) { mkdir($diretorio, 0777); }
		if(!is_dir($diretorio.$codigo)) { mkdir($diretorio.$codigo, 0777); }

		if(!empty($_FILES)) {
			if(!empty($_FILES["fachada"])) {
				$tmp_name = $_FILES['fachada']['tmp_name'];
				$arquivoParts  = pathinfo($_FILES['fachada']['name']);
				$extensao = $arquivoParts['extension'];
				$destinoFinal = $diretorio.$codigo.".".$extensao;

				move_uploaded_file($tmp_name, $destinoFinal);

				if(is_file($destinoFinal)) {
					mysql_query("UPDATE $nomeDaPagina SET fachada='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
				}
			}
			if(!empty($_FILES["fachada_foto"])) {
				$tmp_name = $_FILES['fachada_foto']['tmp_name'];
				$arquivoParts  = pathinfo($_FILES['fachada_foto']['name']);
				$extensao = $arquivoParts['extension'];
				$destinoFinal = $diretorio.$codigo."/".$codigo.".".$extensao;

				move_uploaded_file($tmp_name, $destinoFinal);

				if(is_file($destinoFinal)) {
					mysql_query("UPDATE $nomeDaPagina SET fachada_foto='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
				}
			}

			if(!empty($_FILES["logo"])) {
				$tmp_name = $_FILES['logo']['tmp_name'];
				$arquivoParts  = pathinfo($_FILES['logo']['name']);
				$extensao = $arquivoParts['extension'];
				$destinoFinal = $diretorio.$codigo."/".$codigo.".".$extensao;

				move_uploaded_file($tmp_name, $destinoFinal);

				if(is_file($destinoFinal)) {
					mysql_query("UPDATE $nomeDaPagina SET logo='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
				}
			}
		}
	}

	// ALTERA UM REGISTRO NO BANCO
	if($acaoForm=="editar") { 

		mysql_query("UPDATE $nomeDaPagina SET titulo='$titulo', ordem='$ordem', categoria='$categoria', suites='$suites', aptos='$aptos', tamanho='$tamanho', vagas='$vagas', descricao='$descricao', lat='$lat', lng='$lng', endereco='$endereco', bairro='$bairro' WHERE codigo='$codigo'");

		if(!empty($_FILES)) {
			if(!empty($_FILES["fachada"]) && !empty($_FILES['fachada']['tmp_name'])) {
				// DELETA O ARQUIVO ANTIGO
				$fachadaOld = "../".$row->fachada;	
				$fachadaOld<>'' && is_file($fachadaOld) ? unlink($fachadaOld) : false;

				if(!empty($_FILES['fachada']['tmp_name'])) {
					$tmp_name = $_FILES['fachada']['tmp_name'];
					$arquivoParts  = pathinfo($_FILES['fachada']['name']);
					$extensao = $arquivoParts['extension'];
					$destinoFinal = $diretorio.$codigo.".".$extensao;

					move_uploaded_file($tmp_name, $destinoFinal);

					mysql_query("UPDATE $nomeDaPagina SET fachada='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
				}
			}


			if(!empty($_FILES["fachada_foto"]) && !empty($_FILES['fachada_foto']['tmp_name'])) {
				// DELETA O ARQUIVO ANTIGO
				$logoOld = "../".$row->fachada_foto;	
				$logoOld<>'' && is_file($logoOld) ? unlink($logoOld) : false;

				$tmp_name = $_FILES['fachada_foto']['tmp_name'];
				$arquivoParts  = pathinfo($_FILES['fachada_foto']['name']);
				$extensao = $arquivoParts['extension'];
				$destinoFinal = $diretorio.$codigo."/".$codigo.".".$extensao;

				move_uploaded_file($tmp_name, $destinoFinal);

				mysql_query("UPDATE $nomeDaPagina SET fachada_foto='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
			}

			if(!empty($_FILES["logo"]) && !empty($_FILES['logo']['tmp_name'])) {
				// DELETA O ARQUIVO ANTIGO
				$logoOld = "../".$row->logo;	
				$logoOld<>'' && is_file($logoOld) ? unlink($logoOld) : false;

				$tmp_name = $_FILES['logo']['tmp_name'];
				$arquivoParts  = pathinfo($_FILES['logo']['name']);
				$extensao = $arquivoParts['extension'];
				$destinoFinal = $diretorio.$codigo."/".$codigo.".".$extensao;

				move_uploaded_file($tmp_name, $destinoFinal);

				mysql_query("UPDATE $nomeDaPagina SET logo='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
			}
		}
	}

	echo "<script>$(location).attr({'href':'$urlPadrao', 'target':'paginaTarget'})</script>";
} ?>
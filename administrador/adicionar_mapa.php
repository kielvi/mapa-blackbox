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
				<strong>Marcador(PNG somente) | </strong> Tamanho: 56x84px
			</div>
		</div>

		<div class="container adicionar">
			<form id="form-adicionar" name="form-adicionar" method="post" action="" enctype="multipart/form-data">
				<input id="acaoForm" name="acaoForm" type="hidden" value="<?php echo $acao; ?>" />

				<div class="row">

					<div class="col-md-4 form-group">
						<label for="pin">Marcador</label>
						<input type="file" id="pin" name="pin" data-codigo="<?php echo $row->codigo; ?>" data-tabela="<?php echo $nomeDaPagina; ?>" class="dropify dropify-pin" <?php echo ($row->pin<>'' ? "data-default-file='../$row->pin'" : ''); ?> data-max-file-size="1M" data-allowed-file-extensions="png" />
					</div>
					<div class="col-md-8 form-group">
						<label for="titulo">Titulo</label>
						<input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $row->titulo; ?>" required>
					</div>
					<div class="col-md-8 form-group">
						<label for="subtitulo">Subtítulo</label>
						<input type="text" class="form-control" id="subtitulo" name="subtitulo" value="<?php echo $row->subtitulo; ?>" required>
					</div>
					<div class="col-md-2 form-group">
						<label for="cep">CEP</label>
						<input onfocusout="buscaEndereco(this.value)" onKeyUp="mascara(this, soNumeros)" type="text" class="form-control" id="cep" name="cep" value="<?php echo $row->cep; ?>" maxlength="8" required>
					</div>
					<div class="col-md-4 form-group">
						<label for="endereco">Endereço</label>
						<input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo $row->endereco; ?>" required>
					</div>
					<div class="col-md-2 form-group">
						<label for="numero">Número</label>
						<input onfocusout="searchMap($('#endereco').val()+', '+this.value)" type="text" class="form-control" id="numero" name="numero" value="<?php echo $row->numero; ?>">
					</div>
					<div class="col-md-4 form-group">
						<label for="complemento">Complemento</label>
						<input type="text" class="form-control" id="complemento" name="complemento" value="<?php echo $row->complemento; ?>">
					</div>
					<div class="col-md-2 form-group">
						<label for="bairro">Bairro</label>
						<input type="text" class="form-control" id="bairro" name="bairro" value="<?php echo $row->bairro; ?>" required>
					</div>
					<div class="col-md-2 form-group">
						<label for="cidade">Cidade</label>
						<input type="text" class="form-control" id="cidade" name="cidade" value="<?php echo $row->cidade; ?>" required>
					</div>
					<div class="col-md-2 form-group">
						<label for="estado">Estado</label>
						<input type="text" class="form-control" id="estado" name="estado" value="<?php echo $row->estado; ?>" required>
					</div>
					<div class="col-md-2 form-group">
						<label for="lat">Latitude</label>
						<input type="text" class="form-control" id="lat" name="lat" value="<?php echo $row->lat; ?>" required>
					</div>
					<div class="col-md-2 form-group">
						<label for="lng">Longitude</label>
						<input type="text" class="form-control" id="lng" name="lng" value="<?php echo $row->lng; ?>" required>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 form-group">
						<div id="map" style="height:300px; background:#FFFFFF;"></div>
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

<div class="modal fade" id="notificacao" tabindex="-1" role="dialog" aria-labelledby="notificacaoTxt" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="notificacaoTxt">CEP não encontrado</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFt8qkU4R_94EgrC0x_qB1dxfw2mR1hF8"></script>
<script type="text/javascript" src="../assets/js/gmaps.js"></script>
<script type="text/javascript" src="../assets/js/gmaps-markerclusterer.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	map = new GMaps({
		div: '#map',
		scrollwheel:false,
		lat: <?php echo ($row->lat<>'' ? $row->lat : '-27.0960682'); ?>,
		lng: <?php echo ($row->lng<>'' ? $row->lng : '-48.6178074'); ?>,
		zoom: <?php echo ($acao=="editar" ? 18 : 14); ?>
	});

	<?php if($acao=="editar") { ?>
		map.addMarker({
			lat: <?php echo $row->lat; ?>,
			lng: <?php echo $row->lng; ?>,
			draggable: true,
			dragend: function(event) {
				var lat = event.latLng.lat();
				var lng = event.latLng.lng();
				$("#lat").val(lat)
				$("#lng").val(lng)
			},
		});
	<?php } ?>

});

function buscaEndereco(cep) {
	$.ajax({
		/*url: 'http://correiosapi.apphb.com/cep/'+cep,*/
		url: 'https://viacep.com.br/ws/'+cep+'/json/',
		dataType: 'jsonp',
		crossDomain: true,
		contentType: "application/json",
		beforeSend: function() {
    			$("input[name='endereco']").val('Pesquisando...')
    			$("input[name='bairro']").val('Pesquisando...')
    			$("input[name='cidade']").val('Pesquisando...')
    			$("input[name='estado']").val('Pesquisando...')
  		},
		statusCode: {
    		200: function(data) { 
    			searchMap(data.cep)
    			$("input[name='endereco']").val(/*data.tipoDeLogradouro+" "+*/data.logradouro)
    			$("input[name='bairro']").val(data.bairro)
    			$("input[name='cidade']").val(data.localidade/*cidade*/)
    			$("input[name='estado']").val(data.uf/*estado*/)
    			$("input[name='complemento']").val(data.complemento)
    		},
    		404: function() {
				$('#notificacao').modal('show')

    			$("input[name='endereco']").val('')
    			$("input[name='bairro']").val('')
    			$("input[name='cidade']").val('')
    			$("input[name='estado']").val('')
    		}
    	}
	});
}

function searchMap(endereco) {
	GMaps.geocode({
		address: endereco,
		
		callback: function(results, status){

			if(status=='OK') {
				if(map.markers.length>0) {
					map.removeMarkers()
				}
				var latlng = results[0].geometry.location;
				map.setCenter(latlng.lat(), latlng.lng());
    			$("#lat").val(latlng.lat())
    			$("#lng").val(latlng.lng())
				map.setZoom(18)
				map.addMarker({
					lat: latlng.lat(),
					lng: latlng.lng(),
					draggable: true,
					dragend: function(event) {
						var lat = event.latLng.lat();
						var lng = event.latLng.lng();
						$("#lat").val(lat)
						$("#lng").val(lng)
					},
				});
			}
		}
	});
}
/*
midiabla_2019
midiabla_2019
YAPqx8OjRH65
*/

var dropifyPin = $('.dropify-pin');
$('.dropify').change(function(ev) { $('#paginaTarget').height($('#paginaTarget').contents().height()); });

dropifyPin.dropify({
	messages: {
        'default': 'Arraste e solte um arquivo aqui ou clique para selecionar',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
      	'error':   'Ooops, algo errado foi anexado.'
	}
});

dropifyPin.on('dropify.beforeClear', function(event, element) {
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
		mysql_query("INSERT INTO $nomeDaPagina (titulo, subtitulo, cep, endereco, numero, complemento, bairro, cidade, estado, lat, lng) VALUES ('$titulo', '$subtitulo', '$cep', '$endereco', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$lat', '$lng')");
		$lastid = mysql_fetch_array(mysql_query("SELECT LAST_INSERT_ID()"));
		$codigo = $lastid[0];

		// CRIA A PASTA DE ENVIO DE MAIS FOTOS
		if(!is_dir($diretorio)) { mkdir($diretorio, 0777); }

		if(!empty($_FILES)) {
			if(!empty($_FILES["pin"])) {
				$tmp_name = $_FILES['pin']['tmp_name'];
				$arquivoParts  = pathinfo($_FILES['pin']['name']);
				$extensao = $arquivoParts['extension'];
				$destinoFinal = $diretorio.$codigo.".".$extensao;

				move_uploaded_file($tmp_name, $destinoFinal);

				if(is_file($destinoFinal)) {
					mysql_query("UPDATE $nomeDaPagina SET pin='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
				}
			}
		}
	}

	// ALTERA UM REGISTRO NO BANCO
	if($acaoForm=="editar") { 

		mysql_query("UPDATE $nomeDaPagina SET titulo='$titulo', subtitulo='$subtitulo', cep='$cep', endereco='$endereco', numero='$numero', complemento='$complemento', bairro='$bairro', cidade='$cidade', estado='$estado', lat='$lat', lng='$lng' WHERE codigo='$codigo'");

		if(!empty($_FILES)) {
			if(!empty($_FILES["pin"]) && !empty($_FILES['pin']['tmp_name'])) {
				// DELETA O ARQUIVO ANTIGO
				$pinOld = "../".$row->pin;	
				$pinOld<>'' && is_file($pinOld) ? unlink($pinOld) : false;

				if(!empty($_FILES['pin']['tmp_name'])) {
					$tmp_name = $_FILES['pin']['tmp_name'];
					$arquivoParts  = pathinfo($_FILES['pin']['name']);
					$extensao = $arquivoParts['extension'];
					$destinoFinal = $diretorio.$codigo.".".$extensao;

					move_uploaded_file($tmp_name, $destinoFinal);

					mysql_query("UPDATE $nomeDaPagina SET pin='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
				}
			}
		}
	}

	echo "<script>$(location).attr({'href':'$urlPadrao', 'target':'paginaTarget'})</script>";
} ?>
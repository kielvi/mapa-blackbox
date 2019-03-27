<?php require_once("sessao.php");

$sql_tipos = mysql_query("SELECT DISTINCT(tipo) as nome FROM oportunidades WHERE tipo != ''");
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

		<div class="container adicionar">
			<form id="form-adicionar" name="form-adicionar" method="post" action="" enctype="multipart/form-data">
				<input id="acaoForm" name="acaoForm" type="hidden" value="<?php echo $acao; ?>" />

				<div class="row">

					<div class="col-md-5 form-group">
						<label for="foto">Foto principal</label>
						<input type="file" id="foto" name="foto" data-codigo="<?php echo $row->codigo; ?>" data-tabela="<?php echo $nomeDaPagina; ?>" class="dropify" <?php echo ($row->foto<>'' ? "data-default-file='../$row->foto'" : ''); ?> data-max-file-size="1M" data-allowed-file-extensions="png jpg" />
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
					<div class="col-md-2 form-group">
						<label for="tipo">Tipo</label>
						<select class="form-control js-select2-tag" id="tipo" name="tipo" >
							<?php while($row_tipo = mysql_fetch_object($sql_tipos)) { ?>
								<option value="<?php echo $row_tipo->nome; ?>" <?php echo ( $row->tipo==$row_tipo->nome ? "selected" : false); ?>><?php echo $row_tipo->nome; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-2 form-group">
						<label for="valor">Valor</label>
						<input type="text" class="form-control" id="valor" name="valor" value="<?php echo $row->valor; ?>" onkeypress="return formatar_moeda(this, '.', ',', event);">
					</div>
					<div class="col-md-7 form-group">
						<label for="titulo">Titulo</label>
						<input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $row->titulo; ?>" required>
					</div>
					<div class="col-md-3 form-group">
						<label for="endereco">endereco</label>
						<input onfocusout="searchMap(this.value)" type="text" class="form-control" id="endereco" name="endereco" value="<?php echo $row->endereco; ?>">
					</div>
					<div class="col-md-2 form-group">
						<label for="lat">Lat</label>
						<input type="text" class="form-control" id="lat" name="lat" value="<?php echo $row->lat; ?>">
					</div>
					<div class="col-md-2 form-group">
						<label for="lng">Lng</label>
						<input type="text" class="form-control" id="lng" name="lng" value="<?php echo $row->lng; ?>">
					</div>
					<div class="col-md-12 form-group">
						<div id="map" style="height:300px; background:#FFFFFF;"></div>
					</div>
					<div class="col-md-12 form-group">
						<label for="descricao">Descrição</label>
						<textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo $row->descricao; ?></textarea>
					</div>
					<div class="col-md-12 form-group">
						<label for="diferenciais">Diferenciais</label>
						<textarea class="form-control" id="diferenciais" name="diferenciais" rows="3"><?php echo $row->diferenciais; ?></textarea>
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

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWanCC7ZcurolN37QViXKnHjKzE4bmno0"></script>
<script type="text/javascript" src="../assets/js/gmaps.js"></script>
<script type="text/javascript" src="../assets/js/gmaps-markerclusterer.min.js"></script>

<script type="text/javascript">
$('.dropify').change(function(ev) { $('#paginaTarget').height($('#paginaTarget').contents().height()); });

$('.dropify').dropify({
	messages: {
        'default': 'Arraste e solte um arquivo aqui ou clique para selecionar',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
      	'error':   'Ooops, algo errado foi anexado.'
	}
});

$("textarea").jqte();

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

function searchMap(endereco) {
	GMaps.geocode({
		address: endereco.trim(),
		
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

$('.js-select2-tag').select2({ tags: true });

</script>

<?php // DIRETORIO DA FACJADA
$diretorio = "../fotos_".$sql->nomeDaPagina()."/";

if(isset($acaoForm)) {
	// INSERE NOVO REGISTRO NO BANCO
	if($acaoForm=="adicionar") {
		mysql_query("INSERT INTO $nomeDaPagina (titulo, ordem, endereco, valor, descricao, diferenciais, lat, lng, tipo) VALUES ('$titulo', '$ordem', '$endereco', '$valor', '$descricao', '$diferenciais', '$lat', '$lng', '$tipo')");


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

		mysql_query("UPDATE $nomeDaPagina SET titulo='$titulo', ordem='$ordem', endereco='$endereco', valor='$valor', descricao='$descricao', diferenciais='$diferenciais', lat='$lat', lng='$lng', tipo='$tipo' WHERE codigo='$codigo'");

		if(!empty($_FILES)) {
			if(!empty($_FILES["foto"])) {
				// DELETA O ARQUIVO ANTIGO

				$fileOld = "../".$row->foto;	
				$fileOld<>'' && is_file($fileOld) ? unlink($fileOld) : false;

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
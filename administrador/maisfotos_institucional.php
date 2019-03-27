<?php require_once("sessao.php");


if($acao=="enviar") {	
	// DIRETORIO DO ARQUIVO ANEXO
	$diretorio = "../fotos_".$nomeDaPagina."/";

	mysql_query("INSERT INTO ".$nomeDaPagina."_fotos (cod_institucional) VALUES ('$codigo')");
	$lastid = mysql_fetch_array(mysql_query("SELECT LAST_INSERT_ID()"));
	$codigoFoto = $lastid[0];

	// ENVIA O ARQUIVO SE EXISTIR
	if(!empty($_FILES)) {
		$arquivoTemporario = $_FILES['arquivo']['tmp_name'];
		$arquivoParts  = pathinfo($_FILES['arquivo']['name']);
		$extensao = $arquivoParts['extension'];
		$destinoFinal = $diretorio.$codigo."/".$codigoFoto.".".$extensao;

		move_uploaded_file($arquivoTemporario, $destinoFinal);
		mysql_query("UPDATE ".$nomeDaPagina."_fotos SET foto='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigoFoto'");
	}
} elseif($acao=="editar-legenda") {
	mysql_query("UPDATE ".$nomeDaPagina."_fotos SET legenda='{$legenda}' WHERE codigo='$codigoLegenda'");	
}

// DELETA UM ITEM
if($acao=="deletar") {
	$delete = mysql_query("DELETE FROM ".$nomeDaPagina."_fotos WHERE codigo='$codigoLegenda'");
	// Verifica se existe um arquivo, então o deleta
	if(is_file($arquivoDelete)) unlink($arquivoDelete);
}


// SELECIONA TODAS ROWS DO BD
$max = 48;
$p = $p+0;
$in = $p*$max;
$total = @mysql_num_rows(mysql_query("SELECT * FROM ".$nomeDaPagina."_fotos WHERE cod_institucional='{$codigo}' ORDER BY codigo DESC"));
$pagina = (ceil($total/$max)-1);
$query = mysql_query("SELECT * FROM ".$nomeDaPagina."_fotos WHERE cod_institucional='{$codigo}' ORDER BY codigo DESC LIMIT $in,$max");
$totalItens = mysql_num_rows($query);
 ?>

<link rel="stylesheet" href="css/fotos.css">
<link rel="stylesheet" href="css/responsive.css">

<div class="content fotos-bg">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="capa.php" target="paginaTarget">Página Inicial</a></li>
		<li class="breadcrumb-item"><a href="<?php echo $urlPadrao; ?>" target="paginaTarget">Listagem de <?php echo $sql->admPagina($tabela); ?></a></li>
		<li class="breadcrumb-item active">Galeria de Fotos</li>
	</ol>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-9"><h1>Fotos de <b>Imóveis</b></h1></div>
			<div class="col-md-3 listagem-buttons">
				<a href="<?php echo $urlPadrao; ?>" target="paginaTarget"><span>VOLTAR</span></a>
			</div>
		</div>
		<div class="container fotos">
			<section class="col-sm-12 col-md-6 item" style="background-color:white;">
				<form id="form-adicionar" name="form-adicionar" method="post" action="" enctype="multipart/form-data">
					<input type="hidden" id="acao" name="acao" value="enviar">
					<input type="file" id="input-file-now" name="arquivo" class="dropify" data-max-file-size="1M" data-allowed-file-extensions="jpg png" data-show-remove="false" />
					<button type="submit">Enviar foto</button>
				</form>
			</section>

			<?php if($totalItens>0) { 
				while($row = mysql_fetch_object($query)) { ?>
					<section class="col-sm-12 col-md-6 item" style="background-image:url(<?php echo "../".$row->foto; ?>)">
						<h5> </h5>
						<h1><?php echo ($row->legenda<>'' ? $row->legenda : "Sem legenda"); ?></h1>
						<ul>
							<li>
								<button href="<?php echo "../".$row->foto; ?>" class="cbutton cbutton--effect-novak" data-toggle="lightbox">
									<i class="cbutton__icon fa fa-eye"></i>
									<span class="cbutton__text">Minus</span>
								</button>
							</li>
							<li>
								<button type="button" class="cbutton cbutton--effect-novak" data-toggle="modal" data-target="#modal-editar-legenda" data-whatever="<?php echo $row->codigo; ?>">
									<i class="cbutton__icon fa fa-commenting-o"></i>
									<span class="cbutton__text">Minus</span>
								</a>
							</li>
							<li>
								<button class="cbutton cbutton--effect-novak" data-href="maisfotos_<?php echo $nomeDaPagina; ?>.php?codigo=<?php echo $codigo; ?>&nomeDaPagina=<?php echo $nomeDaPagina; ?>&tabela=<?php echo $tabela; ?>&acao=deletar&codigoLegenda=<?php echo $row->codigo; ?>&arquivoDelete=<?php echo "../".$row->foto; ?>&tipo=<?php echo $tipo; ?>" data-toggle="modal" data-target="#confirmar-delete">
									<i class="cbutton__icon fa fa-times"></i>
									<span class="cbutton__text">Minus</span>
								</button>
							</li>
						</ul>
					</section>
			<?php } } ?>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-editar-legenda" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="exampleModalLabel">Nova legenda</h4>
			</div>
			<div class="modal-body">
				<form id="form-legenda" name="form-legenda" action="" method="post">
					<input type="hidden" name="acao" id="acao" value="editar-legenda">
					<input type="hidden" name="codigoLegenda" id="codigoLegenda">
					<div class="form-group">
						<label for="legenda" class="form-control-label">Legenda:</label>
						<input type="text" class="form-control" id="legenda" name="legenda" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary" form="form-legenda">Confirmar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmar-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">Você tem certeza que quer deletar este item?</div>
			<div class="modal-footer">
				<a data-dismiss="modal">Cancelar</a>
				<a class="btn-ok">Deletar</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#modal-editar-legenda').on('show.bs.modal', function (event) {
	var codigoLegenda = $(event.relatedTarget).data('whatever');
	$(this).find('.modal-body input[name="codigoLegenda"]').val(codigoLegenda);
})
$('#confirmar-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('.dropify').dropify({
	messages: {
        'default': 'ADICIONAR NOVA FOTO',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
      	'error':   'Ooops, algo errado foi anexado.'
	}
});
$('input[id="input-file-now"]').change(function(ev) { 
	$('.item button[type="submit"]').animate({ bottom:"30px" }, 2000)
	$('#paginaTarget').height($('#paginaTarget').contents().height()); 
});

(function() {

// http://stackoverflow.com/a/11381730/989439
function mobilecheck() {
var check = false;
(function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
return check;
}

var support = { animations : Modernizr.cssanimations },
animEndEventNames = { 'WebkitAnimation' : 'webkitAnimationEnd', 'OAnimation' : 'oAnimationEnd', 'msAnimation' : 'MSAnimationEnd', 'animation' : 'animationend' },
animEndEventName = animEndEventNames[ Modernizr.prefixed( 'animation' ) ],
onEndAnimation = function( el, callback ) {
var onEndCallbackFn = function( ev ) {
if( support.animations ) {
if( ev.target != this ) return;
this.removeEventListener( animEndEventName, onEndCallbackFn );
}
if( callback && typeof callback === 'function' ) { callback.call(); }
};
if( support.animations ) {
el.addEventListener( animEndEventName, onEndCallbackFn );
}
else {
onEndCallbackFn();
}
},
eventtype = mobilecheck() ? 'touchstart' : 'click';

[].slice.call( document.querySelectorAll( '.cbutton' ) ).forEach( function( el ) {
el.addEventListener( eventtype, function( ev ) {
classie.add( el, 'cbutton--click' );
onEndAnimation( classie.has( el, 'cbutton--complex' ) ? el.querySelector( '.cbutton__helper' ) : el, function() {
classie.remove( el, 'cbutton--click' );
} );
} );
} );

})();
</script>
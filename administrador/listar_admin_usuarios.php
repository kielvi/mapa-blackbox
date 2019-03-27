<?php require_once("sessao.php");

// ATIVA/DESATIVA BOTOES
$buttonDel  = TRUE;
$buttonEdit = TRUE;

// DELETA UM ITEM
if($acao=="deletar") {
	$delete = mysql_query("DELETE FROM $nomeDaPagina WHERE codigo='$codigo'");

	// Verifica se existe um arquivo, então o deleta
	if(is_file($arquivoDelete)) unlink($arquivoDelete);

	// DELETA AS PERMISSOES ANTERIORES
	$selPerm = mysql_query("SELECT * FROM admin_permissoes WHERE cod_usuario='{$codigo}'");
	while($row_selPerm = mysql_fetch_object($selPerm)) { mysql_query("DELETE FROM admin_permissoes WHERE codigo='$row_selPerm->codigo'"); }

	echo "<script>$(location).attr({'href':'$urlPadrao', 'target':'paginaTarget'})</script>";
}

// GRAVA AS BUSCAS
(isset($buscar) && $buscar<>"" ? (@in_array($buscar, $_SESSION["buscas"]) ? "" : $_SESSION["buscas"][] = $buscar) : "");

// SELECIONA TODAS ROWS DO BD
$max = 12;
$p = $p+0;
$in = $p*$max;
$total = @mysql_num_rows(mysql_query("SELECT * FROM {$nomeDaPagina} WHERE 1=1 ".($buscar<>'' ? " AND (nome LIKE '%$buscar%' OR email LIKE '%$buscar%')" : "")." ORDER BY codigo DESC"));
$pagina = (ceil($total/$max)-1);
$query = mysql_query("SELECT * FROM {$nomeDaPagina} WHERE 1=1 ".($buscar<>'' ? " AND (nome LIKE '%$buscar%' OR email LIKE '%$buscar%')" : "")." ORDER BY codigo DESC LIMIT $in,$max");
$totalItens = mysql_num_rows($query); ?>

<link rel="stylesheet" href="css/listagem.css">
<link rel="stylesheet" href="css/responsive.css">

<div class="content listagem">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="capa.php" target="paginaTarget">Página Inicial</a></li>
		<li class="breadcrumb-item active">Listagem de <?php echo $sql->admPagina($tabela); ?></li>
	</ol>

	<div class="container-fluid">
		<div class="col-md-7"><h1>Listagem de <b><?php echo $sql->admPagina($tabela); ?></b></h1></div>
		<div class="col-md-5 listagem-buttons">
			<a href="<?php echo "adicionar_$nomeDaPagina.php?acao=adicionar&nomeDaPagina=$nomeDaPagina&tabela=$tabela"; ?>"><span>ADICIONAR NOVO</span></a>

			<?php require_once("busca.php"); ?>
		</div>
		<div class="listagem-table">
			<?php if($totalItens>0) { 
				while($row = mysql_fetch_object($query)) { ?>
					<article class="col-md-3 col-sm-4">
						<div class="listagem-item">
							<figure><a href="<?php echo ($row->foto<>'' ? $row->foto : "images/nophoto.svg"); ?>" data-toggle="lightbox" style="background-image:url(<?php echo ($row->foto<>'' ? $row->foto : "images/nophoto.svg"); ?>);"> </a></figure>
							<h1><?php echo $App->SubStrTexto($row->nome, 50); ?></h1>
							<p><?php echo $row->email; ?></p>

							<div class="col-xs-12 listagem-item-buttons" align="center">
								<a class="<?php echo ($buttonEdit==FALSE ? "hidden-xs-up" : ""); ?>" href="<?php echo "adicionar_$nomeDaPagina.php?codigo=$row->codigo&acao=editar&nomeDaPagina=$nomeDaPagina&tabela=$tabela"; ?>" target="paginaTarget"><i class="fa fa-fw fa-pencil-square-o" aria-hidden="true"></i><span>Editar</span></a>
								<a class="<?php echo ($buttonDel==FALSE ? "hidden-xs-up" : ""); ?>" href="javascript:void(0)" data-href="<?php echo $urlPadrao; ?>&codigo=<?php echo $row->codigo; ?>&acao=deletar&arquivoDelete=<?php echo $row->foto; ?>" data-toggle="modal" data-target="#confirmar-delete"><i class="fa fa-fw fa-trash-o"></i><span>Apagar</span></a>
							</div>
						</div>
					</article>
			<?php } 
			} else { ?>
				<div class="alert alert-danger" role="alert"><?php echo ($totalItens==0 && !isset($buscar) ? "Nenhum item cadastrado até o momento" : "Nenhum item com o termo <b>\"$buscar\"</b> encontrado."); ?></div>
			<?php } ?>
		</div>
		<div class="listagem-pag">
			<?php $menos=$p-1;
			$mais= $p+1;
			$quant_pg=ceil($total/$max); ?>
			<ul>
				<li><button onclick="$(location).attr({'href':'<?php echo $urlPadrao."&p=$menos"; ?>', 'target':'paginaTarget'});" class="paginate left" <?php echo ($menos>=0 ? '' : 'data-state="disabled"'); ?>><i></i><i></i></button></li>
				<li><?php echo (($p+1)." / ".$quant_pg); ?></li>
				<li><button onclick="$(location).attr({'href':'<?php echo $urlPadrao."&p=$mais"; ?>', 'target':'paginaTarget'});" class="paginate right" <?php echo ($mais<$quant_pg ? '' : 'data-state="disabled"'); ?>><i></i><i></i></button></li>
			</ul>
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
$('#confirmar-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

(function() {

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

	[].slice.call( document.querySelectorAll( '.listagem-item a' ) ).forEach( function( el ) {
		el.addEventListener( eventtype, function( ev ) {
			classie.add( el, 'button-click' );
			onEndAnimation( classie.has( el, 'cbutton--complex' ) ? el.querySelector( '.cbutton__helper' ) : el, function() {
				classie.remove( el, 'button-click' );
			} );
		} );
	} );


	var morphSearch = document.getElementById( 'morphsearch' ),
	input = morphSearch.querySelector( 'input.morphsearch-input' ),
	ctrlClose = morphSearch.querySelector( 'span.morphsearch-close' ),
	isOpen = isAnimating = false,

	// show/hide search area
	toggleSearch = function(evt) {
		// return if open and the input gets focused
		if( evt.type.toLowerCase() === 'focus' && isOpen ) return false;
			var offsets = morphsearch.getBoundingClientRect();
			if( isOpen ) {

				classie.remove( morphSearch, 'open' );

				// trick to hide input text once the search overlay closes 
				// todo: hardcoded times, should be done after transition ends
				if( input.value !== '' ) {
					setTimeout(function() {
						classie.add( morphSearch, 'hideInput' );
						setTimeout(function() {
							classie.remove( morphSearch, 'hideInput' );
							input.value = '';
						}, 300 );
					}, 500);
				}
				//input.blur();
				$(".listagem-buttons").delay(1000).css("position", "relative")
			} else {
				$(".listagem-buttons").css("position", "inherit")
				classie.add( morphSearch, 'open' );
			}
			isOpen = !isOpen;
		};
		// events
		input.addEventListener( 'focus', toggleSearch );
		ctrlClose.addEventListener( 'click', toggleSearch );
		// esc key closes search overlay
		// keyboard navigation events
		document.addEventListener( 'keydown', function( ev ) {
			var keyCode = ev.keyCode || ev.which;
			if( keyCode === 27 && isOpen ) {
				toggleSearch(ev);
			}
		});
		/***** for demo purposes only: don't allow to submit the form *****/
		//morphSearch.querySelector( 'button[type="submit"]' ).addEventListener( 'click', function(ev) { ev.preventDefault(); } );
	})();
	$('#paginaTarget').height($('#paginaTarget').contents().height());
</script>
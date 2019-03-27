<?php require_once("sessao.php");

// SELECIONA OS MODULOS
$query_menuModulos = mysql_query("SELECT AM.codigo AS codigoAM, AM.modulo AS moduloAM, AM.icone AS iconeAM FROM admin_permissoes AS AP LEFT JOIN admin_tabelas AS AT ON AP.cod_tabela= AT.codigo LEFT JOIN admin_modulos AS AM ON AT.cod_modulo = AM.codigo WHERE cod_usuario='$codigoUsuario' AND AM.codigo<>'' GROUP BY AT.cod_modulo ORDER BY AM.modulo ASC"); ?>

<div class="menu-bg">
	<svg class="login-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 360">
		<path fill="#999999" d="M137.5 217.04h89.225v17.744H191.01v89.543h-17.784V234.89H137.5v-17.85zm155.622-52.234c-.09-15.194-.053-30.395-.04-45.594 0-.938.08-1.845.138-3.373 24.71 21.974 48.962 43.577 73.514 65.392V73.92c-5.308 4.142-10.227 8.094-15.275 11.845-1.746 1.315-2.44 2.682-2.417 4.878.08 15.814.057 31.63.057 47.438v3.406c-24.938-22.848-49.207-45.106-73.826-67.672v107.552c5.455-4.263 10.494-8.308 15.664-12.168 1.584-1.18 2.202-2.386 2.184-4.392zM430.118 0v360H69.882V0h360.236zm-11.35 11.392H81.27v337.43h337.497V11.39zm-56.72 261.604c6.4-7.15 8.575-15.675 8.466-25.107-.215-18.68-12.52-31.066-31.225-31.066H289.035v18.01h3.362c15.64 0 31.292.03 46.92 0 4.69-.024 8.653 1.505 11.122 5.574 3.08 5.122 3.027 10.642.56 15.843-2.32 4.902-6.78 6.595-12.137 6.57-15.55-.08-31.104-.032-46.665-.032h-3.23V280.8h3.084c11.377 0 22.742.03 34.13-.05 1.825-.032 2.846.532 3.73 2.168 6.754 12.492 13.718 24.896 20.3 37.47 1.688 3.214 3.55 4.394 7.12 4.15 4.9-.32 9.832-.08 15.192-.08-8.097-14.737-15.92-29-23.88-43.496 5.547-1.37 9.872-4.047 13.403-7.966zM168.977 92.892c9.383-2.168 18.358-1.602 25.76 6.037 4.706-3.65 9.422-7.333 14.273-11.103-.417-.534-.66-.897-.937-1.205-15.493-17.3-42.017-15.3-55.668-5.624-15.13 10.72-16.03 33.728-1.703 44.914 5.267 4.1 11.403 6.326 17.97 7.64 4.853.97 9.7 2.146 14.367 3.808 4.206 1.498 7.1 4.662 7.742 9.378.965 7.185-3.11 13.64-10.306 15.624-9.04 2.495-17.206.618-24.334-6.028-4.73 3.673-9.474 7.37-14.313 11.125.35.476.578.827.873 1.148.588.67 1.18 1.343 1.85 1.933 14.096 12.54 30.068 13.964 46.897 7.02 14.425-5.95 21.93-22.837 17.562-37.87-3.555-12.222-12.52-18.253-24.27-20.92-5.025-1.135-10.147-1.767-15.184-2.827-6.503-1.34-9.7-4.838-9.918-10.412-.227-6.762 2.638-11.08 9.338-12.638zm42.363-50.116c.163-2.467-.81-2.908-3.044-2.896-22.822.07-45.626.042-68.437.058-.94 0-1.875.08-2.76.134v12.594h74.185c0-3.514-.174-6.725.056-9.89z"/>
	</svg>
	<div class="menu">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<?php $m=0; while($row_menuModulos = mysql_fetch_object($query_menuModulos)) { ?>
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="<?php echo "H".$row_menuModulos->codigoAM; ?>">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#<?php echo "M".$row_menuModulos->codigoAM; ?>" aria-expanded="false" aria-controls="<?php echo "M".$row_menuModulos->codigoAM; ?>">
								<i class="fa <?php echo ($row_menuModulos->iconeAM<>'' ? $row_menuModulos->iconeAM : "fa-plus"); ?>" aria-hidden="true"></i><p><?php echo $row_menuModulos->moduloAM; ?></p>
							</a>
						</h4>
					</div>
					<div id="<?php echo "M".$row_menuModulos->codigoAM; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="<?php echo "H".$row_menuModulos->codigoAM; ?>">
						<div class="panel-body">
							<ul>
								<?php $query_menuTabelas = mysql_query("SELECT TB.*, TB.codigo AS codigoTB, TB.cod_modulo AS cod_moduloTB, TB.item AS itemTB, TB.url as urlTB, AP.* FROM admin_tabelas AS TB LEFT JOIN admin_permissoes AS AP ON TB.codigo = AP.cod_tabela WHERE TB.cod_modulo='{$row_menuModulos->codigoAM}' AND AP.cod_usuario='{$codigoUsuario}}' ORDER BY TB.item ASC");
								while($row_menuTabelas = mysql_fetch_object($query_menuTabelas)) { ?>
									<li><a href="<?php echo $row_menuTabelas->urlTB.(strpos($row_menuTabelas->urlTB,"?")!==false ? "&" : "?")."modulo=$row_menuTabelas->cod_moduloTB&tabela=$row_menuTabelas->codigoTB"; ?>" target="paginaTarget"><i class="fa fa-bars" aria-hidden="true"></i><p><?php echo $row_menuTabelas->itemTB; ?></p></a></li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			<?php $m++; } ?>
		</div>

	</div>
</div>
<button class="close-button" id="close-button">Fechar menu</button>
<div class="morph-shape" id="morph-shape" data-morph-open="M-1,0h101c0,0,0-1,0,395c0,404,0,405,0,405H-1V0z">
	<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100 800" preserveAspectRatio="none">
		<path d="M-1,0h101c0,0-97.833,153.603-97.833,396.167C2.167,627.579,100,800,100,800H-1V0z"/>
	</svg>
</div>
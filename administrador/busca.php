
<div id="morphsearch" class="morphsearch">
	<form id="form-buscar" name="form-buscar" target="paginaTarget" action="" method="post" class="morphsearch-form" autocomplete="off">
		<input id="buscar" name="buscar" class="morphsearch-input" type="search" placeholder="Buscar..."/>
		<button class="morphsearch-submit" type="submit">Buscar</button>
	</form>
	<div class="morphsearch-content">
		<div class="historico-column">
			<?php if(isset($_SESSION[buscas])) {
				echo "<h2>BUSCAS ANTERIORES</h2>";
				krsort($_SESSION["buscas"]);
				forEach($_SESSION["buscas"] as $key=>$itemBusca) { ?>
					<a href="javascript:void(0)" onclick="$('#buscar').val(($(this).find('h3').html()))"> <h3><?php echo $itemBusca; ?></h3></a>
			<?php } 
			} ?>
		</div>
	</div>
	<span class="morphsearch-close"></span>
</div>
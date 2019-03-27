<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Mapa de empreendimentos</title>
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/owl.carousel.min.css">
</head>
<body style="background: #232323;">

	<div class="mapa" data-component="mapa" data-url="empreendimentos.php" data-latlng="-27.13348769,-48.60513994" style="margin-bottom: 300px">
		<div class="mapa_mapa"></div>

		<div class="mapa_info is-updating">
			<div class="mapa_info-titulo"></div>
			<div class="mapa_info-subtitulo"></div>
			<div class="mapa_info-endereco"></div>
			<div class="mapa_fotos">
				<div class="mapa_scene" id="scene">
					<div class="mapa_scene_cloud-1 -bg" data-depth="0.2"></div>
					<div class="mapa_scene_cloud-2 -bg" data-depth="0.4"></div>
					<div class="mapa_scene_cloud-3 -bg" data-depth="0.6"></div>
					<div class="mapa_scene_birds -bg" data-depth="0.1"></div>
				</div>
				<div class="owl-carousel owl-theme">
					<div class='item' style='background-image:url(imagens/dmarco-fachada.png)'></div>
				</div>
			</div>
		</div>

		<div class="mapa_popup">
			<div class="mapa_popup-titulo"></div>
			<div class="mapa_popup-subtitulo"></div>
		</div>
	</div>
	

	<script src="assets/js/jquery-3.3.1.min.js"></script>
	<script src="assets/js/owl.carousel.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/parallax/3.1.0/parallax.min.js"></script>
	<script src="assets/js/require.js" data-main="assets/js/Component"></script>
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
</body>
</html>
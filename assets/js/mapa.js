define('mapa', ['Component'], function(Component){
	var component = new Component('mapa');

	component.install = install;
	component.bootstrap();

	return component;

	function loadPopup(self){
		require(['popupClass'], function(){ install.call(self); })
	}

	function loadMarkers(self){
		$.get( self.attributes['data-url'].value ).done(function(markers){
			install.call(self, markers)
		})
	}

	function getLatLng(element){
		var parts = element.attributes['data-latlng'].value.split(',');
		return { lat: parts[0], lng: parts[1] };
	}

	function install(markers){
		if ( !window.google || !window.google.maps ) return download(this);
		if ( !window.Popup ) return loadPopup(this);
		if ( this.attributes['data-url'] && !markers ) return loadMarkers(this);

$(".owl-carousel").owlCarousel({ items:1 })

var scene = document.getElementById('scene');
var parallaxInstance = new Parallax(scene, {
  relativeInput: true
});

		var self = this;
		self.addEventListener('mouseleave', closePins)
		this.addMarkers = addMarkers;
		this.popup = this.querySelector('.mapa_popup');
		this.mapa = this.querySelector('.mapa_mapa');
		this.info = this.querySelector('.mapa_info');
		this.fotos = this.querySelector('.mapa_fotos .owl-carousel');
		this.center = getLatLng(this);
		this.gpopup = new Popup( new google.maps.LatLng(-27.13348769, -48.60513994), this.popup );
		this.gmap = new google.maps.Map(this.mapa, {
      zoom: 14,
      center: {lat: -27.13348769, lng: -48.60513994},
  		disableDefaultUI: true,
      styles: getMapsStyles()
    });

  	if(markers) self.addMarkers( markers );
	}

	function closePins(){
		this.gpopup.setMap(null);
		this.activeMarker = null;
		$('.mapa_info').removeClass('is-active').addClass('is-updating');
	}
	
	function download(self){
		var fnName = '_mapa_' + new Date().getTime();
		window[fnName] = installTo;

		var script = document.createElement("SCRIPT");
		script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAmgdZbKvbsB5hmSpvO1Bc9UtLPbJQeQPw&callback=' + fnName;
		document.body.appendChild( script );

		function installTo(){ install.call(self); }
	}
	
	function addMarkers( markerList ){
		var self = this;

		markerList.forEach(function(e,i,a){
			e.icon = 'assets/img/box.png';
			e.map = self.gmap;
			e.position = new google.maps.LatLng(parseFloat(e.lat), parseFloat(e.lng));
			var m = new google.maps.Marker(e);
			m.addListener('mouseover', onMarkerHover );
			
		})

		function onMarkerClick(){
			//self.gmap.setCenter( this.getPosition() );
			var _this = this;
			var info = $( self.info );

			if( !info.is('.is-active') ){
				info.addClass('is-active');
				return  setTimeout(function(){
					onMarkerClick.call(_this);
				}, 300);
			} 
			
			info.addClass('is-updating');

			setTimeout(function(){
				$(self.fotos).owlCarousel('destroy');
				$(self.fotos).html(
					_this.data.images.map(function(img){
						return "<div class='item' style='background-image:url("+img+")'></div>";
						/*return "<div class='item' style='background-image:url(imagens/dmarco-fachada.png)'></div>";*/
					}).join('')
				).owlCarousel({items:1});
				$('.mapa_info-endereco', self.info).html( _this.data.address );
				$('.mapa_info-titulo', self.info).html( _this.data.title );
				$('.mapa_info-subtitulo', self.info).html( _this.data.subtitle );

				setTimeout(function(){
					info.removeClass('is-updating');
				}, 400);

			}, 300);
		}

		function onMarkerHover(){
			if(self.activeMarker == this) return;
			self.activeMarker = this;
			self.gpopup.setMap(null);
			$( self.gpopup._content.parentElement ).removeClass('is-active');
			self.gpopup.position = this.position;
			self.popup.querySelector('.mapa_popup-titulo').innerHTML = this.data.title;
			self.popup.querySelector('.mapa_popup-subtitulo').innerHTML = this.data.subtitle;
			self.gpopup.setMap( self.gmap );
			onMarkerClick.call(this);
			setTimeout(function(){
				$( self.gpopup._content.parentElement ).addClass('is-active');	
			}, 100);
			
		}
		
	}

	function getMapsStyles(){
		return [
				{"featureType": "all","elementType": "labels.text.fill","stylers": [{"saturation": 36},{"color": "#000000"},{"lightness": 40}]},    
				{"featureType": "all","elementType": "labels.text.stroke","stylers": [{"visibility": "on"},{"color": "#000000"},{"lightness": 16}]},    
				{"featureType": "all","elementType": "labels.icon","stylers": [{"visibility": "off"}]},    
				{"featureType": "administrative","elementType": "geometry.fill","stylers": [{"color": "#000000"},{"lightness": 20}]},    
				{"featureType": "administrative","elementType": "geometry.stroke","stylers": [{"color": "#000000"},{"lightness": 17},{"weight": 1.2}]},    
				{"featureType": "landscape","elementType": "geometry","stylers": [{"color": "#000000"},{"lightness": 20}]},    
				{"featureType": "poi","elementType": "geometry","stylers": [{"color": "#000000"},{"lightness": 21}]},    
				{"featureType": "road.highway","elementType": "geometry.fill","stylers": [{"color": "#000000"},{"lightness": 17}]},    
				{"featureType": "road.highway","elementType": "geometry.stroke","stylers": [{"color": "#000000"},{"lightness": 29},{"weight": 0.2}]},
				{"featureType": "road.arterial","elementType": "geometry","stylers": [{"color": "#000000"},{"lightness": 18}]},
				{"featureType": "road.local","elementType": "geometry","stylers": [{"color": "#000000"},{"lightness": 16}]},
				{"featureType": "transit","elementType": "geometry","stylers": [{"color": "#000000"},{"lightness": 19}]},
				{"featureType": "water","elementType": "geometry","stylers": [{"color": "#000000"},{"lightness": 17}]}
		];
	}

})
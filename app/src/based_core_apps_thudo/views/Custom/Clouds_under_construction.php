<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en" class="">
	<head>
		<!-- PAGE TITLE -->
		<title>{title} - {site_name}</title>
		<base href="{url_assets}">
		<!-- META/CSS/JS -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
		<meta name="description" content="{heading}">
		<meta name="author" content="{site_author}"/>
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="js/modernizr.webgl.js"></script>
		<!-- // <script type="text/javascript" src="js/jquery.transit.js"></script> -->
		<script type="text/javascript" src="js/min/site.min.js"></script>
		<script type="text/javascript" src="js/three.min.js"></script>
		<!-- // <script type="text/javascript" src="js/min/Detector.min.js"></script>		 -->
		<script type="text/javascript" src="js/min/clouds.min.js"></script>
		<script type="text/javascript" src="js/min/fallback.min.js"></script>
		<link rel="stylesheet" href="css/reset.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/icons.css">
		<link rel="stylesheet" href="css/fallback.css">
	</head>
	<body class="">
		<!-- <div class="logo"></div> -->
		<div class="no-webgl-only message">Your browser don't support 3d animations on this page. <a href="http://browsehappy.com/" target="_blank">Check modern browsers</a></div>
		<div class="fallback-clouds"></div>
		<!-- SOCIAL ICONS WITH LINKS -->
		<div class="socials transit">
			<!-- HREF IS URL fa-name is icon name and can be replaced with any name from http://fortawesome.github.io/Font-Awesome/icons/ -->
			<a href="{url_facebook}" target="_blank"><div class="social transit"><i class="fa fa-facebook"></i></div></a>
			<a href="{url_twitter}" target="_blank"><div class="social transit"><i class="fa fa-twitter"></i></div></a>
			<a href="{url_briefcase}" title="Hire me"><div class="social transit"><i class="fa fa-briefcase"></i></div></a>
			<a href="{url_transit}" target="_blank" title="Get this item"><div class="social transit"><i class="fa fa-cloud-download"></i></div></a>
		</div>
		<!-- END SOCIAL ICONS -->
		<!-- PREVIEW OF BACKGROUNDS - REMOVE IF DONT NEEDED -->
		<div class="preview transit">
			<div class="next"><i class="fa fa-angle-double-right"></i></div>
			<div class="prev"><i class="fa fa-angle-double-left"></i></div>
		</div>
		<!-- END OF PREVIEW OF BACKGROUNDS - REMOVE IF DONT NEEDED -->
		<!-- SHADERS -->
		<script id="vs" type="x-shader/x-vertex">
			varying vec2 vUv;
			void main() {
				vUv = uv;
				gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );
			}
		</script>
		<script id="fs" type="x-shader/x-fragment">
			uniform sampler2D map;
			uniform vec3 fogColor;
			uniform float fogNear;
			uniform float fogFar;
			varying vec2 vUv;
			void main() {
				float depth = gl_FragCoord.z / gl_FragCoord.w;
				float fogFactor = smoothstep( fogNear, fogFar, depth );
				gl_FragColor = texture2D( map, vUv );
				gl_FragColor.w *= pow( gl_FragCoord.z, 20.0 );
				gl_FragColor = mix( gl_FragColor, vec4( fogColor, gl_FragColor.w ), fogFactor );
			}
		</script>
	</body>
</html>
<!DOCTYPE html>
<html>
	<head>
		<title>FlexiTrygg</title>
		<link rel="stylesheet" type="text/css" href="style.css"/>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript" src="script.js"></script>
		<script type="text/javascript" src="connection.js"></script>
		<link href="http://fonts.googleapis.com/css?family=Quattrocento+Sans" rel="stylesheet" type="text/css" media="screen"></link>
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta charset="utf-8"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<!-- iPad (Retina) -->
		<link rel="apple-touch-icon"
			  sizes="144x144"
			  href="144.png">
		<!-- iPad (portrait) -->
		<link rel="apple-touch-startup-image"
			  media="(device-width: 768px)
				 and (orientation: portrait)"
			  href="768x1004.png">
		<!-- iPad (landscape) -->
		<link rel="apple-touch-startup-image"
			  media="(device-width: 768px)
				 and (orientation: landscape)"
			  href="1024x748.png">
		<!-- iPad (Retina, portrait) -->
		<link rel="apple-touch-startup-image"
			  media="(device-width: 768px)
				 and (orientation: portrait)
				 and (-webkit-device-pixel-ratio: 2)"
			  href="1536x2008.png">
		<!-- iPad (Retina, landscape) -->
		<link rel="apple-touch-startup-image"
			  media="(device-width: 768px)
				 and (orientation: landscape)
				 and (-webkit-device-pixel-ratio: 2)"
			  href="1496x2048.png">
	</head>
	<body>
		<header>
			<div class="left" id="userInfo">
				<img src="caregiver.png" alt="Caregiver"/>
				<hgroup>
					<h3>Susanne Hermansson</h3>
					<h4>Vårdgivare</h4>
				</hgroup>
			</div>
			<div class="right">
				<div class="status selector">
					<div class="option free">Tillgänglig</div>
					<div class="selected option busy">Upptagen</div>
					<div class="option offDuty">Hemma</div>
					<div class="option auto">Automatisk</div>
				</div>
			</div>
			<div class="center active" id="emergency">Larm</div>
			<div class="clear"></div>
		</header>
		<section class="left" id="schedule">
			<div class="sorting selector">
				<div class="selected option time">Tid</div>
				<div class="option patient">Patient</div>
				<div class="option past">Passerat</div>
			</div>
			<div class="scrollbox">
				<div class="scheduleItem green">
					<div class="statusIndicator"></div>
					<div class="date">16 nov 2012</div><div class="time">09:54</div>
					<h1>Agda Svensson</h1>
					<div class="address">
						Storgatan 13<br/>
						60233 Norrköping
					</div>
				</div>
				<div class="scheduleItem red">
					<div class="statusIndicator"></div>
					<div class="date">16 nov 2012</div><div class="time">11:02</div>
					<h1>Bertil Olsson</h1>
					<div class="address">
						Småvägen 31<br/>
						60288 Norrköping
					</div>
				</div>
				<div class="scheduleItem yellow">
					<div class="statusIndicator"></div>
					<div class="date">16 nov 2012</div><div class="time">09:54</div>
					<h1>Agda Svensson</h1>
					<div class="address">
						Storgatan 13<br/>
						60233 Norrköping
					</div>
				</div>
				<div class="scheduleItem red selected">
					<div class="statusIndicator"></div>
					<div class="date">16 nov 2012</div><div class="time">11:02</div>
					<h1>Bertil Olsson</h1>
					<div class="address">
						Småvägen 31<br/>
						60288 Norrköping
					</div>
				</div>
				<div class="scheduleItem green">
					<div class="statusIndicator"></div>
					<div class="date">16 nov 2012</div><div class="time">09:54</div>
					<h1>Agda Svensson</h1>
					<div class="address">
						Storgatan 13<br/>
						60233 Norrköping
					</div>
				</div>
				<div class="scheduleItem yellow">
					<div class="statusIndicator"></div>
					<div class="date">16 nov 2012</div><div class="time">11:02</div>
					<h1>Bertil Olsson</h1>
					<div class="address">
						Småvägen 31<br/>
						60288 Norrköping
					</div>
				</div>
			</div>
		</section>
		<section id="main">
			<div id="patientInfo">
				<header>
					<img src="patient.png" alt="Patient"/>
					<hgroup>
						<h1>Bertil Olsson</h1>
						<h2>82 år</h2>
					</hgroup>
				</header>
				<div class="tabPane">
					<div class="tabs">
						<div class="tab">Att göra</div>
						<div class="tab selected">Rapporter</div>
						<div class="tab">Statistik</div>
					</div>
					<div class="contents">
						<div class="content">Lista över saker</div>
						<div class="content">Lite rapporter</div>
						<div class="content">Massa statistik</div>
					</div>
				</div>
			</div>
		</section>
	</body>
</html>
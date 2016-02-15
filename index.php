<html>
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Tomasz Pilch">
		<title>Brno temperature!</title>
		<link rel="stylesheet" media="screen,projection,tv" href="style.css">
		<script src="jquery-1.11.1.min.js"></script>
		<script src="script.js"></script>
	</head>
	<body>
		<div class="center">
					<?php
						date_default_timezone_set('Europe/London');
						$logAll = [];
						$temperature = explode('_', file_get_contents('data/temp'));
						$temp1All = str_replace('*', '.', $temperature[0]);
						$temp1Full = explode('*', $temperature[0]);
					
						$temp2All = str_replace('*', '.', $temperature[1]);
						$temp2Full = explode('*', $temperature[1]);
					?>
			<div class="head">
				OUT
			</div>
			<div class="head">
				IN
			</div>
			<div id="temperature1_td" class="temp_div">
				<img src="images/temperature.png" id="temperature1_img" class="temperature1_img">
				<img src="images/deg.png" id="temp1c" class="temperature1_img" onload="AnimateRotate('<?= (180*((float)$temp1All/30.0));?>', '<?= $temp1All?>', this, '#temperature1_value');"/>
				<div class="temperature1">
					<div class="value" id="temperature1_value">
					<?php
						echo $temp1Full[0];
						echo "<span>," . $temp1Full[1] . "</span>";
					?> °C
					</div>
				</div>
			</div>

			<div id="temperature2_td" class="temp_div">
				<img src="images/temperature.png" id="temperature2_img" class="temperature2_img">
				<img src="images/deg.png" id="temp2c" class="temperature2_img" onload="AnimateRotate('<?= (180*((float)$temp2All/30.0));?>', '<?= $temp2All?>', this, '#temperature2_value');"/>
				<div class="temperature2">
					<div class="value" id="temperature2_value">
					<?php
						echo $temp2Full[0];
						echo "<span>," . $temp2Full[1] . "</span>";
					?> °C
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

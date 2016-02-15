<html>
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Tomasz Pilch">
		<title>Brno temperature!</title>
		<link rel="stylesheet" media="screen,projection,tv" href="style.css">
		<script src="jquery-1.11.1.min.js"></script>
		<script src="script.js"></script>
		<script src="Chart.js"></script>
	</head>
	<body>
		<div class="center">
			<?php
				$logs = explode("\n", file_get_contents('data/tempLog'));
				$prevDate = null;

				$lastDate = '';
				$avgs = 1;	
				$avgsa = 1;				
				if (isset($_GET['all'])) {
					for ($i = 0; $i < count($logs); $i++) {
						$log = $logs[$i];
						$log = explode(" -> ", $log);
						$temps = explode("_", str_replace('*', '.', $log[1]));
						$dateTime = explode(" ", $log[0]);
						$time = explode(":", $dateTime[1]);
					
						if ($lastDate != $dateTime[0] && !empty($lastDate)) {
							$logAll['date'] .= '"' . $lastDate . '",';
							$logAll['0t'] /= $avgs;	
							$logAll['0'] .= round($logAll['0t'], 2) . ',';
							$logAll['1t']/= $avgs;
							$logAll['1'] .= round($logAll['1t'], 2) . ',';	
							$logAll['0ta'] /= $avgsa;	
							$logAll['0a'] .= round($logAll['0ta'], 2) . ',';
							$logAll['1ta']/= $avgsa;
							$logAll['1a'] .= round($logAll['1ta'], 2) . ',';
							if (intval($time[0]) >= 6 && intval($time[0]) <= 22) {
								$logAll['0t'] = 0;	
								$logAll['0t'] = $temps[0];
								$logAll['1t'] = 0;	
								$logAll['1t'] = $temps[1];
							} else {
								$logAll['0ta'] = 0;	
								$logAll['0ta'] = $temps[0];	
								$logAll['1ta'] = 0;	
								$logAll['1ta'] = $temps[1];
							}
							$avgs = 1;	
							$avgsa = 1;
							$lastDate = $dateTime[0];
						} else {
							if (intval($time[0]) >= 6 && intval($time[0]) <= 22) {
								$logAll['0t'] += $temps[0];
								$logAll['1t'] += $temps[1];
								$avgs++;
							} else {
								$logAll['0ta'] += $temps[0];
								$logAll['1ta'] += $temps[1];
								$avgsa++;
							}
							$lastDate = $dateTime[0];
						}
					}
				} else if (isset($_GET['day'])) {
					for ($i = (count($logs)-100); $i < count($logs)-1; $i++) {
						$log = $logs[$i];
						$log = explode(" -> ", $log);
						$temps = explode("_", str_replace('*', '.', $log[1]));
						$logAll['0'] .= $temps[0] . ',';
						$logAll['1'] .= $temps[1] . ',';
						$dateTime = explode(" ", $log[0]);

						$day = explode("-", $dateTime[0]);
						$logAll['date'] .= '"' . $day[2]*1 . '. ' . $dateTime[1] . '",';

					}
				} else if (!empty($_GET['from']) && !empty($_GET['to'])) {
					$dateFrom = new DateTime($_GET['from']);
					$dateTo = new DateTime($_GET['to']);
					for ($i = 0; $i < count($logs)-1; $i++) {
						$log = $logs[$i];
						$log = explode(" -> ", $log);
						$dateTime = explode(" ", $log[0]);
						$dateNow = new DateTime($log[0]);
						if ($dateNow >= $dateFrom && $dateNow <= $dateTo) {
							$temps = explode("_", str_replace('*', '.', $log[1]));
							$logAll['0'] .= $temps[0] . ',';
							$logAll['1'] .= $temps[1] . ',';

							$day = explode("-", $dateTime[0]);
							$logAll['date'] .= '"' . $day[2]*1 . ' ' . $dateTime[1] . '",';
							$i += 59;
						}
					}
				} else {
					for ($i = ceil(count($logs)-(30*1439)); $i < count($logs)-1; $i++) {
						$log = $logs[$i];
						$log = explode(" -> ", $log);
							
						$temps = explode("_", str_replace('*', '.', $log[1]));
						$logAll['0'] .= $temps[0] . ',';
						$logAll['1'] .= $temps[1] . ',';
						$dateTime = explode(" ", $log[0]);
						$logAll['date'] .= '"' . $dateTime[0] . '",';
						$i += 1439; // day
					}
				}
				$logAll['0'] = substr($logAll['0'], 0, -1);
				$logAll['1'] = substr($logAll['1'], 0, -1);
				$logAll['date'] = substr($logAll['date'], 0, -1);
			?>
			<script type="text/javascript">
				$(window).load(function() {
					var ctx = $("#canvas").get(0).getContext("2d");
					var myLineChart = new Chart(ctx);
					var data = {
						labels: [<?= $logAll['date']?>],
						datasets: [
							{
								label: "Day OUT",
								fillColor: "rgba(255,64,64,0.2)",
								strokeColor: "rgba(255,64,64,1)",
								pointColor: "rgba(255,64,64,1)",
								pointStrokeColor: "#fff",
								pointHighlightFill: "#fff",
								pointHighlightStroke: "rgba(255,64,64,1)",
								data: [<?= $logAll['0']?>]
							},
							{
								label: "Day IN",
								fillColor: "rgba(151,187,205,0.2)",
								strokeColor: "rgba(151,187,205,1)",
								pointColor: "rgba(151,187,205,1)",
								pointStrokeColor: "#fff",
								pointHighlightFill: "#fff",
								pointHighlightStroke: "rgba(151,187,205,1)",
								data: [<?= $logAll['1']?>]
							},
							{
								label: "Night OUT",
								fillColor: "rgba(255,45,45,0.2)",
								strokeColor: "rgba(255,45,45,1)",
								pointColor: "rgba(255,45,45,1)",
								pointStrokeColor: "#fff",
								pointHighlightFill: "#fff",
								pointHighlightStroke: "rgba(255,64,164,1)",
								data: [<?= $logAll['0a']?>]
							},
							{
								label: "Night IN",
								fillColor: "rgba(127,166,205,0.2)",
								strokeColor: "rgba(127,166,205,1)",
								pointColor: "rgba(127,166,205,1)",
								pointStrokeColor: "#fff",
								pointHighlightFill: "#fff",
								pointHighlightStroke: "rgba(251,187,205,1)",
								data: [<?= $logAll['1a']?>]
							}
						]
					};

					var options = {
						scaleShowGridLines : true,
						scaleGridLineColor : "rgba(0,0,0,.05)",
						scaleGridLineWidth : 1,
						bezierCurve : true,
						bezierCurveTension : 0.4,
						pointDot : true,
						pointDotRadius : 4,
						pointDotStrokeWidth : 1,
						pointHitDetectionRadius : 20,
						datasetStroke : true,
						datasetStrokeWidth : 2,
						datasetFill : true,
						tooltipTemplate: "<%=label%>: <%= value %>",
						multiTooltipTemplate: "<%=datasetLabel%>: <%= value %>",
						legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><%=datasets[i].label%><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>"
					};					

					$("#canvas").attr("width", $("body").width()-20);
					$("#canvas").attr("height", $("body").height()-20);

					var myLineChart = new Chart(ctx).Line(data, options);	
				});
			</script>
			<canvas id="canvas" style="margin-left: 10px;" width="1000" height="400"></canvas>
		</div>
	</body>
</html>

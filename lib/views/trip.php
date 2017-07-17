<h2><?=sprintf('<img src="/st/img/route_type_%d.svg" class="margarita-symbol"> <span class="margarita-badge">%s</span> %s', $route['route_type'], $route['route_short_name'], $trip['trip_headsign']); ?></h2>

<table class="pure-table">
<thead>
<tr><th colspan="3">trasa</th></tr>
<tr><th>zastávka</th><th>čas</th><th></th></tr>
</thead>
<tbody>
<?php
foreach ($stops as $stop) {
	echo sprintf(
		'<tr><td id="stop_%s">%s %s</td><td>%s</td><td><a href="/%s/stop/%s?date=%s&time=%s" class="margarita-noprint">vystoupit »</a></td></tr>',
		$stop['stop_id'],
		$stop['stop_name'],
		($stop['wheelchair_boarding']==1 ? '<img src="/st/img/wheelchair.svg" class="margarita-symbol">' : ''), 
		substr($stop['departure_time'], 0, 5),
		$net,
		$stop['stop_id'],
		$date,
		$stop['arrival_time']
	);
}


?>
</tbody>
</table>

<p>Provozuje: <a href="<?= $agency['agency_url']; ?>"><?= $agency['agency_name']; ?></a></p>
<h2><?=sprintf('<img src="/st/img/route_type_%d.svg" class="margarita-symbol"> <span class="margarita-badge">%s</span> %s', $route['route_type'], $route['route_short_name'], $route['route_long_name']); ?></h2>

<table class="pure-table">
<thead>
<tr><th colspan="5">spoje</th></tr>
<tr><th>ze zastávky</th><th>čas</th><th></th><th>směr</th><th></th></tr>
</thead>
<tbody>
<?php
foreach ($trips as $trip) {
	echo sprintf(
		'<tr><td>%s</td><td>%s</td><td>»</td><td>%s</td><td><a href="/%s/trip/%s/?date=%s" class="margarita-noprint">spoj »</td></tr>',
		$trip['stop_name'],
		substr($trip['departure_time'], 0, 5),
		$trip['trip_headsign'],
		$net,
		$trip['trip_id'],
		$date
	);
}


?>
</tbody>
</table>

<?= $form->open()->get()->addClass("pure-form pure-form-stacked"); ?>
<label>Datum</label>
<?= $form->date('date'); ?>
<?= $form->showError('date'); ?>

<?= $form->submit('Zobrazit')->addClass('pure-button pure-button-primary'); ?>
    
<?= $form->close(); ?>


<p>Dopravce: <a href="<?= $agency['agency_url']; ?>"><?= $agency['agency_name']; ?></a></p>
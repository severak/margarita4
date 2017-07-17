<h2><?=$stop['stop_name']; ?></h2>

<table class="pure-table">
<thead>
<tr><th colspan="4">odjezdy</th></tr>
<tr><th>linka</th><th>směr</th><th>čas</th><th></th></tr>
</thead>
<tbody>
<?php
foreach ($trips as $trip) {
	echo sprintf(
		'<tr><td><img src="/st/img/route_type_%d.svg" class="margarita-symbol margarita-noprint"> %s</td><td>%s</td><td>%s</td><td><a href="/%s/trip/%s/?date=%s#stop_%s" class="margarita-noprint">spoj »</td></tr>',
		$trip['route_type'],
		$trip['route_short_name'],
		$trip['trip_headsign'],
		substr($trip['departure_time'], 0, 5),
		$net,
		$trip['trip_id'],
		$date,
		$trip['stop_id']
	);
}


?>
</tbody>
</table>

<div class="margarita-noprint">
<?= $form->open()->get()->addClass("pure-form pure-form-stacked"); ?>
<label>Datum</label>
<?= $form->date('date'); ?>
<?= $form->showError('date'); ?>

<label>Čas</label>
<?= $form->text('time'); ?>
<?= $form->showError('time'); ?>

<label>Jen linka</label>
<?= $form->select('route', $selectRoutes); ?>
<?= $form->showError('route'); ?>

<label><?= $form->checkbox('wholeday'); ?> celý den</label>

<?= $form->submit('Zobrazit')->addClass('pure-button pure-button-primary'); ?>
    
<?= $form->close(); ?>
</div>


<?php
// todo: podobné zastávky - mapa
<h2><?=$stop['stop_name']; ?></h2>

<table class="pure-table">
<thead>
<tr><th colspan="3">odjezdy</th></tr>
<tr><th>linka</th><th>směr</th><th>čas</th></tr>
</thead>
<tbody>
<?php
foreach ($trips as $trip) {
	echo sprintf(
		'<tr><td><img src="/st/img/route_type_%d.svg" style="height: 1em"> %s</td><td>%s</td><td>%s</td></tr>',
		$trip['route_type'],
		$trip['route_short_name'],
		$trip['trip_headsign'],
		substr($trip['departure_time'], 0, 5)
	);
}


?>
</tbody>
</table>

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


<?php
// todo: podobné zastávky - mapa
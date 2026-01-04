<h2><?=$stop['stop_name']; ?></h2>

<?php
$isShort = !empty($_GET['short']);
if ($isShort) { ?>
<table class="pure-table">
<thead>
<tr><th colspan="4">odjezdy</th></tr>
<tr><th>směr</th><th>hodina</th><th>minuty</th></tr>
</thead>
<tbody>
<?php
$prevSmer = '';
$prevHh = '';
foreach ($trips as $trip) {
    $smer = $trip['trip_headsign'];
    $hh = substr($trip['departure_time'], 0, 2);
    $mm = substr($trip['departure_time'], 3, 2);
    if ($smer != $prevSmer || $hh != $prevHh) {
        echo '</tr><tr><td>' . $smer . '</td><td>' . $hh . '</td><td>';
    }
    echo $mm . ' ';

    $prevSmer = $smer;
    $prevHh = $hh;
}
?>
</tbody>
</table>
<?php } else { ?>
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
		base64_encode($trip['trip_id']),
		$date,
		base64_encode($trip['stop_id'])
	);
}


?>
</tbody>
</table>
<?php } ?>

<?php if (!empty($siblings)) {
    echo '<p>Sousední zastávky: ';
    foreach ($siblings as $sibling) {
        if ($sibling['stop_id']!=$stop['stop_id']) {
            echo '<a href="/'.$net.'/stop/'.base64_encode($sibling['stop_id']).'">'.$sibling['stop_name'].'</a> ';
        }
    }
    echo '</p>';
 } ?>

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
<label><?= $form->checkbox('short'); ?> smrsknout</label>

<?= $form->submit('Zobrazit')->addClass('pure-button pure-button-primary'); ?>
    
<?= $form->close(); ?>
</div>


<?php
// todo: podobné zastávky - mapa
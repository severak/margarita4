<form class="pure-form" method="get">
	<input type="text" name="search" placeholder="číslo linky nebo název stanice" value="<?=$search; ?>" class="pure-input-2-3">
	<input type="submit" value="vyhledat" class="pure-button pure-button-primary pure-input-1-3">
</form>

<?php 
if (count($stops)) {
	echo '<h2>nalezeny zastávky</h2>';
	foreach($stops as $stop) {
		$wheel = '';
		if ($stop['wheelchair_boarding']==1) {
			$wheel = ' <img src="/st/img/wheelchair.svg" style="height: 1em">'; 
		}
		echo sprintf('<a href="/%s/stop/%s">%s</a>%s<br/>', $net, $stop["stop_id"], $stop['stop_name'], $wheel);
	}
}

if (count($routes)) {
	echo '<h2>nalezeny linky</h2>';
	foreach($routes as $route) {
		echo sprintf('<img src="/st/img/route_type_%d.svg" style="height: 1em"> <a href="/%s/route/%s">%s</a><br/>', $route['route_type'], $net, $route["route_id"], $route['route_short_name'] . ' -  ' . $route['route_long_name'] );
	}
}



<ul>
<?php
foreach ($routes as $route) {
	echo "<li>" . $types[$route["route_type"]] . " " . $route["route_short_name"] . " " . $route["route_long_name"] . "</li>";
}
?>
</ul>

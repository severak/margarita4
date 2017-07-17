<hr>
<small>aplikace &copy; <a href="http://severak.svita.cz/">Severák</a> 2017;
<?php
if (!empty($net)) {
	$config = get_config();
	echo 'data &copy;' . $config['net'][$net]['copyright'];
}
?>
</small>
</div>
<?php
if (!empty($net)) {
	$config = get_config();
	echo '<style>.margarita-background { background: url("'.$config['net'][$net]['background'].'") no-repeat fixed center/cover; }</style>';
}
?>

</body>
</html>
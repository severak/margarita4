<?php
$config = get_config();
foreach ($config['net'] as $id=>$net):
?>

<h2><img src="<?= $net['logo']; ?>" class="margarita-symbol"> <a href="/<?= $id; ?>"><?= $net['name']; ?></a></h2>

<?php endforeach;
<form class="pure-form" method="get" action="/<?= $net; ?>/search">
	<input type="text" name="search" placeholder="číslo linky nebo název stanice" class="pure-input-2-3">
	<input type="submit" value="vyhledat" class="pure-button pure-button-primary pure-input-1-3">
</form>

<div class="margarita-routes">
<?php if (count($subways)): ?>
<h2><img src="/st/img/route_type_1.svg" class="margarita-symbol"> linky metra</h2>
<ul>
<?php foreach ($subways as $route): ?>
<li><a href="/<?= $net . '/route/' . $route['route_id'];?>"><span class="margarita-badge"><?= $route['route_short_name']; ?></span> <?= $route['route_long_name']; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>


<?php if (count($trains)): ?>
<h2><img src="/st/img/route_type_2.svg" class="margarita-symbol"> linky vlaku</h2>
<ul>
<?php foreach ($trains as $route): ?>
<li><a href="/<?= $net . '/route/' . $route['route_id'];?>"> <span class="margarita-badge"><?= $route['route_short_name']; ?></span> <?= $route['route_long_name']; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>


<?php if (count($trams)): ?>
<h2><img src="/st/img/route_type_0.svg" class="margarita-symbol"> linky tramvaje</h2>
<ul>
<?php foreach ($trams as $route): ?>
<li><a href="/<?= $net . '/route/' . $route['route_id'];?>"> <span class="margarita-badge"><?= $route['route_short_name']; ?></span> <?= $route['route_long_name']; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>


<?php if (count($buses)): ?>
<h2><img src="/st/img/route_type_3.svg" class="margarita-symbol"> linky autobusů</h2>
<ul>
<?php foreach ($buses as $route): ?>
<li><a href="/<?= $net . '/route/' . $route['route_id'];?>"><span class="margarita-badge"><?= $route['route_short_name']; ?></span> <?= $route['route_long_name']; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>


<?php if (count($ferries)): ?>
<h2><img src="/st/img/route_type_4.svg" class="margarita-symbol"> přívozy</h2>
<ul>
<?php foreach ($ferries as $route): ?>
<li><a href="/<?= $net . '/route/' . $route['route_id'];?>"><span class="margarita-badge"><?= $route['route_short_name']; ?></span> <?= $route['route_long_name']; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>


<?php if (count($elevators)): ?>
<h2><img src="/st/img/route_type_6.svg" class="margarita-symbol"> lanovky</h2>
<ul>
<?php foreach ($elevators as $route): ?>
<li><a href="/<?= $net . '/route/' . $route['route_id'];?>"><span class="margarita-badge"><?= $route['route_short_name']; ?></span> <?= $route['route_long_name']; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

</div>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/st/css/pure/pure.css">
	<link rel="stylesheet" href="/st/css/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" href="/st/css/margarita.css">
	<meta property="og:title" content="<?php $title; ?>" />
</head>
<body class="margarita-background">
	
<div class="margarita-page">	
<h1><img src="/st/img/aiga_ground.svg" class="margarita-symbol"> <a href="/">jízdní řády</a>
<?php
if (!empty($net)) {
	$config = get_config();
	echo '<small> - <a href="/'.$net. '">' . $config['net'][$net]['name'] . '</a></small>';
}
?>
</h1>
<hr>
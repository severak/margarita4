<?php
if (!file_exists(__DIR__ . '/config.php')) {
	die("ERROR: Kyselo not installed.");
}
$config = require __DIR__ . '/config.php';

require 'lib/flight/Flight.php';
require "lib/flight/autoload.php";

Flight::init();
Flight::set('flight.handle_errors', false);
Flight::set('flight.views.path', __DIR__ . '/lib/views');
require "lib/tracy/src/tracy.php";
use \Tracy\Debugger;
Debugger::enable();

flight\core\Loader::addDirectory("lib/flourish");

$route_types = [
0 => "TRAM",
1 => "METRO",
2 => "VLAK",
3 => "BUS",
4 => "LOĎ",
5 => "LANOVKA",
6 => "LANOVKA",
7 => "LANOVKA"
];

function get_db($net)
{
	if (!file_exists(__DIR__ . '/db/' .$net .  '.sqlite')) {
		throw new Exception("network ". $net . " not found!");
	}
	
	$db = new sparrow;
	$db->setDb('pdosqlite://localhost/' . __DIR__ . '/db/' .$net .  '.sqlite');
	$db->show_sql = true;
	return $db;
}

// homepage
Flight::route('/', function(){
	Flight::render('header', array('title' => 'margarita 4'));
	Flight::render('homepage');
	Flight::render('footer', []);
});

// list of routes
Flight::route('/@net/routes/', function($net){
	global $route_types;
	$db = get_db($net);
	
	$routes = $db->sql("select * 
from routes 
join agency on routes.agency_id=agency.agency_id
order by printf(\"%8s\", route_short_name) asc")->many();
	

	Flight::render('header', array('title' => 'list of routes'));
	Flight::render('routes', ["routes"=>$routes, "net"=>$net, "types"=>$route_types]);
	Flight::render('footer', []);
});


Flight::start();
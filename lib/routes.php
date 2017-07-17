<?php
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
	if (!file_exists(__DIR__ . '/../db/' .$net .  '.sqlite')) {
		//throw new Exception("network ". $net . " not found!");
		Flight::notFound();
	}
	
	$db = new sparrow;
	$db->setDb('pdosqlite://localhost/' . __DIR__ . '/../db/' .$net .  '.sqlite');
	$db->show_sql = true;
	return $db;
}

function get_valid_services(sparrow $db, $date='now')
{
	$date = date('Y-m-d', strtotime($date));
	$day = strtolower(date('l', strtotime($date)));
	
	$services =  $db->sql('SELECT * FROM calendar WHERE ' . $day . '=1 AND start_date<=' . $db->quote($date) . ' AND end_date>=' . $db->quote($date) )->many();
	
	$ret = [];
	foreach ($services as $service) {
		$ret[] = $db->quote($service['service_id']);
	}
	
	return implode(',', $ret);
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

Flight::route('/@net/search/', function($net){
	$db = get_db($net);
	
	$searchFor = '';
	$stops = array();
	$routes = array();
	
	if (!empty($_GET['search'])) {
		$searchFor = $_GET['search'];

		$stops = $db->sql('SELECT * FROM stops WHERE stop_name LIKE '. $db->quote('%' . $searchFor . '%') . ' AND location_type!=1 ORDER BY stop_name')->many();
		$routes = $db->sql('SELECT * FROM routes WHERE route_short_name LIKE '. $db->quote($searchFor) . ' LIMIT 20')->many();
	}
	
	Flight::render('header', array('title' => 'hledání'));
	Flight::render('search', ["net"=>$net, 'search'=>$searchFor, 'routes'=>$routes, 'stops'=>$stops]);
	Flight::render('footer', []);
});

Flight::route('/@net/route/@route_id/', function($net, $route_id){
	$db = get_db($net);

	$params = $_GET;

	if (empty($params['date'])) $params['date'] = date('Y-m-d', strtotime('now'));

	$route = $db->from('routes')->where(['route_id'=>$route_id])->one();
	$agency = $db->from('agency')->where(['agency_id'=>$route['agency_id'] ])->one();
	
	if (!$route) {
		Flight::notFound();
	}
	
	$services = get_valid_services($db, $params['date']);
	
	$trips = $db->sql('SELECT *
FROM trips
JOIN stop_times ON trips.trip_id=stop_times.trip_id AND stop_sequence=1
JOIN stops ON stop_times.stop_id=stops.stop_id
WHERE route_id=' .  $db->quote($route_id) . '  AND service_id IN (' .  $services . ')
ORDER BY departure_time ASC')->many();

	$form = new severak\form;
	$form->values = $params;
	
	Flight::render('header', [ 'title' => 'linka ' . $route['route_short_name'] ]);
	Flight::render('route', ["net"=>$net, 'route'=>$route, 'agency'=>$agency, 'trips'=>$trips, 'form'=>$form, 'date'=>$params['date'] ]);
	Flight::render('footer', []);
});
	
	
Flight::route('/@net/stop/@stop_id/', function($net, $stop_id){
	$db = get_db($net);

	$params = $_GET;

	if (empty($params['date'])) $params['date'] = date('Y-m-d', strtotime('now'));
	if (empty($params['time'])) $params['time'] = date('H:i:s', strtotime('now'));
	
	$stop = $db->from('stops')->where(['stop_id'=>$stop_id])->one();
	
	if (!$stop) {
		Flight::notFound();
	}
	
	$services = get_valid_services($db, $params['date']);
	
	$timeCond = '';
	if (empty($params['wholeday'])) $timeCond = 'AND departure_time>='.$db->quote($params['time']);
	$routeCond = '';
	if (!empty($params['route'])) $routeCond = ' AND routes.route_id=' . $db->quote($params['route']); 
	
	$trips = $db->sql('
	SELECT *
FROM stop_times 
JOIN trips ON stop_times.trip_id=trips.trip_id
JOIN routes ON trips.route_id=routes.route_id
WHERE stop_times.stop_id='.$db->quote($stop_id). $timeCond . $routeCond. ' AND service_id IN ('.$services.')
ORDER BY departure_time ASC
' . (!empty($timeCond) ? 'LIMIT 20' : '') )->many();

	$possibleRoutes = $db->sql('
	SELECT DISTINCT routes.route_id, route_short_name
FROM stop_times 
JOIN trips ON stop_times.trip_id=trips.trip_id
JOIN routes ON trips.route_id=routes.route_id
WHERE stop_times.stop_id='.$db->quote($stop_id).' AND service_id IN ('.$services.')
ORDER BY  substr("        " || route_short_name, -8) ASC')->many();
	$routesForSelect = [''=> '[všechny]', ];
	foreach ($possibleRoutes as $route) {
		$routesForSelect[$route['route_id']] = $route['route_short_name'];
	}
	
	$form = new severak\form;
	$form->values = $params;
	
	Flight::render('header', [ 'title' => 'zastávka ' . $stop['stop_name'] ]);
	Flight::render('stop', ["net"=>$net, 'stop'=>$stop, 'trips'=>$trips, 'form'=>$form, 'selectRoutes'=>$routesForSelect, 'date'=>$params['date'] ]);
	Flight::render('footer', []);
});


Flight::route('/@net/trip/@trip_id/', function($net, $trip_id){
	$db = get_db($net);
	
	$date = isset($_GET['date']) ? $_GET['date'] : '';
	
	$trip = $db->from('trips')->where(['trip_id'=>$trip_id])->one();
	
	if (!$trip) {
		Flight::notFound();
	}
	
	$route= $db->from('routes')->where(['route_id'=>$trip['route_id']])->one();
	$agency = $db->from('agency')->where(['agency_id'=>$route['agency_id'] ])->one();

	$stops = $db->sql('SELECT * 
FROM stop_times 
JOIN stops ON stop_times.stop_id=stops.stop_id
WHERE trip_id=' . $db->quote($trip_id) .  '
ORDER BY stop_sequence ASC')->many();

	Flight::render('header', [ 'title' => 'spoj linky ' . $route['route_short_name'] ]);
	Flight::render('trip', ["net"=>$net, 'stops'=>$stops, 'trip'=>$trip, 'route'=>$route, 'agency'=>$agency, 'date'=>$date]);
	Flight::render('footer', []);
});
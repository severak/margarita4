<?php
$route_types = [
0 => "TRAM",
1 => "METRO",
2 => "VLAK",
3 => "BUS",
4 => "LOĎ",
5 => "LANOVKA",
6 => "LANOVKA",
7 => "LANOVKA",
11 => "TROLEJBUS"
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

function get_config()
{
	global $config;
	return $config;
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

    // adding added services from calendar_dates
    $servicesAdded =  $db->sql('SELECT * FROM calendar_dates WHERE date='.$db->quote($date).' AND exception_type=1')->many();
    foreach ($servicesAdded as $service) {
        $ret[] = $db->quote($service['service_id']);
    }

    // removing removed services from calendar_dates
    $servicesRemoved =  $db->sql('SELECT * FROM calendar_dates WHERE date='.$db->quote($date).' AND exception_type=2')->many();
    $removed = [];
    foreach ($servicesRemoved as $service) {
        $removed[] = $db->quote($service['service_id']);
    }
    $ret2 = [];
    foreach ($ret as $serviceId) {
        if (!in_array($serviceId, $removed)) {
            $ret2[] = $serviceId;
        }
    }
	
	return implode(',', $ret2);
}

function services_explain(sparrow $db, $serviceId)
{
    $services =  $db->sql('SELECT * FROM calendar WHERE service_id=' . $db->quote($serviceId))->many();
    $jede = [];
    $nejede = [];
    $platOd = '';
    $platDo = '';

    foreach ($services as $service) {
        if (
            $service['monday']==0 &&
            $service['tuesday']==0 &&
            $service['wednesday']==0 &&
            $service['thursday']==0 &&
            $service['friday']==0 &&
            $service['saturday']==0 &&
            $service['sunday']==0
        ) {
            $jede[] = 'vyjmenované dny';
        }
        if (
            $service['monday']==1 &&
            $service['tuesday']==1 &&
            $service['wednesday']==1 &&
            $service['thursday']==1 &&
            $service['friday']==1 &&
            $service['saturday']==1 &&
            $service['sunday']==1
        ) {
            $jede[] = 'každý den';
        }
        if (
            $service['monday']==1 &&
            $service['tuesday']==1 &&
            $service['wednesday']==1 &&
            $service['thursday']==1 &&
            $service['friday']==1 &&
            $service['saturday']==0 &&
            $service['sunday']==0
        ) {
            $jede[] = 'pracovní dny';
        }
        if (
            $service['monday']==0 &&
            $service['tuesday']==0 &&
            $service['wednesday']==0 &&
            $service['thursday']==0 &&
            $service['friday']==0 &&
            $service['saturday']==1 &&
            $service['sunday']==1
        ) {
            $jede[] = 'víkendy';
        }

        if (empty($jede)) {
            if ($service['monday']==1) { $jede[] = 'Po'; }
            if ($service['tuesday']==1) { $jede[] = 'Út'; }
            if ($service['wednesday']==1) { $jede[] = 'St'; }
            if ($service['thursday']==1) { $jede[] = 'Čt'; }
            if ($service['friday']==1) { $jede[] = 'Pá'; }
            if ($service['saturday']==1) { $jede[] = 'So'; }
            if ($service['sunday']==1) { $jede[] = 'Ne'; }
        }

        $platOd = date('j.n.Y', strtotime($service['start_date']));
        $platDo = date('j.n.Y', strtotime($service['end_date']));

    }

    // adding added services from calendar_dates
    $servicesAdded =  $db->sql('SELECT * FROM calendar_dates WHERE service_id='.$db->quote($serviceId).' AND exception_type=1')->many();

    foreach ($servicesAdded as $service) {
        $jede[] = date('j.n', strtotime($service['date']));
    }

    // removing removed services from calendar_dates
    $servicesRemoved =  $db->sql('SELECT * FROM calendar_dates WHERE service_id='.$db->quote($serviceId).' AND exception_type=2')->many();

    foreach ($servicesRemoved as $service) {
        $nejede[] = date('j.n', strtotime($service['date']));
    }

    return 'jede: ' . implode(', ', $jede). '<br/>nejede: ' . implode(', ', $nejede) . '<br/>JŘ platí od ' . $platOd . ' do ' . $platDo;
}

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return strlen($needle) === 0 || strpos($haystack, $needle) === 0;
    }
}

function fix_https_prefix($url)
{
    if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
        return $url;
    }
    return 'https://'.$url;
}

// homepage
Flight::route('/', function(){
	Flight::render('header', array('title' => 'margarita 4'));
	Flight::render('homepage');
	Flight::render('footer', []);
});

Flight::route('/@net/', function($net){
	$db = get_db($net);
	$config = get_config();
	
	$subways = $db->from('routes')->where(['route_type'=>1])->sortAsc('substr("     " || route_short_name, -5)')->many();
	$trains = $db->from('routes')->where(['route_type'=>2])->sortAsc('substr("     " || route_short_name, -5)')->many();
	$trams = $db->from('routes')->where(['route_type'=>0])->sortAsc('substr("     " || route_short_name, -5)')->many();
	$buses = $db->from('routes')->where(['route_type'=>3])->sortAsc('substr("     " || route_short_name, -5)')->many();
	$ferries = $db->from('routes')->where(['route_type'=>4])->sortAsc('substr("     " || route_short_name, -5)')->many();
	$elevators = $db->from('routes')->where(['route_type'=>[5,6,7]])->sortAsc('substr("     " || route_short_name, -5)')->many();
    $trolleybuses = $db->from('routes')->where(['route_type'=>11])->sortAsc('substr("     " || route_short_name, -5)')->many();
	
	Flight::render('header', array('title' => $config['net'][$net]['name'], 'net'=>$net));
	Flight::render('routes', ['subways'=>$subways, 'trains'=>$trains, 'trams'=>$trams, 'buses'=>$buses, 'ferries'=>$ferries, 'elevators'=>$elevators, 'trolleybuses'=>$trolleybuses, 'net'=>$net]);
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
	
	Flight::render('header', array('title' => 'hledání', 'net'=>$net));
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
	
	Flight::render('header', [ 'title' => 'linka ' . $route['route_short_name'] , 'net'=>$net]);
	Flight::render('route', ["net"=>$net, 'route'=>$route, 'agency'=>$agency, 'trips'=>$trips, 'form'=>$form, 'date'=>$params['date'] ]);
	Flight::render('footer', []);
});
	
	
Flight::route('/@net/stop/@stop_id/', function($net, $stop_id){
	$db = get_db($net);
    $stop_id = base64_decode($stop_id);

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
	
	Flight::render('header', [ 'title' => 'zastávka ' . $stop['stop_name'] , 'net'=>$net]);
	Flight::render('stop', ["net"=>$net, 'stop'=>$stop, 'trips'=>$trips, 'form'=>$form, 'selectRoutes'=>$routesForSelect, 'date'=>$params['date'] ]);
	Flight::render('footer', []);
});


Flight::route('/@net/trip/@trip_id/', function($net, $trip_id){
	$db = get_db($net);
    $trip_id = base64_decode($trip_id);
	
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

	Flight::render('header', [ 'title' => 'spoj linky ' . $route['route_short_name'] , 'net'=>$net]);
	Flight::render('trip', ["net"=>$net, 'stops'=>$stops, 'trip'=>$trip, 'route'=>$route, 'agency'=>$agency, 'date'=>$date]);
	Flight::render('footer', []);
});
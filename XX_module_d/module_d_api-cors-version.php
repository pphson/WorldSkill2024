<?php

// Allow CORS from any origin
header("Access-Control-Allow-Origin: *");

// HTTP methods to allow
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

// Headers to allow
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}


error_reporting(0);

session_start();


if (!isset($_SESSION['request_count'])) {
    $_SESSION['request_count'] = 0;
} else {
    $_SESSION['request_count']++;
}

$requestUri = $_SERVER['REQUEST_URI'];
switch ($requestUri) {
    case '/module_d_api.php/reset.json':
        header('Content-Type: application/json');
        $_SESSION['request_count'] = 0;
        echo json_encode(['Reset' => 'Carpark seed reset done.']);
        break;
    case '/module_d_api.php/carparks.json':
        header('Content-Type: application/json');
        echo json_encode(getCarparkData($_SESSION['request_count']));
        break;

    case '/module_d_api.php/weather.json':
        header('Content-Type: application/json');
        echo json_encode(getWeatherData());
        break;

    default:
        if (strpos($requestUri, '/module_d_api.php/image.png') === 0) {
            createImageWithText($_GET['title']);
        } else if (strpos($requestUri, '/module_d_api.php/events.json') === 0) {
            header('Content-Type: application/json');
            echo json_encode(getEventsData());
            break;
        } else {
            header('Content-Type: application/json');
            $list = [
                '/module_d_api.php/reset.json' => 'Reset random seed',
                '/module_d_api.php/carparks.json' => 'Get carpark availability',
                '/module_d_api.php/weather.json' => 'Get weather data',
                '/module_d_api.php/events.json' => 'Get events data',
                '/module_d_api.php/image.png?title=Hello, World' => 'Get image with text',
            ];
            echo json_encode(['Available API endpoints' => $list]);
        }
        break;
}

function getCarparkData($seed) {
    mt_srand($seed);
    return [
        'Brotteaux' => ['availableSpaces' => mt_rand(0, 50), 'latitude'=> 45.764043, 'longitude' => 4.835659, 'location' => '12 Rue de Brotteaux, 69006 Lyon, France'],
        'Cordeliers' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.764501, 'longitude' => 4.835659, 'location' => 'Place des Cordeliers, 69002 Lyon, France'],
        'Gare de Lyon' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.755409, 'longitude' => 4.821223, 'location' => 'Place Charles de Gaulle, 69003 Lyon, France'],
        'Perrache' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.757501, 'longitude' => 4.842299, 'location' => 'Place Louis Pradel, 69002 Lyon, France'],
        'Croix-Rousse' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.772501, 'longitude' => 4.833239, 'location' => 'Place de la Croix-Rousse, 69004 Lyon, France'],
        'Vaise' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.786551, 'longitude' => 4.801299, 'location' => 'Place de Vaise, 69009 Lyon, France'],
        'Part-Dieu' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.758501, 'longitude' => 4.852299, 'location' => '151 Rue Servient, 69003 Lyon, France'],
        'Saint-Exupery' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.731501, 'longitude' => 4.886299, 'location' => 'Place de lAviation, 69003 Lyon, France'],
        'Lyon-Saint-Exupery' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.731501, 'longitude' => 4.886269, 'location' => 'Place de lAviation, 69003 Lyon, France'],
        'Ecully' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.783551, 'longitude' => 4.761259, 'location' => 'Rue de la Paix, 69130 Ecully, France'],
        'Bron' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.741501, 'longitude' => 4.912299, 'location' => 'Place de la Gare, 69500 Bron, France'],
        'Villeurbanne' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.766501, 'longitude' => 4.887299, 'location' => 'Place Lazare Goujon, 69100 Villeurbanne, France'],
        'Rillieux-la-Pape' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.814501, 'longitude' => 4.887293, 'location' => 'Place de la paix, 69140 Rillieux-la-Pape, France'],
        'Dardilly' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.806501, 'longitude' => 4.744293, 'location' => 'Place de la libert , 69570 Dardilly, France'],
        'Oullins' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.692501, 'longitude' => 4.814299, 'location' => 'Place de la Gare, 69600 Oullins, France'],
        'Sainte-Foy-les-Lyon' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.736501, 'longitude' => 4.801229, 'location' => 'Place de la Gare, 69110 Sainte-Foy-les-Lyon, France'],
        'Tassin-la-Demi-Lune' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.783501, 'longitude' => 4.744299, 'location' => 'Place de la Gare, 69160 Tassin-la-Demi-Lune, France'],
        'Vernaison' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.651504, 'longitude' => 4.809243, 'location' => 'Place de la Gare, 69390 Vernaison, France'],
        'Sathonay-Camp' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.857501, 'longitude' => 4.862233, 'location' => 'Place de la Gare, 69580 Sathonay-Camp, France'],
        'Cailloux-sur-Fontaines' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.883501, 'longitude' => 4.942299, 'location' => 'Place de la Gare, 69270 Cailloux-sur-Fontaines, France'],
        'Simandres' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.737501, 'longitude' => 4.886299, 'location' => 'Place de la Gare, 69590 Simandres, France'],
        'Solaize' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.666501, 'longitude' => 4.833299, 'location' => 'Place de la Gare, 69340 Solaize, France'],
        'Marennes' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.733506, 'longitude' => 4.833299, 'location' => 'Place de la Gare, 69270 Marennes, France'],
        'Loire-sur-Rhône' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.566501, 'longitude' => 4.805299, 'location' => 'Place de la Gare, 69700 Loire-sur-Rhône, France'],
        'Givors' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.583501, 'longitude' => 4.777299, 'location' => 'Place de la Gare, 69700 Givors, France'],
        'Grigny' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.603511, 'longitude' => 4.733255, 'location' => 'Place de la Gare, 69530 Grigny, France'],
        'Vaugneray' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.743501, 'longitude' => 4.633299, 'location' => 'Place de la Gare, 69670 Vaugneray, France'],
        'Chaponost' => ['availableSpaces' => mt_rand(0, 50), 'latitude' => 45.713531, 'longitude' => 4.744299, 'location' => 'Place de la Gare, 69630 Chaponost, France'],
     ];
}

function getWeatherData() {
    $data = [];
    $date = new DateTime('2024-09-12');
    for ($i = 0; $i < 7; $i++) {
        $data[] = [
            'location' => 'Lyon',
            'date' => $date->format('Y-m-d'),
            'status' => ['Cloudy', 'Rainy', 'Sunny'][rand(0, 2)],
            'lower_temperature' => rand(10, 19),
            'upper_temperature' => rand(20, 28)
        ];
        $date->add(new DateInterval('P1D'));
    };
    return $data;
}

function getEventsData() {
    $events = [
        ['title' => 'Riffs & Rhythms: Lyon Jazz Festival', 'image' => '/module_d_api.php/image.png?title=Riffs %26 Rhythms: Lyon Jazz Festival', 'date' => '2024-09-17'],
        ['title' => 'Harvest Hoedown: Lyon Autumn Market', 'image' => '/module_d_api.php/image.png?title=Harvest Hoedown: Lyon Autumn Market', 'date' => '2024-09-22'],
        ['title' => 'Monster Mash Bash: Lyon Halloween Party', 'image' => '/module_d_api.php/image.png?title=Monster Mash Bash: Lyon Halloween Party', 'date' => '2024-10-31'],
        ['title' => 'Joyeux Noël: Lyon Christmas Market', 'image' => '/module_d_api.php/image.png?title=Joyeux Noël: Lyon Christmas Market', 'date' => '2024-12-01'],
        ['title' => 'Countdown to Midnight: Lyon New Years Eve Party', 'image' => '/module_d_api.php/image.png?title=Countdown to Midnight: Lyon New Years Eve Party', 'date' => '2024-12-31'],
        ['title' => 'Winter Wonderland: Lyon Winter Festival', 'image' => '/module_d_api.php/image.png?title=Winter Wonderland: Lyon Winter Festival', 'date' => '2025-02-01'],
        ['title' => 'Spring Fling: Lyon Spring Carnival', 'image' => '/module_d_api.php/image.png?title=Spring Fling: Lyon Spring Carnival', 'date' => '2025-03-21'],
        ['title' => 'Easter Eggstravaganza: Lyon Easter Egg Hunt', 'image' => '/module_d_api.php/image.png?title=Easter Eggstravaganza: Lyon Easter Egg Hunt', 'date' => '2025-04-12'],
        ['title' => 'Summer Sounds: Lyon Summer Music Festival', 'image' => '/module_d_api.php/image.png?title=Summer Sounds: Lyon Summer Music Festival', 'date' => '2025-06-21'],
        ['title' => 'Vintage Vibes: Lyon Autumn Wine Festival', 'image' => '/module_d_api.php/image.png?title=Vintage Vibes: Lyon Autumn Wine Festival', 'date' => '2025-09-20'],
        ['title' => 'Jazz & Wine: Lyon Jazz & Wine Festival', 'image' => '/module_d_api.php/image.png?title=Jazz %26 Wine: Lyon Jazz %26 Wine Festival', 'date' => '2025-10-17'],
        ['title' => 'Holiday Cheer: Lyon Christmas Market', 'image' => '/module_d_api.php/image.png?title=Holiday Cheer: Lyon Christmas Market', 'date' => '2025-12-01'],
        ['title' => 'New Years Bash: Lyon New Years Eve Party', 'image' => '/module_d_api.php/image.png?title=New Years Bash: Lyon New Years Eve Party', 'date' => '2025-12-31'],
        ['title' => 'Winter Whimsy: Lyon Winter Festival', 'image' => '/module_d_api.php/image.png?title=Winter Whimsy: Lyon Winter Festival', 'date' => '2026-02-01'],
        ['title' => 'Spring Spectacle: Lyon Spring Carnival', 'image' => '/module_d_api.php/image.png?title=Spring Spectacle: Lyon Spring Carnival', 'date' => '2026-03-21'],
        ['title' => 'Easter Egg Hunt: Lyon Easter Egg Hunt', 'image' => '/module_d_api.php/image.png?title=Easter Egg Hunt: Lyon Easter Egg Hunt', 'date' => '2026-04-12'],
        ['title' => 'Summer Sounds: Lyon Summer Music Festival', 'image' => '/module_d_api.php/image.png?title=Summer Sounds: Lyon Summer Music Festival', 'date' => '2026-06-21'],
        ['title' => 'Vintage Vibes: Lyon Autumn Wine Festival', 'image' => '/module_d_api.php/image.png?title=Vintage Vibes: Lyon Autumn Wine Festival', 'date' => '2026-09-20'],
        ['title' => 'Summer Lovin: Lyon Summer Music Festival', 'image' => '/module_d_api.php/image.png?title=Summer Lovin: Lyon Summer Music Festival', 'date' => '2026-07-01'],
        ['title' => 'La Fête de la Musique: Lyon Music Festival', 'image' => '/module_d_api.php/image.png?title=La Fête de la Musique: Lyon Music Festival', 'date' => '2026-07-21'],
        ['title' => 'Bastille Day: Lyon French National Day', 'image' => '/module_d_api.php/image.png?title=Bastille Day: Lyon French National Day', 'date' => '2026-07-14'],
        ['title' => 'Les Nuits de Fourvière: Lyon Outdoor Concerts', 'image' => '/module_d_api.php/image.png?title=Les Nuits de Fourvière: Lyon Outdoor Concerts', 'date' => '2026-07-15'],
        ['title' => 'Autumn Colors: Lyon Fall Festival', 'image' => '/module_d_api.php/image.png?title=Autumn Colors: Lyon Fall Festival', 'date' => '2026-10-01'],
        ['title' => 'Halloween Party: Lyon Spooky Night', 'image' => '/module_d_api.php/image.png?title=Halloween Party: Lyon Spooky Night', 'date' => '2026-10-31'],
        ['title' => 'Christmas Market: Lyon Holiday Shopping', 'image' => '/module_d_api.php/image.png?title=Christmas Market: Lyon Holiday Shopping', 'date' => '2026-12-01'],
        ['title' => 'New Years Eve Party: Lyon Countdown', 'image' => '/module_d_api.php/image.png?title=New Years Eve Party: Lyon Countdown', 'date' => '2026-12-31'],
        ['title' => 'Winter Festival: Lyon Ice Skating', 'image' => '/module_d_api.php/image.png?title=Winter Festival: Lyon Ice Skating', 'date' => '2027-01-01'],
        ['title' => 'Carnival of Lights: Lyon Mardi Gras', 'image' => '/module_d_api.php/image.png?title=Carnival of Lights: Lyon Mardi Gras', 'date' => '2027-02-21'],
        ['title' => 'Easter Egg Hunt: Lyon Springtime Fun', 'image' => '/module_d_api.php/image.png?title=Easter Egg Hunt: Lyon Springtime Fun', 'date' => '2027-04-12'],
        ['title' => 'Summer Music Festival: Lyon Outdoor Concerts', 'image' => '/module_d_api.php/image.png?title=Summer Music Festival: Lyon Outdoor Concerts', 'date' => '2027-06-21'],
        ['title' => 'Vintage Car Show: Lyon Classic Cars', 'image' => '/module_d_api.php/image.png?title=Vintage Car Show: Lyon Classic Cars', 'date' => '2027-07-01'],
        ['title' => 'Bastille Day Parade: Lyon French National Day', 'image' => '/module_d_api.php/image.png?title=Bastille Day Parade: Lyon French National Day', 'date' => '2027-07-14'],
        ['title' => 'Les Nuits de Fourvière: Lyon Outdoor Theater', 'image' => '/module_d_api.php/image.png?title=Les Nuits de Fourvière: Lyon Outdoor Theater', 'date' => '2027-07-15'],
        ['title' => 'Autumn Wine Festival: Lyon Harvest Season', 'image' => '/module_d_api.php/image.png?title=Autumn Wine Festival: Lyon Harvest Season', 'date' => '2027-09-20'],
        ['title' => 'Halloween Costume Party: Lyon Spooky Night', 'image' => '/module_d_api.php/image.png?title=Halloween Costume Party: Lyon Spooky Night', 'date' => '2027-10-31'],
        ['title' => 'Christmas Tree Lighting: Lyon Holiday Cheer', 'image' => '/module_d_api.php/image.png?title=Christmas Tree Lighting: Lyon Holiday Cheer', 'date' => '2027-12-01'],
        ['title' => 'New Years Bash: Lyon Countdown', 'image' => '/module_d_api.php/image.png?title=New Years Bash: Lyon Countdown', 'date' => '2027-12-31'],
        ['title' => 'Winter Wonderland: Lyon Ice Skating', 'image' => '/module_d_api.php/image.png?title=Winter Wonderland: Lyon Ice Skating', 'date' => '2028-01-01'],
        ['title' => 'Carnival of Lights: Lyon Mardi Gras', 'image' => '/module_d_api.php/image.png?title=Carnival of Lights: Lyon Mardi Gras', 'date' => '2028-02-21'],
        ['title' => 'Easter Egg Hunt: Lyon Springtime Fun', 'image' => '/module_d_api.php/image.png?title=Easter Egg Hunt: Lyon Springtime Fun', 'date' => '2028-04-12'],
        ['title' => 'Summer Music Festival: Lyon Outdoor Concerts', 'image' => '/module_d_api.php/image.png?title=Summer Music Festival: Lyon Outdoor Concerts', 'date' => '2028-06-21'],
        ['title' => 'Vintage Car Show: Lyon Classic Cars', 'image' => '/module_d_api.php/image.png?title=Vintage Car Show: Lyon Classic Cars', 'date' => '2028-07-01'],
        ['title' => 'Bastille Day Parade: Lyon French National Day', 'image' => '/module_d_api.php/image.png?title=Bastille Day Parade: Lyon French National Day', 'date' => '2028-07-14'],
        ['title' => 'Les Nuits de Fourvière: Lyon Outdoor Theater', 'image' => '/module_d_api.php/image.png?title=Les Nuits de Fourvière: Lyon Outdoor Theater', 'date' => '2028-07-15'],
        ['title' => 'Autumn Wine Festival: Lyon Harvest Season', 'image' => '/module_d_api.php/image.png?title=Autumn Wine Festival: Lyon Harvest Season', 'date' => '2028-09-20'],
        ['title' => 'Halloween Party: Lyon Spooky Night', 'image' => '/module_d_api.php/image.png?title=Halloween Party: Lyon Spooky Night', 'date' => '2028-10-31'],
        ['title' => 'Christmas Market: Lyon Holiday Shopping', 'image' => '/module_d_api.php/image.png?title=Christmas Market: Lyon Holiday Shopping', 'date' => '2028-12-01'],
        ['title' => 'New Years Eve Party: Lyon Countdown', 'image' => '/module_d_api.php/image.png?title=New Years Eve Party: Lyon Countdown', 'date' => '2028-12-31'],
        ['title' => 'Winter Festival: Lyon Ice Skating', 'image' => '/module_d_api.php/image.png?title=Winter Festival: Lyon Ice Skating', 'date' => '2029-01-01'],
        ['title' => 'Carnival of Lights: Lyon Mardi Gras', 'image' => '/module_d_api.php/image.png?title=Carnival of Lights: Lyon Mardi Gras', 'date' => '2029-02-21'],
        ['title' => 'Easter Egg Hunt: Lyon Springtime Fun', 'image' => '/module_d_api.php/image.png?title=Easter Egg Hunt: Lyon Springtime Fun', 'date' => '2029-04-12'],
        ['title' => 'Summer Music Festival: Lyon Outdoor Concerts', 'image' => '/module_d_api.php/image.png?title=Summer Music Festival: Lyon Outdoor Concerts', 'date' => '2029-06-21'],
        ['title' => 'Vintage Car Show: Lyon Classic Cars', 'image' => '/module_d_api.php/image.png?title=Vintage Car Show: Lyon Classic Cars', 'date' => '2029-07-01'],
        ['title' => 'Bastille Day Parade: Lyon French National Day', 'image' => '/module_d_api.php/image.png?title=Bastille Day Parade: Lyon French National Day', 'date' => '2029-07-14'],
        ['title' => 'Les Nuits de Fourvière: Lyon Outdoor Theater', 'image' => '/module_d_api.php/image.png?title=Les Nuits de Fourvière: Lyon Outdoor Theater', 'date' => '2029-07-15'],
        ['title' => 'Autumn Wine Festival: Lyon Harvest Season', 'image' => '/module_d_api.php/image.png?title=Autumn Wine Festival: Lyon Harvest Season', 'date' => '2029-09-20'],
    ];

    $total = count($events);

    if (isset($_GET['beginning_date']) && isset($_GET['ending_date'])) {
        $beginningDate = new DateTime($_GET['beginning_date']);
        $endingDate = new DateTime($_GET['ending_date']);
        $events = array_filter($events, function ($event) use ($beginningDate, $endingDate) {
            $eventDate = new DateTime($event['date']);
            return $eventDate >= $beginningDate && $eventDate <= $endingDate;
        });
    }


    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;
    $events = array_slice($events, $offset, $limit);

    $nextPage = $page + 1;
    $prevPage = $page - 1;
    $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    parse_str($query, $params);
    if (isset($params['page'])) {
        $nextPageUrl = preg_replace('/page=\d+/', "page=$nextPage", $_SERVER['REQUEST_URI']);
        $prevPageUrl = preg_replace('/page=\d+/', "page=$prevPage", $_SERVER['REQUEST_URI']);
    } else {
        $nextPageUrl = $_SERVER['REQUEST_URI'] . "?page=$nextPage";
        $prevPageUrl = $_SERVER['REQUEST_URI'] . "?page=$prevPage";
    }
    $pages = [
        'next' => $nextPage <= ceil($total / $limit) ? $nextPageUrl : null,
        'prev' => $prevPage > 0 ? $prevPageUrl : null
    ];
    return ['events' => $events, 'pages' => $pages];
}
function createImageWithText($text, $size = 400) {
    // Create a blank image
    $image = imagecreatetruecolor($size, $size);

    // Generate random start and end colors for the gradient
    $startColor = [rand(0, 255), rand(0, 255), rand(0, 255)];
    $endColor = [rand(0, 255), rand(0, 255), rand(0, 255)];

    // Create gradient background
    for ($y = 0; $y < $size; $y++) {
        $r = $startColor[0] + ($endColor[0] - $startColor[0]) * ($y / $size);
        $g = $startColor[1] + ($endColor[1] - $startColor[1]) * ($y / $size);
        $b = $startColor[2] + ($endColor[2] - $startColor[2]) * ($y / $size);
        $color = imagecolorallocate($image, $r, $g, $b);
        imageline($image, 0, $y, $size, $y, $color);
    }

    // Allocate black color for text
    $txtColor = imagecolorallocate($image, 0, 0, 0);

    // Calculate text size and position
    $font = 15; // GD built-in font size
    $textWidth = imagefontwidth($font) * strlen($text);
    $textHeight = imagefontheight($font);

    $x = ($size - $textWidth) / 2;
    $y = ($size - $textHeight) / 2;

    // Add the text
    imagestring($image, $font, $x, $y, $text, $txtColor);

    // Output the image
    header('Content-Type: image/png');
    imagepng($image);

    // Free up memory
    imagedestroy($image);
}

?>
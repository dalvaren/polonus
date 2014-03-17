<?php 

require_once __DIR__ . '/vendor/autoload.php';

use Polonus\PolonusClient;

$poland = new PolonusClient('jakub.maj@gmail.com', 'B6c*#n,Zjr', 'd5bcc2101c3a460fb7e61bedeb3093ea', 'https://pksbilety.pl');

 // $result = $poland->routeInfo('73237', '73242', '2014-02-24');
// $result = $poland->routeInfo(73237, 73242, '2014-02-24');
// $result = $poland->returnTicket('73237', '73242', true);
// $result = $poland->search('73237', '73242', '2014-02-24');
// $result = $poland->vehiclesOnRoute();
$result = $poland->delayed();
// $result = $poland->combinedSearch('73237', '73242', '2014-02-24');
// $result = $poland->combinedSearch('Warszawa', 'Radom', '2014-02-24');
// $result = $poland->buyTicket('73237', '73242', '2014-02-24', '', 1, true);

// file_put_contents('./tests/Polonus/json/delayed.json', json_encode($result));
var_dump(json_encode($result));
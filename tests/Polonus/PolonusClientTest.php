<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Polonus\PolonusClient;

class UserTest extends PHPUnit_Framework_TestCase
{
    protected $polonusClient;

    protected function setUp() {
        $this->polonusClient = new PolonusClient('jakub.maj@gmail.com', 'B6c*#n,Zjr', 'd5bcc2101c3a460fb7e61bedeb3093ea', 'https://pksbilety.pl');
    }

    public function testSearch(){
        $result = $this->polonusClient->search('73237', '73242', '2014-02-24');
        $this->assertJsonStringEqualsJsonString('{"status":200,"body":"[]"}', json_encode($result));
    }

    public function testRouteInfo(){
        $result = $this->polonusClient->routeInfo('73237', '73242', '2014-02-24');
        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/json/routeInfo.json', json_encode($result));
    }

    public function testBuyTicket(){
        $result = $this->polonusClient->buyTicket('73237', '73242', '2014-02-24', '', 1, true);
        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/json/buyTicket.json', json_encode($result));
    }

    public function testReturnTicket(){
        $result = $this->polonusClient->returnTicket('73237', '73242', true);
        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/json/returnTicket.json', json_encode($result));
    }

    public function testVehiclesOnRoute(){
        $result = $this->polonusClient->vehiclesOnRoute();
        $this->assertStringStartsWith('{"status":200,"body":"[', json_encode($result));
    }

    public function testDelayed(){
        $result = $this->polonusClient->delayed();
        $this->assertStringStartsWith('{"status":200,"body":"[', json_encode($result));
    }

    public function testCombinedSearch(){
        $result = $this->polonusClient->combinedSearch('73237', '73242', '2014-02-24');
        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/json/combinedSearch.json', json_encode($result));
    }

    protected function tearDown() {
        unset($this->polonusClient);
    }
}
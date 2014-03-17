<?php

namespace Polonus;

use Guzzle\Http\Client;

/**
 * Polonus ISSB v3 API Client
 *
 * @author Daniel Campos <daniel.campos@clickbus.com.br>
 */
class PolonusClient
{
    /**
     * API Access Login
     *
     * @var string
     */
    protected $accessLogin;

    /**
     * API Access Password
     *
     * @var string
     */
    protected $accessPassword;

    /**
     * API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * API URL address
     *
     * @var string
     */
    protected $apiUrlAddress;

    /**
     * Client
     *
     * @var Guzzle\Http\Client
     */
    protected $client;

    public function __construct($accessLogin, $accessPassword, $apiKey, $apiUrlAddress = NULL){
        if (!isset($accessLogin) || !isset($accessPassword) || !isset($apiKey)) {
            throw new Exception('Missing API authentication info.');
        }

        $this->accessLogin = $accessLogin;
        $this->accessPassword = $accessPassword;
        $this->apiKey = $apiKey;
        if($apiUrlAddress == NULL){
            $this->apiUrlAddress = $apiUrlAddress;
        }else{
            $this->apiUrlAddress = 'https://pksbilety.pl';
        }

        $this->client = new Client();        
    }

    /**
     * Gets Access Login.
     *
     * @return string
     */
    public function getAccessLogin()
    {
        return $this->accessLogin;
    }

    /**
     * Gets Access Password.
     *
     * @return string
     */
    public function getAccessPassword()
    {
        return $this->accessPassword;
    }

    /**
     * Gets API Key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Search
     *    
     * @param int $origin is the origin ID
     * @param int $destination is the destination ID
     * @param string $departureDate format yyyy-mm-dd
     * @param string $timeOfDay (EarlyMorning, BeforeNoon, Afternoon, Evening)
     * @param string $routeType (Regular, Accelerated, Fast, Express, International, Special, Trip)
     * @return int|json
     */
    public function search($origin, $destination, $departureDate, $timeOfDay = null, $routeType = null)
    {
        $parameters = array(
            'Login' => $this->accessLogin,
            'Password' => $this->accessPassword,
            'DepartureDate' => $departureDate,
            'Origin' => $origin,
            'Destination' => $destination);
        if($timeOfDay){ $parameters['TimeOfDay'] = $timeOfDay; }
        if($routeType){ $parameters['RouteType'] = $routeType; }

        $request = $this->client->post($this->apiUrlAddress . '/api/search', null, $parameters);
        
        try{
            $response = $request->send();
        }catch(\Guzzle\Http\Exception\ClientErrorResponseException $e){
            $result['status'] = $e->getResponse()->getStatusCode();
            $result['body'] = $e->getResponse()->getBody(true);
            return $result;
        }
        
        $result['status'] = $response->getStatusCode();
        $result['body'] = $response->getBody(true);
        return $result;  
    }

    /**
     * RouteInfo
     *
     * @param int $origin is the origin ID
     * @param int $destination is the destination ID
     * @param string $departureDate format yyyy-mm-dd
     * @return int|json
     */
    public function routeInfo($origin, $destination, $departureDate = null)
    {
        $parameters = array(
            'Login' => $this->accessLogin,
            'Password' => $this->accessPassword,
            'Origin' => $origin,
            'Destination' => $destination);
        if($departureDate){ $parameters['DepartureDate'] = $departureDate; }

        $request = $this->client->post($this->apiUrlAddress . '/api/routeinfo', array(), $parameters);
        
        try{
            $response = $request->send();
        }catch(\Guzzle\Http\Exception\ClientErrorResponseException $e){
            $result['status'] = $e->getResponse()->getStatusCode();
            $result['body'] = $e->getResponse()->getBody(true);
            return $result;
        }
        
        $result['status'] = $response->getStatusCode();
        $result['body'] = $response->getBody(true);
        return $result;        
    }

    /**
     * BuyTicket
     *
     * @param int $origin is the origin ID
     * @param int $destination is the destination ID
     * @param string $departureDate format yyyy-mm-dd
     * @param string $paymentTransaction
     * @param string $quantity
     * @param string $test
     * @param string $selectedPromotionID
     * @param string $selectedReliefID
     * @return int|json
     */
    public function buyTicket($origin, $destination, $departureDate, $paymentTransaction, $quantity, $test = false, $selectedPromotionID = null, $selectedReliefID = null)
    {
        $signature = md5($this->accessLogin . $this->accessPassword . $this->apiKey);
        $parameters = array(
            'Login' => $this->accessLogin,
            'Password' => $this->accessPassword,
            'OriginID' => $origin,
            'DestinationID' => $destination,
            'DepartureDate' => $departureDate,
            'PaymentTransaction' => $paymentTransaction,
            'Quantity' => $quantity,
            'Signature' => $signature
            );
        if($test){ $parameters['Test'] = $test; }
        if($selectedPromotionID){ $parameters['SelectedPromotionID'] = $selectedPromotionID; }
        if($selectedReliefID){ $parameters['SelectedReliefID'] = $selectedReliefID; }

        $request = $this->client->post($this->apiUrlAddress . '/api/buyticket', array(), $parameters);

        try{
            $response = $request->send();
        }catch(\Guzzle\Http\Exception\ClientErrorResponseException $e){
            $result['status'] = $e->getResponse()->getStatusCode();
            $result['body'] = $e->getResponse()->getBody(true);
            return $result;
        }
        
        $result['status'] = $response->getStatusCode();
        $result['body'] = $response->getBody(true);
        return $result;
    }

    /**
     * ReturnTicket
     *
     * @param string $orderID
     * @param string $ticketId
     * @param string $test
     * @return int|json
     */
    public function returnTicket($orderID, $ticketId, $test = false)
    {
        $signature = md5($this->accessLogin . $this->accessPassword . $this->apiKey);
        $parameters = array(
            'Login' => $this->accessLogin,
            'Password' => $this->accessPassword,
            'OrderID' => $orderID,
            'TicketId' => $ticketId,
            'Signature' => $signature
            );
        if($test){ $parameters['Test'] = $test; }

        $request = $this->client->post($this->apiUrlAddress . '/api/returnticket', array(), $parameters);

        try{
            $response = $request->send();
        }catch(\Guzzle\Http\Exception\ClientErrorResponseException $e){
            $result['status'] = $e->getResponse()->getStatusCode();
            $result['body'] = $e->getResponse()->getBody(true);
            return $result;
        }
        
        $result['status'] = $response->getStatusCode();
        $result['body'] = $response->getBody(true);
        return $result;
    }

    /**
     * VehiclesOnRoute
     *
     * @return int|json
     */
    public function vehiclesOnRoute()
    {
        $parameters = array(
            'Login' => $this->accessLogin,
            'Password' => $this->accessPassword
            );

        $request = $this->client->post($this->apiUrlAddress . '/api/vehiclesonroute', array(), $parameters);

        try{
            $response = $request->send();
        }catch(\Guzzle\Http\Exception\ClientErrorResponseException $e){
            $result['status'] = $e->getResponse()->getStatusCode();
            $result['body'] = $e->getResponse()->getBody(true);
            return $result;
        }
        
        $result['status'] = $response->getStatusCode();
        $result['body'] = $response->getBody(true);
        return $result;
    }

    /**
     * Delayed
     *
     * @return int|json
     */
    public function delayed()
    {
        $parameters = array(
            'Login' => $this->accessLogin,
            'Password' => $this->accessPassword
            );

        $request = $this->client->post($this->apiUrlAddress . '/api/delayed', array(), $parameters);

        try{
            $response = $request->send();
        }catch(\Guzzle\Http\Exception\ClientErrorResponseException $e){
            $result['status'] = $e->getResponse()->getStatusCode();
            $result['body'] = $e->getResponse()->getBody(true);
            return $result;
        }
        
        $result['status'] = $response->getStatusCode();
        $result['body'] = $response->getBody(true);
        return $result;
    }

    /**
     * Combined Search
     *
     * @param int $origin is the origin ID
     * @param int $destination is the destination ID
     * @param string $departureDate format yyyy-mm-dd
     * @param string $timeOfDay (EarlyMorning, BeforeNoon, Afternoon, Evening)
     * @param string $routeType (Regular, Accelerated, Fast, Express, International, Special, Trip)
     * @return int|json
     */
    public function combinedSearch($origin, $destination, $departureDate, $timeOfDay = null, $routeType = null)
    {
        $parameters = array(
            'Login' => $this->accessLogin,
            'Password' => $this->accessPassword,
            'Origin' => $origin,
            'DepartureDate' => $departureDate,
            'Destination' => $destination);
        if($timeOfDay){ $parameters['TimeOfDay'] = $timeOfDay; }
        if($routeType){ $parameters['RouteType'] = $routeType; }

        $request = $this->client->post($this->apiUrlAddress . '/api/combined/search', array(), $parameters);
        
        try{
            $response = $request->send();
        }catch(\Guzzle\Http\Exception\ClientErrorResponseException $e){
            $result['status'] = $e->getResponse()->getStatusCode();
            $result['body'] = $e->getResponse()->getBody(true);
            return $result;
        }
        
        $result['status'] = $response->getStatusCode();
        $result['body'] = $response->getBody(true);
        return $result;      
    }


    public static function checkPBR()
    {
        $client = new Client();
        $request = $client->get('http://finance.yahoo.com/d/quotes.json?s=PBR&f=l1');
        $response = $request->send();
        echo $response->getBody();
    }
}
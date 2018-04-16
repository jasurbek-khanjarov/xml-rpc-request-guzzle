<?php

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Client;

class PpcRequest
{
    /**
     * @param $method method of request(POST, GET)
     * $param $url target url
     * @param $methodName methodname of xml-rpc
     * @param $params
     * @return array
     */
    public static function sendRequestAndGetResponse($method, $url, $methodName, $params): array
    {
        // create a new xml data
        $requestXmlData = new \SimpleXMLElement($params);
        // encode xml into xml-rpc with defining methodname
        $body = xmlrpc_encode_request($methodName, $requestXmlData);

        try {
            // create a new Guzzle client
            $client = new Client();
            // send xml-rpc request to server and get response
            $response = $client->request($method, $url, ['body' => $body]);
            
        } catch (BadResponseException $e) { // catch if server fails to respond correctly (the problem within client server)
        
            return;
        }

        // convert string response into xml data
        $responseBody = new \SimpleXMLElement($response->getBody()->getContents());
        
        return $responseBody;
    }
    

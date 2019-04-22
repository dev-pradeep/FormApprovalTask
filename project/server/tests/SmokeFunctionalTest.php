<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeFunctionalTest extends WebTestCase {
    const STATIC_API_KEY = "REAL_API_TOKEN";
    const API_URL        = "localhost:8001/api/";
    const BASE_URL       = "localhost:8001";

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSecure($url) {
        $client = self::createClient([]);
        $client->request('GET', $url);
        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url) {
        $url .= '/azertyx_token';
        $client = self::createClient([], []);
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider() {
        yield ['/messages/reject'];
    }

    /**
     * @dataProvider urlApiProvider
     */
    public function testAPIisSecure($url) {
        $client = self::createClient([]);
        $client->request('GET', $url, [], [], ['HTTP_X_AUTH_TOKEN' => 'incorrect_api_key', 'HTTP_ACCEPT' => 'application/json']);
        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider urlApiProvider
     */
    /*
    public function testAPIWorks($url) {
        $client = self::createClient([]);
        $client->request('GET', $url, [], [], ['HTTP_X_AUTH_TOKEN' => 'REAL_API_TOKEN', 'HTTP_ACCEPT' => 'application/json']);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }    
    */

    public function urlApiProvider() {  
        yield ['/api/admin/messages/{id}'];              
    }

    public function testMessagesRoute()
    {
        $client = new \GuzzleHttp\Client([
            'base_url' => self::API_URL,
            'defaults' => [
                'exceptions' => false,
                'headers'  => ['content-type' => 'application/json'],
            ]
        ]);
  
        $url = self::API_URL."messages"; 
        $response = $client->get($url);

        $json = $response->getBody(true);
        $body = json_decode($json, true);
  
        $this->assertEquals(200, $response->getStatusCode());
    }    

    public function testMessagePostRoute()
    {        
        $client = new \GuzzleHttp\Client(
            ['headers' => [
                'Content-Type' => 'application/json'
                ]
            ]
        );
 
        $title = 'Title '.rand(0, 999);        
        $description = 'description '.rand(0, 999);    
        $content = 'content '.rand(0, 999);    
        $email = 'email_'.rand(0, 999);    

        $data = array(
            'title' => $title,
            "description" => $description,
            'content' => $content,
            'email' => $email.'@gmail.com'
        );
       
        $url = self::API_URL."messages"; 
        $response = $client->post($url, [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201, $response->getStatusCode());
    }   
    
    public function testMessageApproveRoute()
    {        
        $client = new \GuzzleHttp\Client(
            ['headers' => [
                'Content-Type' => 'application/json'
                ]
            ]
        );
       
        $url = self::API_URL."messages/approve/1"; 
        $response = $client->post($url, [
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }     

    public function testRejectMessagesRoute()
    {        
        $client = new \GuzzleHttp\Client(
            ['headers' => [
                'Content-Type' => 'application/json'
                ]
            ]
        );
       
        $url = self::BASE_URL."/messages/reject/azertyx_token"; 
        $response = $client->get($url);

        $json = $response->getBody(true);
        $body = json_decode($json, true);
  
        $this->assertEquals(200, $response->getStatusCode());
    }        
}

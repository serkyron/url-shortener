<?php
/**
 * Created by PhpStorm.
 * User: serkyron
 * Date: 26.08.18
 * Time: 18:32
 */

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShortenerControllerTest extends WebTestCase
{
    public function testShorten()
    {
        $client = static::createClient();
        $uri = $client->getContainer()->get('router')->generate('shorten');
        $postData = [];
        $response = $this->sendPostRequest($client, $postData, $uri);
        dd($response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

//    public function testShortenNoUrlSupplied()
//    {
//
//    }

    private function sendPostRequest($client, array $postData, string $uri)
    {
        $client->request(
            'POST',
            $uri,
            $postData
        );

        return $client->getResponse();
    }
}
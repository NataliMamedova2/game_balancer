<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BattleControllerTest extends WebTestCase
{
    public function testBattleIndexPage()
    {
        $client = static::createClient();
        $client->request('GET', '/battle/');

        $response = $client->getResponse();

        $this->assertEquals(
            Response::HTTP_OK,
            $response->getStatusCode(),
            sprintf('The %s URL is correctly.', '/battle/')
        );
    }

    public function testBattleAjaxHandler()
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/battle/ajax', ['id' => 7]);

        $results = json_decode($client->getResponse()->getContent(), true);

        $this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));
        $this->assertCount(1, $results);
    }
}

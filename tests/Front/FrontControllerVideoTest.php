<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerVideoTest extends WebTestCase
{
    public function testNoRersults()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET','/');

        $form = $crawler->selectButton('Search video')->form([
            'query'=>'aaa',
        ]);

        $crawler = $client->submit($form);

        $this->assertContains('No results', $crawler->filter('h1')->text());
    }
}

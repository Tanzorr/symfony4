<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultcontrollerTest extends WebTestCase
{
    public function testSomething()
    {
       $client  = static ::cleateClient();
       $crawler = $client->requiest('CET', $this->provideUrls());

       $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideUrls() {
        return[
            ['/home'],
            ['/login']
        ];
    }
}

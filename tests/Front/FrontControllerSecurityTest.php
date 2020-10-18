<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerSecurityTest extends WebTestCase
{
  public function testSecureUrls(string $url)
  {
      $client = static::createClient();
      $client->request('GET', $url);
      $this->assertContans('/login', $client->getResponse()->getTargetUrl());
  }

  public function getsecureUrls()
  {
      yield ['/admin/videos'];
      yield ['/admin'];
      yield ['/admin/su/categories'];
      yield ['/admin/su/delete-category/1'];
  }

  public function testVideoForMembersOnly()
  {
      $client = static::createClient();
      $client->request('GET','/video-list/category/movies,4');
      $this->assertContains('Video for <b>MEMBERS</b> only.', $client->getResponse()->getContent());
  }
}

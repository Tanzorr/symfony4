<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $password_encoder)
    {
        $this->password_encoder = $password_encoder;

    }

    public function load(ObjectManager $manager)
    {
      foreach ($this->getUserData() as [$name, $last_name, $email, $password, $api_key, $roles ])
      {
          $user = new User();
          $user->setName($name);
          $user->setLastName($last_name);
          $user->setEmail($email);
          $user->setPassword($this->password_encoder->encodePassword($user, $password));
          $user->setVimeoApiKey($api_key);
          $user->setRoles($roles);
          $manager->persist($user);
      }
      $manager->flush();
    }

    private  function getUserData(): array
    {
        return [
            ['John', 'Wayne', 'jw@symf4.loc','passw', 'hjd8dehdh', ['ROLE_ADMIN']],
            ['John2', 'Wayne2', 'jw@symf14.loc','passw', 'hjd8dehdh', ['ROLE_ADMIN']],
            ['John2', 'Wayne4', 'jw@symf24.loc','passw', 'hjd8dehdh', ['ROLE_ADMIN']],
        ];
    }
}

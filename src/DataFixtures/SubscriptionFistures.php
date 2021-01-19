<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Subscription;
use App\Entity\User;

class SubscriptionFistures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       foreach ($this->getSubscriptionData() as [$user_id, $palan, $valid_to,
                $payment_status, $free_plan_used])
       {
           $subscription = new Subscription();
           $subscription->setPlan($palan);
           $subscription->setValidTo($valid_to);
           $subscription->setPaymantStatus($payment_status);
           $subscription->setFreePlanUsed($free_plan_used);
           $user = $manager->getRepository(User::class)->find($user_id);
           $user->setSubscription($subscription);
           $manager->persist($user);
       }

        $manager->flush();
    }

    private function getSubscriptionData():array
    {
        return [
            [1, Subscription::getPlanDataNameByIndex(2), (new \Datetime())->modify('+100 year'),'paid', false],
            [2, Subscription::getPlanDataNameByIndex(0), (new \Datetime())->modify('+100 year'),'paid', false],
            [3, Subscription::getPlanDataNameByIndex(1), (new \Datetime())->modify('+100 year'),'paid', false],
            [4, Subscription::getPlanDataNameByIndex(2), (new \Datetime())->modify('+100 year'),'paid', false],
        ];
    }
}

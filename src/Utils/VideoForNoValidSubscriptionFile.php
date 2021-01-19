<?php


namespace App\Utils;
use Symfony\Component\Security\Core\Security;
use App\Entity\Video;


class VideoForNoValidSubscriptionFile
{
    public $isSubscriptonValid =false;

    public function __construct(Security $security)
    {
        $user = $security->getUser();
        if ($user && $user->getSubscription() !=null) {
            $payment_status = $user->getSubscription()->getValidTo();
            $valid = new \DateTime();
            if ($payment_status !=null && $valid){
                $this->isSubscriptonValid = true;
            }
        }
    }

    public function check()
    {
        if ($this->isSubscriptonValid){
            return null;
        }
        else{
            static $video = Video::videoForNotLoggedInOrNotMemebrs;
            return $video;
        }
    }

}
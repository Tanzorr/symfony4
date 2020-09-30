<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class VideoCreatedSobscriberSubscriber implements EventSubscriberInterface
{
    public function onVideoCreatedEvent($event)
    {
        dump($event->video->title);
    }

    public function onKernelResponse1(FilterResponseEvent $event)
    {
        $response = new Response('dupa');
        $event->setResponse($response);
        dump(2);
    }

    public function onKernelResponse2(FilterResponseEvent $event)
    {
       // $response = new Response('dupa1');
       // $event->setResponse($response);
        dump(1);
    }

    public static function getSubscribedEvents()
    {
        return [
//            'video.created.event' => 'onVideoCreatedEvent',
//            KernelEvents::RESPONSE =>[
//                ['onKernelResponse1',2],
//                ['onKernelResponse2',1],
//                ]
        ];
    }
}

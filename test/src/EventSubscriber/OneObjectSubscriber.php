<?php

namespace App\EventSubscriber;

use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\OnlyOne;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OneObjectSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            DataObjectEvents::PRE_ADD => ['check', 1000]
        ];
    }

    /**
     * @throws \Exception
     */
    public function check(DataObjectEvent $event){
        $object = $event->getObject();
        if($object instanceof OnlyOne){
            if((new OnlyOne\Listing())->count() >= 1){
                $event->stopPropagation();
                throw new \Exception('Cant have more than 1 of this type of object');
            }
        }
    }
}

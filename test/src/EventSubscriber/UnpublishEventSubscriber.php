<?php

namespace App\EventSubscriber;

use App\Model\DataObject\Product;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UnpublishEventSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            DataObjectEvents::PRE_ADD => 'unpublish',
            DataObjectEvents::PRE_UPDATE => 'unpublish',
        ];
    }

    public function unpublish(DataObjectEvent $event){
        $object = $event->getObject();

        if($object instanceof Product){
            if($object->getDeletedAt()){
                $object->setPublished(false);
            }
        }
    }
}

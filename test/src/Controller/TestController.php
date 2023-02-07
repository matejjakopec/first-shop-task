<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\OnlyOne;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends FrontendController
{
    /**
     * @Route("/test", name="test")
     */
    public function createAction(){
        $one = new OnlyOne();
        $one->setText('awdawd');
        $one->setKey('1231243efaawdawdawdawdawdwdawdawdadawdawdwdawdsf');
        $one->setPublished(true);
        $one->setParentId(1);
        $one->save();

        return new Response('test');
    }

}

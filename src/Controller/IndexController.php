<?php
namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class IndexController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeRender(Event $event)
    {
        $this->viewBuilder()->setLayout('new_layout');
        parent::beforeRender($event);
    }

    public function index()
    {
    }
}

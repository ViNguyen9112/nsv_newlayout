<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link        https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Constants;

class CalendarController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('TBLMRepType');
        $this->loadModel('TBLMLanguage');
        $this->loadModel('TBLTTimeCard');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->loadModel('TBLMStaff');

        $this->viewBuilder()->setLayout('calendar');
    }

    /**
     *
     */
    public function index()
    {
        $staffIds = $this->TBLMStaff->getAllStaff();
        $this->set('staffIds', $staffIds);

        $staffid = $this->Auth->user('StaffID');
        $today = date('Y-m-d');
        $timecard = $this->TBLTTimeCard->find()
            ->where(['StaffID' => $staffid, 'Date' => $today])
            ->order(['TimeCardID' => 'DESC'])
            ->first();

        if ($timecard) {
            $this->set('timecard', $timecard);
        }

        $types = $this->TBLMRepType->find()->where(['FlagDelete' => 0]);
        $typeReports = [];
        $language = $this->request->session()->read('Config.language');
        $langIdx = Constants::$languages['report_idx'][$language] ?? 1;
        $langIdx = 'Type' . $langIdx;

        foreach ($types as $type) {
            $typeReports[] = [
                'name' => $type->$langIdx,
                'color' => $type->TypeColor
            ];
        }
        $this->set('typeReports', $typeReports);

        // get language
        $language = $this->request->session()->read('Config.language');
        $language_list = ['en_US' => 'EN', 'jp_JP' => 'JP', 'vn_VN' => 'VN'];

        $data_lang = $this->TBLMLanguage->find()
            ->select(['KeyString', $language_list[$language] . 'Language'])
            ->toArray();
        $data_language = [];
        foreach ($data_lang as $value) {
            $data_language[$value['KeyString']] = $value[$language_list[$language] . 'Language'];
        }

        $this->set('lang', $language_list[$language]);
        $this->set('data_language', $data_language);
    }
}

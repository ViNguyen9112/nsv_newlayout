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


use Cake\Chronos\Chronos;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Constants;
use Cake\I18n\Time;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Exception;
use PDO;
use Stichoza\GoogleTranslate\TranslateClient;
use \Gumlet\ImageResize;
use Cake\Routing\Router;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;

class MypageController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('TBLMStaff');
        $this->loadModel('TBLMArea');
        $this->loadModel('TBLMCustomer');
        $this->loadModel('TBLTTimeCard');
        $this->loadModel('TBLTReport');
        $this->loadModel('TBLTImageReport');
        $this->loadModel('TBLTFolder');
        $this->loadModel('TBLTFaceImage');
        $this->loadModel('TBLMLanguage');
        $this->loadModel('TBLMCategory');
        $this->loadModel('TBLTCheckResult');
        $this->loadModel('TBLTDistance');
        $this->loadModel('TBLMAreaStaff');
        $this->loadModel('TBLMRepType');
        $this->loadModel("TBLMRepCategory");
        $this->loadModel("TBLMRepDetail");
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->setLayout('mypage');
    }

    public function index()
    {
        $areas = $this->TBLMAreaStaff->find()->where(['StaffID' => $this->Auth->user('StaffID')])->toArray();
        $conditions = [];
        $arr_areas = [];
        foreach ($areas as $area) {
            array_push($arr_areas, $area->AreaID);
        }
        if (!empty($arr_areas)) {
            $conditions['AreaID IN'] = $arr_areas;
        }
        $listArea = $this->TBLMArea->find()->order('Name', 'ASC')->where($conditions)->toArray();
        $this->set('listArea', $listArea);
        $area = $this->TBLMArea->find()->order('Name', 'ASC')->where($conditions)->first();
        $staffid = $this->Auth->user('StaffID');
        $today = date('Y-m-d');
        $timecard = $this->TBLTTimeCard->find()
            ->where(['StaffID' => $staffid, 'Date' => $today])
            ->order(['TimeCardID' => 'DESC'])
            ->first();
        //********************************** GET CHECK IN/OUT ************* */
        if ($timecard && !$timecard->TimeOut) {
            $customer = $this->TBLMCustomer->find()->where(['CustomerID' => $timecard->CustomerID])->first();
            $area = $this->TBLMArea->find()->where(['AreaID' => $customer->AreaID])->first();
            $list_customer = $this->TBLMCustomer->find()->where(['AreaID' => $customer->AreaID])->toArray();
            $this->set('AreaID', $area->AreaID);
            $this->set('CustomerID', $customer->CustomerID);
            $this->set('listCustomer', $list_customer);
            // text time checkin
            $timeIn = $timecard->TimeIn;
            $timeIn = $timeIn->format('H:i:s');
            $timecard->TimeIn = date('H:i:s', strtotime($timeIn));
            // check checked-out
            if ($timecard->TimeOut) {
                $this->set('CheckedOut', 1);
                // text
                $timeOut = $timecard->TimeOut;
                $timeOut = $timeOut->format('H:i:s');
                $timecard->TimeOut = date('H:i:s', strtotime($timeOut));
            } else {
                $this->set('CheckedOut', 0);
            }
            $this->set('customerName', $customer->Name);
            $this->set('timecard', $timecard);
        }
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
		$this->set('data_language', array_merge($data_language, $this->getLanguage()['language_result']));

        //type report
        $session = $this->getRequest()->getSession();
        $language = $session->read('Config.language');
        $idxLanguage = Constants::$languages['report_idx'][$language];
        $types = $this->TBLMRepType->getAll("Type{$idxLanguage}");
        $this->set('types', $types);
		$this->set('staff', $this->TBLMStaff->find()->where(['StaffID' => $this->Auth->user('StaffID')])->first());
    }

	protected function getLanguage()
	{
		// get language
        $language = $this->request->session()->read('Config.language');
        $language_list = ['en_US' => 'EN', 'jp_JP' => 'JP', 'vn_VN' => 'VN'];
        $data_lang = $this->TBLMLanguage->find()->select(['KeyString', $language_list[$language] . 'Language'])->toArray();
        $data_language = [];
        foreach ($data_lang as $value) 
		{
            $data_language[$value['KeyString']] = $value[$language_list[$language] . 'Language'];
        }
		$customLanguage = array(
			'_overtime_title' => array(
				'VN' => 'Làm thêm giờ',
				'JP' => '時間とともに',
				'EN' => 'Over Time'
			),
			'_overtime_name' => array(
				'VN' => 'Nhân viên',
				'JP' => '名前',
				'EN' => 'Name'
			),
			'_overtime_from_time' => array(
				'VN' => 'Bắt đầu',
				'JP' => '始まる時間',
				'EN' => 'Start time'
			),
			'_overtime_time' => array(
				'VN' => 'Thời gian',
				'JP' => '限目',
				'EN' => 'Period'
			),
			'_overtime_to_time' => array(
				'VN' => 'Kết thúc',
				'JP' => '終了時間',
				'EN' => 'Time'
			),
			'_overtime_date' => array(
				'VN' => 'Ngày',
				'JP' => '日にち',
				'EN' => 'Date'
			),
			'_overtime_total' => array(
				'VN' => 'Tổng',
				'JP' => '合計',
				'EN' => 'Total'
			),
			'_overtime_preview' => array(
				'VN' => 'Xem trước',
				'JP' => 'プレビュー',
				'EN' => 'Preview'
			),
			'_overtime_submit' => array(
				'VN' => 'Gửi',
				'JP' => '送信',
				'EN' => 'Submit'
			),
			'_overtime_back' => array(
				'VN' => 'Quay lại',
				'JP' => '戻る',
				'EN' => 'Back'
			),
			'_overtime_validate_time' => array(
				'VN' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu',
				'JP' => '終了時間は開始時間よりも長くする必要があります',
				'EN' => 'End time must be greater than start time'
			),
			'_overtime_validate_start_time' => array(
				'VN' => 'Thời gian bắt đầu phải lớn hơn thời gian hiện tại',
				'JP' => '開始時間は現在の時間より大きくなければなりません',
				'EN' => 'Start time must be greater than current time'
			),
			'_overtime_minutes' => array(
				'VN' => 'phút',
				'JP' => '分',
				'EN' => 'minutes'
			),
			'_overtime_hours' => array(
				'VN' => 'giờ',
				'JP' => '時間',
				'EN' => 'hour'
			),
			'_overtime_validate_success' => array(
				'VN' => 'Thành công',
				'JP' => '成功',
				'EN' => 'Success'
			),
			'_overtime_validate_error' => array(
				'VN' => 'Không gởi được, vui lòng thử lại sau.',
				'JP' => '送信に失敗しました。後でもう一度やり直してください。',
				'EN' => 'Failed to send, please try again later.'
			),
			'_overtime_validate_error_exits' => array(
				'VN' => 'Bạn đã đăng ký làm thêm giờ vào lúc {start} đến {end}',
				'JP' => 'あなたは{start}から{end}に残業するために登録しました',
				'EN' => 'You have registered to work overtime at {start} to {end}'
			),
			'_overtime_permission' => array(
				'VN' => 'Chỉ có Leader và Area Leader mới có quyền truy cập khu vực này',
				'JP' => 'このエリアにアクセスできるのはLeaderとArea Leaderだけです',
				'EN' => 'Only Leader and Area Leader have access to this area'
			),
			'_overtime_accept' => array(
				'VN' => 'Đã duyệt',
				'JP' => '承認済み',
				'EN' => 'Approved'
			),
			'_overtime_approve' => array(
				'VN' => 'Duyệt',
				'JP' => '承認',
				'EN' => 'Approve'
			),
			'_overtime_refuse' => array(
				'VN' => 'Từ chối',
				'JP' => '未承認',
				'EN' => 'Refuse'
			),
			'_overtime_pending' => array(
				'VN' => 'Chờ duyệt',
				'JP' => '保留中',
				'EN' => 'Pending'
			),
			'_overtime_no_data' => array(
				'VN' => 'Không có dữ liệu',
				'JP' => 'データなし',
				'EN' => 'No data'
			),
			'_overtime_no_leaders' => array(
				'VN' => 'Không tìm thấy quản lý của bạn, liên hệ quản trị viên để hỗ trợ',
				'JP' => 'マネージャーが見つかりませんでした。詳細については、管理者にお問い合わせください',
				'EN' => 'Your manager could not be found, contact admin for more information'
			),
			'_annualleave_title' => array(
				'VN' => 'Nghỉ phép',
				'JP' => '年次休暇',
				'EN' => 'Annual Leave'
			),
			'_annualleave_type' => array(
				'VN' => 'Loại',
				'JP' => 'タイプ',
				'EN' => 'Type'
			),
			'_annualleave_reason' => array(
				'VN' => 'Lý do',
				'JP' => '理由',
				'EN' => 'Reason'
			),
            '_annualleave_status' => array(
				'VN' => 'Trạng thái',
				'JP' => '状態',
				'EN' => 'Status'
			),
            '_annualleave_validate_error_exits' => array(
				'VN' => 'Bạn đã đăng ký nghỉ phép vào thời gian {start} - {end}',
				'JP' => '{start}から{end}への年次休暇に登録しました',
				'EN' => 'You have registered to annual leave at {start} to {end}'
			),
			'_annualleave_leader_accept' => array(
				'VN' => 'Leader đã duyệt',
				'JP' => 'リーダー承認',
				'EN' => 'Leader approved'
			),
			'_annualleave_manager_accept' => array(
				'VN' => 'Manager đã duyệt',
				'JP' => '{start}から{end}への年次休暇に登録しました',
				'EN' => 'マネージャーが承認'
			),
			'_annualleave_year' => array(
				'VN' => 'Năm',
				'JP' => '年',
				'EN' => 'Year'
			),
			'_annualleave_month' => array(
				'VN' => 'Tháng',
				'JP' => '月',
				'EN' => 'Month'
			),
		);
		return array(
			'language_choice' => $language_list[$language],
			'language_result' => array_merge($data_language, $customLanguage)
		);
	}

	public function overtime(){
		$isPost = $this->request->is('post');
		$language = $this->getLanguage();
		$staff = $this->TBLMStaff->find()->where(['StaffID' => $this->Auth->user('StaffID')])->first();
		$leaderConfig = array('Leader', 'Area Leader');
		if (!in_array($staff->Position, $leaderConfig)) 
		{
			$this->set('flash', $language['language_result']['_overtime_permission'][$language['language_choice']]);
			return $this->redirect(Router::url('/', true));
		}
		if($isPost)
		{
			$this->loadModel('TBLTOverTime');
			$this->loadModel('TBLTNotice');
			$result = array(
				'code'	=>	403
			);
			$StartDate = $this->getRequest()->getData('StartDate');
			$FromTime = $this->getRequest()->getData('FromTime');
			$ToTime = $this->getRequest()->getData('ToTime');
			if($StartDate && $FromTime && $ToTime)
			{
				// get list top level
				$leaders = array();
				if($staff->Position == 'Leader')
				{
					$area = $this->TBLMAreaStaff->getAreaManager($staff->StaffID);
					$listUser = array();
					foreach ($this->TBLMAreaStaff->find('all', ['conditions' => ['AreaID IN' => $area]])->toArray() as $item) {
						$listUser[] = $item->StaffID;
					}
					$leaders = $this->TBLMStaff->find('all', ['conditions' => ['StaffID IN' => $listUser, 'Position' => 'Area Leader']])->toArray();
				}
				else if($staff->Position == 'Area Leader')
				{
					$leaders = $this->TBLMStaff->find('all', ['conditions' => ['Position NOT IN' => $leaderConfig, 'Region' => $staff->Region]])->toArray();
				}
				if(!$leaders)
				{
					$result = array(
						'code'	=>	404,
						'msg'	=>	$language['language_result']['_overtime_no_leaders'][$language['language_choice']]
					);
				}
				else
				{
					$start = strtotime($StartDate.' '.$FromTime.':00');
					$end = strtotime($StartDate.' '.$ToTime.':00');
					$startFormat = date('Y-m-d H:i:s', $start);
					$endFormat = date('Y-m-d H:i:s', $end);
					if($this->TBLTOverTime->checkTime(array('StaffID' => $this->Auth->user('StaffID'), 'StartTime' => $startFormat, 'EndTime' => $endFormat)) > 0)
					{
						$result = array(
							'code'	=>	409,
							'msg'	=>	str_replace(array('{start}', '{end}'), array($FromTime, $ToTime), $language['language_result']['_overtime_validate_error_exits'][$language['language_choice']])
						);
					}
					// else if($start < $end && $start >= strtotime(date('Y-m-d')))
					else if($start < $end)
					{
						$overtime = $this->TBLTOverTime->newEntity();
						$overtime->StaffID = $this->Auth->user('StaffID');
						$overtime->StartTime = $startFormat;
						$overtime->EndTime = $endFormat;
						$overtime->Total = ($end - $start)/60;
						$saved = $this->TBLTOverTime->save($overtime);
						if(isset($saved->ID) && $saved->ID > 0)
						{
							$notify = array();
							foreach($leaders as $leader)
							{
								$input = $this->TBLTNotice->newEntity();
								$input->StaffID = $leader->StaffID;
								$input->OverTimeID = $saved->ID;
								$s = $this->TBLTNotice->save($input);
								if(isset($s->ID) && $s->ID > 0)
								{
									$notify[$leader->ID] = $s->ID;
								}
							}
							$result = array(
								'code'	=>	200,
								'result' => $notify
							);
						}
					}
				}
			}
			return $this->response->withType('application/json')->withStringBody(json_encode($result));
		}
		$this->set('staff', $staff);
		$this->set('data_language', $language['language_result']);
		$this->set('lang', $language['language_choice']);
	}

	public function otmanagement(){
		$isPost = $this->request->is('post');
		$language = $this->getLanguage();
		$staff = $this->TBLMStaff->find()->where(['StaffID' => $this->Auth->user('StaffID')])->first();
		$leaderConfig = array('Leader', 'Area Leader');
		if (!in_array($staff->Position, $leaderConfig)) 
		{
			$this->set('flash', $language['language_result']['_overtime_permission'][$language['language_choice']]);
			return $this->redirect(Router::url('/', true));
		}
		if($isPost)
		{
			$this->loadModel('TBLTOverTime');
			$this->loadModel('TBLTNotice');
			$result = array(
				'data' => array(),
				'leaders' => array()
			);
			$Year = (int)$this->getRequest()->getData('Year');
			$Month = (int)$this->getRequest()->getData('Month');
			if($Year > 2000)
			{
				if($Month >= 1 && $Month <= 12)
				{
					$result['data'] = $this->TBLTOverTime->find()->where(['StaffID' => $this->Auth->user('StaffID'), 'YEAR(StartTime)' => $Year, 'MONTH(StartTime)' => $Month])->order(['Status' => 'ASC', 'CreatedAt' => 'DESC'])->toArray();
				}
				else
				{
					$result['data'] = $this->TBLTOverTime->find()->where(['StaffID' => $this->Auth->user('StaffID'), 'YEAR(StartTime)' => $Year])->order(['Status' => 'ASC', 'CreatedAt' => 'DESC'])->toArray();
				}
				if(!empty($result['data']))
				{
					$leaders = array();
					if($staff->Position == 'Leader')
					{
						$area = $this->TBLMAreaStaff->getAreaManager($staff->StaffID);
						$listUser = array();
						foreach ($this->TBLMAreaStaff->find('all', ['conditions' => ['AreaID IN' => $area]])->toArray() as $item) {
							$listUser[] = $item->StaffID;
						}
						$leaders = $this->TBLMStaff->find('all', ['conditions' => ['StaffID IN' => $listUser, 'Position' => 'Area Leader']])->select(['StaffID', 'Name', 'Position'])->toArray();
					}
					else if($staff->Position == 'Area Leader')
					{
						$leaders = $this->TBLMStaff->find('all', ['conditions' => ['Position NOT IN' => $leaderConfig, 'Region' => $staff->Region]])->select(['StaffID', 'Name', 'Position'])->toArray();
					}
					$result['leaders'] = $leaders;
				}
			}
			return $this->response->withType('application/json')->withStringBody(json_encode($result));
		}
		$this->set('staff', $staff);
		$this->set('data_language', $language['language_result']);
		$this->set('lang', $language['language_choice']);
	}

	public function otrequest(){
		$isPost = $this->request->is('post');
		$language = $this->getLanguage();
		$staff = $this->TBLMStaff->find()->where(['StaffID' => $this->Auth->user('StaffID')])->first();
		$leaderConfig = array('Area Leader');
		if (!in_array($staff->Position, $leaderConfig)) 
		{
			$this->set('flash', $language['language_result']['_overtime_permission'][$language['language_choice']]);
			return $this->redirect(Router::url('/', true));
		}
		if($isPost)
		{
			$this->loadModel('TBLTOverTime');
			$this->loadModel('TBLTNotice');
			$t = $this->getRequest()->getData('Type');
			if($t == 'list')
			{
				$result = array(
					'data' => array(),
					'staff' => array()
				);
				$Year = (int)$this->getRequest()->getData('Year');
				$Month = (int)$this->getRequest()->getData('Month');
				if($Year > 2000)
				{
					if($Month >= 1 && $Month <= 12)
					{
						$result['data'] = $this->TBLTNotice->find()->select(['TBLTOverTime.Status','TBLTOverTime.ID','TBLTOverTime.StaffID','TBLTOverTime.Total','TBLTOverTime.StartTime','TBLTOverTime.EndTime','TBLMStaff.Name','TBLMStaff.StaffID','TBLTNotice.Notification','TBLTNotice.ID'])->contain(['TBLTOverTime', 'TBLMStaff'])->where(['TBLTNotice.StaffID' => $this->Auth->user('StaffID'), 'YEAR(TBLTOverTime.StartTime)' => $Year, 'MONTH(TBLTOverTime.StartTime)' => $Month])->order(['TBLTNotice.Approved' => 'ASC', 'TBLTNotice.CreatedAt' => 'DESC'])->toArray();
					}
					else
					{
						$result['data'] = $this->TBLTNotice->find()->select(['TBLTOverTime.Status','TBLTOverTime.ID','TBLTOverTime.StaffID','TBLTOverTime.Total','TBLTOverTime.StartTime','TBLTOverTime.EndTime','TBLMStaff.Name','TBLMStaff.StaffID','TBLTNotice.Notification','TBLTNotice.ID'])->contain(['TBLTOverTime', 'TBLMStaff'])->where(['TBLTNotice.StaffID' => $this->Auth->user('StaffID'), 'YEAR(TBLTOverTime.StartTime)' => $Year])->order(['TBLTNotice.Approved' => 'ASC', 'TBLTNotice.CreatedAt' => 'DESC'])->toArray();
					}
					if(!empty($result['data']))
					{
						$listStaffArr = array();
						foreach($result['data'] as $item)
						{
							$listStaffArr[] = $item->TBLTOverTime->StaffID;
						}
						$staffs = $this->TBLMStaff->find()->select(['StaffID', 'Name', 'Position'])->where(['StaffID IN' => $listStaffArr])->toArray();
						if(!empty($staffs))
						{
							foreach($staffs as $staff)
							{
								$result['staff'][$staff->StaffID] = $staff;
							}
						}
					}
				}
			}
			else if($t == 'action')
			{
				$ID = $this->getRequest()->getData('ID');
				$action = $this->getRequest()->getData('Action');
				if(in_array($action, array('approve', 'refuse')) && count($ID) == 2 && (int)$ID[0] > 0 && (int)$ID[1] > 0)
				{
					$actionVal = $action == 'approve' ? 1 : 2;
					$noticeRS = $this->TBLTNotice->updateAll(
						["Approved" => $actionVal, 'UpdatedAt' => date('Y-m-d H:i:s')],
						["ID" => $ID[1]] 
					);
					$OverTimeRS = $this->TBLTOverTime->updateAll(
						["Status" => $actionVal, "Notification" => 0, 'UpdatedAt' => date('Y-m-d H:i:s')],
						["ID" => $ID[0]] 
					);
					if($noticeRS && $OverTimeRS)
					{
						$result['data'] = $actionVal;
					}
				}
			}
			return $this->response->withType('application/json')->withStringBody(json_encode($result));
		}
		$this->set('staff', $staff);
		$this->set('data_language', $language['language_result']);
		$this->set('lang', $language['language_choice']);
	}

	protected function numberOfWorkingDays($startDate, $endDate) {
		$begin = strtotime($startDate);
		$end   = strtotime($endDate);
		if ($begin > $end) {
			return 0;
		} else {
			$no_days  = 0;
			while ($begin <= $end) {
				$what_day = date("N", $begin);
				if (!in_array($what_day, [6,7]) ) // 6 and 7 are weekend
					$no_days++;
				$begin += 86400; // +1 day
			};

			return $no_days;
		}
	}

	public function annualleave(){
		$isPost = $this->request->is('post');
		$language = $this->getLanguage();
		$staff = $this->TBLMStaff->find()->where(['StaffID' => $this->Auth->user('StaffID')])->first();
		$leaderConfig = array('Leader', 'Area Leader');
		if (!in_array($staff->Position, $leaderConfig)) 
		{
			$this->set('flash', $language['language_result']['_overtime_permission'][$language['language_choice']]);
			return $this->redirect(Router::url('/', true));
		}
		if($isPost)
		{
			$this->loadModel('TBLTAnnualLeave');
			$this->loadModel('TBLTNoticeal');
			$result = array(
				'code'	=>	403
			);
			$FromDate = $this->getRequest()->getData('StartDate');
			$ToDate = $this->getRequest()->getData('ToDate');
			$Type = $this->getRequest()->getData('Type');
			$Reason = addslashes(strip_tags(substr($this->getRequest()->getData('Reason'), 0, 500)));
			if($FromDate && $ToDate && in_array($Type, array('al', 'ul')) && $Reason)
			{
				// get list top level
				$leaders = array();
				if($staff->Position == 'Leader')
				{
					$area = $this->TBLMAreaStaff->getAreaManager($staff->StaffID);
					$listUser = array();
					foreach ($this->TBLMAreaStaff->find('all', ['conditions' => ['AreaID IN' => $area]])->toArray() as $item) {
						$listUser[] = $item->StaffID;
					}
					$leaders = $this->TBLMStaff->find('all', ['conditions' => ['StaffID IN' => $listUser, 'Position' => 'Area Leader']])->toArray();
				}
				else if($staff->Position == 'Area Leader')
				{
					$leaders = $this->TBLMStaff->find('all', ['conditions' => ['Position NOT IN' => $leaderConfig, 'Region' => $staff->Region]])->toArray();
				}
				if(!$leaders)
				{
					$result = array(
						'code'	=>	404,
						'msg'	=>	$language['language_result']['_overtime_no_leaders'][$language['language_choice']]
					);
				}
				else
				{
					$start = strtotime($FromDate);
					$end = strtotime($ToDate);
					$startFormat = date('Y-m-d', $start);
					$endFormat = date('Y-m-d', $end);
					//var_dump($this->TBLTAnnualLeave->checkTime(array('StaffID' => $this->Auth->user('StaffID'), 'FromDate' => $startFormat, 'ToDate' => $endFormat))); die;
					if($this->TBLTAnnualLeave->checkTime(array('StaffID' => $this->Auth->user('StaffID'), 'FromDate' => $startFormat, 'ToDate' => $endFormat)) > 0)
					{
						$result = array(
							'code'	=>	409,
							'msg'	=>	str_replace(array('{start}', '{end}'), array($FromDate, $ToDate), $language['language_result']['_annualleave_validate_error_exits'][$language['language_choice']])
						);
					}
					else if($start >= strtotime(date('Y-m-d')) && $start <= $end)
					{
						$overtime = $this->TBLTAnnualLeave->newEntity();
						$overtime->StaffID = $this->Auth->user('StaffID');
						$overtime->FromDate = $startFormat;
						$overtime->ToDate = $endFormat;
						$overtime->Total = $this->numberOfWorkingDays($startFormat, $endFormat);
						$overtime->Type = $Type;
						$overtime->Reason = $Reason;
						if($staff->Position == 'Area Leader')
						{
							$overtime->Status = 1;
						}
						$saved = $this->TBLTAnnualLeave->save($overtime);
						if(isset($saved->ID) && $saved->ID > 0)
						{
							$notify = array();
							foreach($leaders as $leader)
							{
								$input = $this->TBLTNoticeal->newEntity();
								$input->StaffID = $leader->StaffID;
								$input->AnnualLeaveID = $saved->ID;
								if($staff->Position == 'Area Leader')
								{
									$input->Status = 1;
								}
								$s = $this->TBLTNoticeal->save($input);
								if(isset($s->ID) && $s->ID > 0)
								{
									$notify[$leader->ID] = $s->ID;
								}
							}
							$result = array(
								'code'	=>	200,
								'result' => $notify
							);
						}
					}
				}
			}
			return $this->response->withType('application/json')->withStringBody(json_encode($result));
		}
		$this->set('staff', $staff);
		$this->set('data_language', $language['language_result']);
		$this->set('lang', $language['language_choice']);
	}
    
    public function almanagement(){
		$isPost = $this->request->is('post');
		$language = $this->getLanguage();
		$staff = $this->TBLMStaff->find()->where(['StaffID' => $this->Auth->user('StaffID')])->first();
		$leaderConfig = array('Leader', 'Area Leader');
		if (!in_array($staff->Position, $leaderConfig)) 
		{
			$this->set('flash', $language['language_result']['_overtime_permission'][$language['language_choice']]);
			return $this->redirect(Router::url('/', true));
		}
		if($isPost)
		{
			$this->loadModel('TBLTAnnualLeave');
			$this->loadModel('TBLTNoticeal');
			$result = array(
				'data' => array(),
				'leaders' => array()
			);
			$Year = (int)$this->getRequest()->getData('Year');
			$Month = (int)$this->getRequest()->getData('Month');
			if($Year > 2000)
			{
				if($Month >= 1 && $Month <= 12)
				{
					$result['data'] = $this->TBLTAnnualLeave->find()->where(['StaffID' => $this->Auth->user('StaffID'), 'YEAR(FromDate)' => $Year, 'MONTH(FromDate)' => $Month])->order(['Status' => 'ASC', 'CreatedAt' => 'DESC'])->toArray();
				}
				else
				{
					$result['data'] = $this->TBLTAnnualLeave->find()->where(['StaffID' => $this->Auth->user('StaffID'), 'YEAR(FromDate)' => $Year])->order(['Status' => 'ASC', 'CreatedAt' => 'DESC'])->toArray();
				}
				if(!empty($result['data']))
				{
					$leaders = array();
					if($staff->Position == 'Leader')
					{
						$area = $this->TBLMAreaStaff->getAreaManager($staff->StaffID);
						$listUser = array();
						foreach ($this->TBLMAreaStaff->find('all', ['conditions' => ['AreaID IN' => $area]])->toArray() as $item) {
							$listUser[] = $item->StaffID;
						}
						$leaders = $this->TBLMStaff->find('all', ['conditions' => ['StaffID IN' => $listUser, 'Position' => 'Area Leader']])->select(['StaffID', 'Name', 'Position'])->toArray();
					}
					else if($staff->Position == 'Area Leader')
					{
						$leaders = $this->TBLMStaff->find('all', ['conditions' => ['Position NOT IN' => $leaderConfig, 'Region' => $staff->Region]])->select(['StaffID', 'Name', 'Position'])->toArray();
					}
					$result['leaders'] = $leaders;
				}
			}
			return $this->response->withType('application/json')->withStringBody(json_encode($result));
		}
		$this->set('staff', $staff);
		$this->set('data_language', $language['language_result']);
		$this->set('lang', $language['language_choice']);
	}
    
    public function alrequest(){
		$isPost = $this->request->is('post');
		$language = $this->getLanguage();
		$staff = $this->TBLMStaff->find()->where(['StaffID' => $this->Auth->user('StaffID')])->first();
		$leaderConfig = array('Area Leader');
		if (!in_array($staff->Position, $leaderConfig)) 
		{
			$this->set('flash', $language['language_result']['_overtime_permission'][$language['language_choice']]);
			return $this->redirect(Router::url('/', true));
		}
		if($isPost)
		{
			$this->loadModel('TBLTAnnualLeave');
			$this->loadModel('TBLTNoticeal');
			$t = $this->getRequest()->getData('Type');
			if($t == 'list')
			{
				$result = array(
					'data' => array(),
					'staff' => array()
				);
				$Year = (int)$this->getRequest()->getData('Year');
				$Month = (int)$this->getRequest()->getData('Month');
				if($Year > 2000)
				{
					if($Month >= 1 && $Month <= 12)
					{
						$result['data'] = $this->TBLTNoticeal->find()->select(['TBLTAnnualLeave.Status','TBLTAnnualLeave.ID','TBLTAnnualLeave.StaffID','TBLTAnnualLeave.Total','TBLTAnnualLeave.FromDate','TBLTAnnualLeave.ToDate','TBLMStaff.Name','TBLMStaff.StaffID','TBLTNoticeal.Notification','TBLTNoticeal.ID'])->contain(['TBLTAnnualLeave', 'TBLMStaff'])->where(['TBLTNoticeal.StaffID' => $this->Auth->user('StaffID'), 'YEAR(TBLTAnnualLeave.FromDate)' => $Year, 'MONTH(TBLTAnnualLeave.FromDate)' => $Month])->order(['TBLTNoticeal.Approved' => 'ASC', 'TBLTNoticeal.CreatedAt' => 'DESC'])->toArray();
					}
					else
					{
						$result['data'] = $this->TBLTNoticeal->find()->select(['TBLTAnnualLeave.Status','TBLTAnnualLeave.ID','TBLTAnnualLeave.StaffID','TBLTAnnualLeave.Total','TBLTAnnualLeave.FromDate','TBLTAnnualLeave.ToDate','TBLMStaff.Name','TBLMStaff.StaffID','TBLTNoticeal.Notification','TBLTNoticeal.ID'])->contain(['TBLTAnnualLeave', 'TBLMStaff'])->where(['TBLTNoticeal.StaffID' => $this->Auth->user('StaffID'), 'YEAR(TBLTAnnualLeave.FromDate)' => $Year])->order(['TBLTNoticeal.Approved' => 'ASC', 'TBLTNoticeal.CreatedAt' => 'DESC'])->toArray();
					}
					if(!empty($result['data']))
					{
						$listStaffArr = array();
						foreach($result['data'] as $item)
						{
							$listStaffArr[] = $item->TBLTAnnualLeave->StaffID;
						}
						$staffs = $this->TBLMStaff->find()->select(['StaffID', 'Name', 'Position'])->where(['StaffID IN' => $listStaffArr])->toArray();
						if(!empty($staffs))
						{
							foreach($staffs as $staff)
							{
								$result['staff'][$staff->StaffID] = $staff;
							}
						}
					}
				}
			}
			else if($t == 'action')
			{
				$ID = $this->getRequest()->getData('ID');
				$action = $this->getRequest()->getData('Action');
				if(in_array($action, array('approve', 'refuse')) && count($ID) == 2 && (int)$ID[0] > 0 && (int)$ID[1] > 0)
				{
					$actionVal = $action == 'approve' ? 1 : 2;
					$noticeRS = $this->TBLTNoticeal->updateAll(
						["Approved" => $actionVal, 'UpdatedAt' => date('Y-m-d H:i:s')],
						["ID" => $ID[1]] 
					);
					$OverTimeRS = $this->TBLTAnnualLeave->updateAll(
						["Status" => $actionVal, "Notification" => 0, 'UpdatedAt' => date('Y-m-d H:i:s')],
						["ID" => $ID[0]] 
					);
					if($noticeRS && $OverTimeRS && $actionVal == 1)
					{
						$leaders = $this->TBLMStaff->find('all', ['conditions' => ['Position NOT IN' => array('Leader', 'Area Leader'), 'Region' => $staff->Region]])->toArray();
						if(!empty($leaders))
						{
							$notify = array();
							foreach($leaders as $leader)
							{
								$input = $this->TBLTNoticeal->newEntity();
								$input->StaffID = $leader->StaffID;
								$input->AnnualLeaveID = $ID[0];
								$s = $this->TBLTNoticeal->save($input);
								if(isset($s->ID) && $s->ID > 0)
								{
									$notify[$leader->ID] = $s->ID;
								}
							}
						}
					}
					$result['data'] = $actionVal;
				}
			}
			return $this->response->withType('application/json')->withStringBody(json_encode($result));
		}
		$this->set('staff', $staff);
		$this->set('data_language', $language['language_result']);
		$this->set('lang', $language['language_choice']);
	}

	public function notice(){
		$result = array(
			'total' => 0,
			'data' => array()
		);
		$isPost = $this->request->is('post');
		if($isPost)
		{
			$this->loadModel('TBLTOverTime');
			$this->loadModel('TBLTNotice');
            $this->loadModel('TBLTAnnualLeave');
			$this->loadModel('TBLTNoticeal');
			$role = $this->getRequest()->getData('role');
			$staff = $this->TBLMStaff->find()->where(['StaffID' => $this->Auth->user('StaffID')])->first();
			if($role == 'mark_readed')
			{
				$result['total'] = $this->TBLTOverTime->updateAll(
					["Notification" => 1],
					["StaffID" => $this->Auth->user('StaffID'), "Notification" => 0] 
				);
                $result['total'] += $this->TBLTAnnualLeave->updateAll(
					["Notification" => 1],
					["StaffID" => $this->Auth->user('StaffID'), "Notification" => 0] 
				);
				if($staff->Position == 'Area Leader')
				{
					$result['total'] += $this->TBLTNotice->updateAll(
						["Notification" => 1],
						["StaffID" => $this->Auth->user('StaffID'), "Notification" => 0] 
					);
                    $result['total'] += $this->TBLTNoticeal->updateAll(
						["Notification" => 1],
						["StaffID" => $this->Auth->user('StaffID'), "Notification" => 0] 
					);
				}
			}
			else
			{
                // OT data
				$result['data'] = $this->TBLTOverTime->find()->where(['StaffID' => $this->Auth->user('StaffID')])->order(['Status' => 'ASC', 'CreatedAt' => 'DESC'])->limit(100)->toArray();
				$result['total'] = $result['total_my_ot'] = $this->TBLTOverTime->find()->where(['StaffID' => $this->Auth->user('StaffID'), 'Notification' => 0])->count();
                // AL data
                $result['data_al'] = $this->TBLTAnnualLeave->find()->where(['StaffID' => $this->Auth->user('StaffID')])->order(['Status' => 'ASC', 'CreatedAt' => 'DESC'])->limit(100)->toArray();
				$result['total_al'] = $result['total_my_al'] = $this->TBLTAnnualLeave->find()->where(['StaffID' => $this->Auth->user('StaffID'), 'Notification' => 0])->count();
				if($staff->Position == 'Area Leader')
				{
                    // OT data
					$totalRequestOT = $this->TBLTNotice->find()->where(['StaffID' => $this->Auth->user('StaffID'), 'Notification' => 0])->count();
					$result['total'] += $totalRequestOT;
					$result['total_request_ot'] = $totalRequestOT;
					$result['data_request_ot'] = $this->TBLTNotice->find()->select(['TBLTOverTime.Status','TBLTOverTime.StaffID','TBLTOverTime.Total','TBLTOverTime.StartTime','TBLTOverTime.EndTime','TBLMStaff.Name','TBLMStaff.StaffID','TBLTNotice.Notification'])->contain(['TBLTOverTime', 'TBLMStaff'])->where(['TBLTNotice.StaffID' => $this->Auth->user('StaffID')])->order(['TBLTNotice.Approved' => 'ASC', 'TBLTNotice.CreatedAt' => 'DESC'])->limit(100)->toArray();
					if(!empty($result['data_request_ot']))
					{
						$listStaffArr = array();
						foreach($result['data_request_ot'] as $item)
						{
							if(isset($item->TBLTOverTime->StaffID))
							{
								$listStaffArr[] = $item->TBLTOverTime->StaffID;
							}
						}
						$staffs = $this->TBLMStaff->find()->select(['StaffID', 'Name', 'Position'])->where(['StaffID IN' => $listStaffArr])->toArray();
						if(!empty($staffs))
						{
							foreach($staffs as $staff)
							{
								$result['staff'][$staff->StaffID] = $staff;
							}
						}
					}
                    // AL data
                    $totalRequestAL = $this->TBLTNoticeal->find()->where(['StaffID' => $this->Auth->user('StaffID'), 'Notification' => 0])->count();
					$result['total_al'] += $totalRequestAL;
					$result['total_request_al'] = $totalRequestAL;
					$result['data_request_al'] = $this->TBLTNoticeal->find()->select(['TBLTAnnualLeave.Status','TBLTAnnualLeave.StaffID','TBLTAnnualLeave.Total','TBLTAnnualLeave.FromDate','TBLTAnnualLeave.ToDate','TBLMStaff.Name','TBLMStaff.StaffID','TBLTNoticeal.Notification'])->contain(['TBLTAnnualLeave', 'TBLMStaff'])->where(['TBLTNoticeal.StaffID' => $this->Auth->user('StaffID')])->order(['TBLTNoticeal.Approved' => 'ASC', 'TBLTNoticeal.CreatedAt' => 'DESC'])->limit(100)->toArray();
					if(!empty($result['data_request_al']))
					{
						foreach($result['data_request_al'] as $item)
						{
							if(isset($item->TBLTAnnualLeave->StaffID))
							{
								$listStaffArr[] = $item->TBLTAnnualLeave->StaffID;
							}
						}
						$staffs = $this->TBLMStaff->find()->select(['StaffID', 'Name', 'Position'])->where(['StaffID IN' => $listStaffArr])->toArray();
						if(!empty($staffs))
						{
							foreach($staffs as $staff)
							{
								$result['staff'][$staff->StaffID] = $staff;
							}
						}
					}
				}
			}
		}	
		return $this->response->withType('application/json')->withStringBody(json_encode($result));
	}

    public function location($lat, $long)
    {
        $this->set('lat', $lat);
        $this->set('long', $long);
    }

    //on calendar click on event
    public function getType()
    {
        $data = $this->TBLMRepType->find()->where(['FlagDelete' => 0]);
        $result['data'] = $data;
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function getCustomer()
    {
        $areaid = $this->getRequest()->getData('AreaID');
        $customer = $this->TBLMCustomer->find()->where(['AreaID' => $areaid, 'FlagDelete' => 0])->order(['CustomerID' => 'ASC']);
        return $this->response->withType('application/json')->withStringBody(json_encode($customer));
    }

    public static function savePhoto($img, $staffid, $type, $timecardid)
    {
        $result = [];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        // make path
        $path = WWW_ROOT . 'FaceImage/' . $staffid;
        if (!file_exists($path)) {
            mkdir($path);
        }
        // filename
        $filename = $staffid . date('YmdHi') . $type . '.jpg'; #TODO: validate check in/out
        $file = $path . "/" . $filename;
        $success = file_put_contents($file, $data);

        $filename = self::__resize_image($file, $path . "/", 640, 480, $filename);

        if ($success) {
            $result['success'] = 1;
            $result['srcfile'] = 'FaceImage/' . $staffid . "/" . $filename;
            $result['filename'] = $filename;

            // insert DB Folder
            // check if not have then insert
            $folderid = 0;
            $existFolder = TableRegistry::getTableLocator()->get('TBLTFolder')->find()
                ->where([
                    'Name' => $staffid,
                    'Type' => 'Staff',
                    'ParentFolder IS NULL'
                ])
                ->first();
            if ($existFolder) {
                $folderid = $existFolder->ID;
            } else {
                $folderTable = TableRegistry::getTableLocator()->get('TBLTFolder');
                $folder = $folderTable->newEntity();
                $folder->Name = $staffid;
                $folder->Type = "Staff";
                $folder->Created_at = date('Y-m-d H:i:s');
                $savedFolder = $folderTable->save($folder);

                $folderid = $savedFolder->ID;
            }
            // save to DB FaceImage
            $faceImageTable = TableRegistry::getTableLocator()->get('TBLTFaceImage');
            $faceImage = $faceImageTable->newEntity();
            $faceImage->Name = $filename;
            $faceImage->Source = 'FaceImage/' . $staffid . "/";
            $faceImage->FolderID = $folderid;
            $faceImage->TimeCardID = $timecardid;
            $faceImage->Created_at = date('Y-m-d H:i:s');
            $faceImageTable->save($faceImage);
        }
        return $result;
    }

    public function validateCheckin()
    {
        $result = [];
        $customerID = $this->getRequest()->getData('customerID');
        $timecard = $this->TBLTTimeCard->find()
            ->where([
                'StaffID' => $this->Auth->user('StaffID'),
                'Date' => date('Y-m-d'),
            ])
            ->order(['TimeCardID' => 'DESC'])
            ->first();
        if ($timecard) {
            if ($timecard->TimeOut) {
                $result['valid'] = 1;
            } else {
                if ($timecard->CustomerID == $customerID) {
                    $result['same_area'] = 1;
                    $timeIn = $timecard->TimeIn;
                    $timeIn = $timeIn->format('H:i:s');
                    $result['timeCheckin'] = date('H:i:s', strtotime($timeIn));
                } else {
                    $result['same_area'] = 0;
                    // customer
                    $customer = $this->TBLMCustomer->find()->where(['CustomerID' => $timecard->CustomerID])->first();
                    $result['customerID'] = $customer->CustomerID;
                    $result['customerName'] = $customer->Name;
                    // get area
                    $area = $this->TBLMArea->find()->where(['AreaID' => $customer->AreaID])->first();
                    $result['areaID'] = $area->AreaID;
                    $result['areaName'] = $area->Name;
                    // time checked in
                    $timeIn = $timecard->TimeIn;
                    $timeIn = $timeIn->format('H:i:s');
                    $result['timeCheckin'] = date('H:i:s', strtotime($timeIn));
                }
            }
        } else {
            $result['valid'] = 1;
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function insertCheckin()
    {
        $img = $this->getRequest()->getData('img');
        $staffid = $this->Auth->user('StaffID');

        $result['success'] = 0;

        $timecard = $this->TBLTTimeCard->newEntity();
        $timecard->CustomerID = $this->getRequest()->getData('customerID');
        $timecard->CheckinLocation = $this->getRequest()->getData('coord');
        $timecard->Date = date('Y-m-d');
        $timecard->TimeIn = date('H:i:s');
        $timecard->StaffID = $this->Auth->user('StaffID');
        $timecard->Created_at = date('Y-m-d H:i:s');
        $saved = $this->TBLTTimeCard->save($timecard);
        if ($saved) {
            $result['success'] = 1;
            $result['timeChecked'] = $saved->TimeIn;
            $capture = MypageController::savePhoto($img, $staffid, 'IN', $saved->TimeCardID);
            if (isset($capture['success'])) {
                // src file
                $result['srcfile'] = $capture['srcfile'];
            }

            // get data staffs
            $result['dataStaff'] = $this->TBLTTimeCard->find()
                ->where([
                    "StaffID" => $staffid,
                    "CheckinLocation != 'none'",
                    'Date' => date('Y-m-d')
                ])
                ->toArray();
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function putDistance()
    {
        $distance = $this->getRequest()->getData('distance');
        $staffid = $this->Auth->user('StaffID');
        $date = date('Y-m-d');
        $result['success'] = 0;

        $eDistance = $this->TBLTDistance->find()
            ->where([
                'StaffID' => $staffid,
                'Date' => $date
            ])
            ->first();

        $eDistance = ($eDistance) ? $eDistance : $this->TBLTDistance->newEntity();

        $eDistance->StaffID = $staffid;
        $eDistance->Date = $date;
        $eDistance->Distance = $distance;
        $eDistance->DateUpdated = date('Y-m-d H:i:s');
        if ($this->TBLTDistance->save($eDistance)) {
            $result['success'] = 1;
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function validateReport()
    {
        // vars
        $customerID = $this->getRequest()->getData('customerID');
        $staffid = $this->Auth->user('StaffID');
        $today = date('y/m/d');
        // data

        $report = $this->TBLTReport->find()
            ->where([
                'StaffID' => $staffid,
                "DATE_FORMAT(DateTime, '%y/%m/%d') =" => $today,
                'CustomerID' => $customerID
            ])
            ->order(['ID' => 'DESC'])
            ->first();

        $timecard = $this->TBLTTimeCard->find()
            ->where([
                'StaffID' => $staffid,
                'Date' => $today,
                'CustomerID' => $customerID
            ])
            ->order(['TimeCardID' => 'DESC'])
            ->first();

        $result['TypeSubmit'] = '';
        $result['TypeCode'] = "-1";

        // if checkout
        if ($report) {
            // get language
            if ($report->TimeCardID == $timecard->TimeCardID) {
                $result['TypeSubmit'] = 'update';
                $result['IDReport'] = $report->ID;
                $result['IDTimeCard'] = $timecard->TimeCardID;
                $result['TypeCode'] = $report->TypeCode;
                $result['Content'] = $report->Report; //$report[$report_lang]
                $result['Check'] = $this->TBLTCheckResult->find()->where(['TimeCardID' => $report->TimeCardID]);

                // images
                $result['images'] = $this->TBLTImageReport->find()->where(['ReportID' => $report->ID]);
            } else {
                $result['TypeSubmit'] = 'new';
            }
        } else {
            if ($timecard) {
                $result['TypeSubmit'] = 'new';
            } else {
                $result['NullCheckin'] = 1;
            }
        }

        if ($result['TypeSubmit'] == 'new') {
            $result['IDReport'] = $timecard->TimeCardID;
            $result['IDTimeCard'] = $timecard->TimeCardID;

            $checkresult = $this->TBLTCheckResult->find()
                ->where(['TimeCardID' => $timecard->TimeCardID])
                ->first();
            if ($checkresult) {
                $result['TypeCode'] = $checkresult->TypeCode;
                $result['Check'] = $this->TBLTCheckResult->find()->where(['TimeCardID' => $timecard->TimeCardID]);
                $result['Content'] = "null";
            }
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function insertReport()
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $result['success'] = 0;
        // vars
        $report_id = $this->getRequest()->getData('report_id'); // Case update
        $typeSubmit = $this->getRequest()->getData('typeSubmit');
        $typeReport = $this->getRequest()->getData('typeReport');
        $customerID = $this->getRequest()->getData('customerID');
        $content = $this->getRequest()->getData('content');
        $ID = $this->getRequest()->getData('ID');
        $haveCheck = $this->getRequest()->getData('haveCheck');
        $valuesChecked = explode(",", $this->getRequest()->getData()['valuesChecked']);
        $note = $this->getRequest()->getData('note');

        $timeCardID = $this->getRequest()->getData('TimeCardID');
        if ($timeCardID == '') {
            $staffid = $this->Auth->user('StaffID');
            $today = date('y/m/d');
            $timecard = $this->TBLTTimeCard->find()
                ->where([
                    'StaffID' => $staffid,
                    'Date' => $today,
                    'CustomerID' => $customerID
                ])
                ->order(['TimeCardID' => 'DESC'])
                ->first();
            $timeCardID = $timecard->TimeCardID;
        }

        // translate
        $tr_jp = new TranslateClient(null, 'ja');
        $tr_vn = new TranslateClient(null, 'vi');
        $tr_en = new TranslateClient(null, 'en');
        $tr_jp->setUrlBase('http://translate.google.cn/translate_a/single');
        $tr_vn->setUrlBase('http://translate.google.cn/translate_a/single');
        $tr_en->setUrlBase('http://translate.google.cn/translate_a/single');

        // new or update
        if ($typeSubmit == 'new' || $ID == null) {
            // case update
            if ($ID == null) {
                $ID = $timeCardID;
            }

            $report = $this->TBLTReport->newEntity();
            $report->StaffID = $this->Auth->user('StaffID');
            $report->DateTime = date('Y-m-d H:i:s');
            $report->CustomerID = $customerID;
            $report->TypeCode = $typeReport;
            $report->TimeCardID = $ID;
            $report->Created_at = date('Y-m-d H:i:s');

            // cases
            if ($haveCheck == 1) {
                $textarea = $note;
                $checkresults = $this->TBLMRepType->getReportBy($typeReport);
                foreach ($checkresults as $item) {
                    $category = $item->TBLMRepCategory;
                    $detail = is_object($category) ? $category->TBLMRepDetail : null;

                    if ($detail) {
                        $check = $this->TBLTCheckResult->newEntity();
                        $check->TimeCardID = $timeCardID;
                        $check->TypeCode = $item->TypeCode;
                        $check->CheckID = $detail->ID;
                        $check->Result = (in_array($detail->ID, $valuesChecked)) ? 1 : 0;
                        $this->TBLTCheckResult->save($check);
                    }
                }
            } else {
                $textarea = $content;
            }

            $report->Report = $textarea;
            $report->ReportVN = $tr_vn->translate($textarea);
            $report->ReportJP = $tr_jp->translate($textarea);
            $report->ReportEN = $tr_en->translate($textarea);

            $saved_report = $this->TBLTReport->save($report);
            if ($saved_report) {
                $id_report_access = $saved_report->ID;
            }
        } // update
        else {
            $report = $this->TBLTReport->find()->where(['ID' => $ID])->first();

            //update image
            $images_uploaded = $this->TBLTImageReport->find()->where(['ReportID' => $ID]);
            $arr_uploaded = explode(",", $this->getRequest()->getData('imagesUploaded'));
            foreach ($images_uploaded as $img) {
                if ($arr_uploaded != []) {
                    if (!in_array($img->ID, $arr_uploaded)) {
                        $this->TBLTImageReport->delete($img);
                    }
                } else {
                    $this->TBLTImageReport->delete($img);
                }
            }

            $id_report_access = $ID;
            $report->TypeCode = $typeReport;
            // cases
            if ($haveCheck == 1) {
                $textarea = $note;
                $checks = $this->TBLTCheckResult->find()->where(['TimeCardID' => $timeCardID]);
                foreach ($checks as $item) {
                    $check = $this->TBLTCheckResult->find()->where(['ID' => $item->ID])->first();
                    $check->Result = (in_array($check->CheckID, $valuesChecked)) ? 1 : 0;
                    $this->TBLTCheckResult->save($check);
                }
            } else {
                $textarea = $content;
            }
            $report->Report = $textarea;
            $report->ReportVN = $tr_vn->translate($textarea);
            $report->ReportJP = $tr_jp->translate($textarea);
            $report->ReportEN = $tr_en->translate($textarea);

            $this->TBLTReport->save($report);
        }

        if ($id_report_access && $this->getRequest()->getData()['files'] != 'null') {
            $result['uploaded'] = 0;
            $files = $this->getRequest()->getData()['files'];
            $target_dir = WWW_ROOT . "ImageReport/ID_" . $id_report_access;

            if (!file_exists($target_dir)) {
                mkdir($target_dir);
            }
            foreach ($files as $index => $item) {
                $ext = substr(strtolower(strrchr($item['name'], '.')), 1); //get the extension
                $arr_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'tiff', 'bmp']; //set allowed extensions
                $setNewFileName = basename($this->Auth->user('StaffID') . "_" . str_replace('.' . $ext, '', $item["name"])) . "_" . rand(000000, 999999) . "." . $ext;
                $setNewFileName = str_replace(' ', '', $setNewFileName);
                if (in_array($ext, $arr_ext)) {
                    try {
                        if (move_uploaded_file($item['tmp_name'], $target_dir . "/tmp_" . $setNewFileName)) {
                            // resize image
                            $setNewFileName = self::__resize_image($target_dir . "/tmp_" . $setNewFileName, $target_dir . "/", 1024, 768, false, 50);

                            $image = $this->TBLTImageReport->newEntity();
                            $image->ReportID = $id_report_access;
                            $image->ImageName = $setNewFileName;
                            $image->DateCreated = date('Y-m-d H:i:s');

                            $this->TBLTImageReport->save($image);
                        }
                    } catch (\Exception $e) {
                        print($e);
                    }
                }
            }

            $result['uploaded'] = 1;
        }

        $result['success'] = 1;

        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    /**
     * Resize image given a height and width and return raw image data.
     *
     * Note : You can add more supported image formats adding more parameters to the switch statement.
     *
     * @param string $img filepath
     * @param $dst
     * @param $w
     * @param $h
     * @param bool $fileName
     * @param int $quality
     * @return string
     * @throws \Gumlet\ImageResizeException
     */
    private static function __resize_image($img, $dst, $w, $h, $fileName = false, $quality = 70)
    {
        list($width, $height) = getimagesize($img);
        $fileName = $fileName ? $fileName : "w" . $width . "h" . $height . "d" . date("ymdhis") . ".jpg";
        if ($width > $height) {
            // lanscape
            $image = new ImageResize($img);
            $image->resizeToBestFit($w, $h);
            $image->save($dst . $fileName, IMAGETYPE_JPEG, $quality);
        } else {
            // postrait
            $image = new ImageResize($img);
            $image->resizeToBestFit($h, $w);
            $image->save($dst . $fileName, IMAGETYPE_JPEG, $quality);
        }
        return $fileName;
    }

    public function validateCheckout()
    {
        $result = [];
        $customerID = $this->getRequest()->getData('customerID');
        $timecard = $this->TBLTTimeCard->find()
            ->where([
                'StaffID' => $this->Auth->user('StaffID'),
                'Date' => date('Y-m-d'),
            ])
            ->order(['TimeCardID' => 'DESC'])
            ->first();

        if ($timecard) {
            if ($timecard->TimeOut) {
                if ($customerID == $timecard->CustomerID) {
                    // alert you checked out at ...
                    $result['same_area'] = 1;
                    $timeOut = $timecard->TimeOut;
                    $timeOut = $timeOut->format('H:i:s');
                    $result['timeCheckout'] = date('H:i:s', strtotime($timeOut));
                } else {
                    // alert you not checked in at here
                    $result['same_area'] = 0;
                }
            } else {
                // same area
                if ($customerID == $timecard->CustomerID) {
                    // check have report or not
                    $report = $this->TBLTReport->find()->where(['TimeCardID' => $timecard->TimeCardID])->first();
                    if ($report) {
                        $result['valid'] = 1;
                        $result['timecardIDCheckout'] = $timecard->TimeCardID;
                    } else {
                        $result['not_reported'] = 1;
                    }
                } else { //difference area
                    $result['same_area'] = 0;
                    $result['not_timeout'] = 1;
                }
            }
        } else {
            $result['same_area'] = 0;
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function insertCheckout()
    {
        $img = $this->getRequest()->getData('img');
        $staffid = $this->Auth->user('StaffID');

        $result['success'] = 0;

        // insert DB
        $timecard_id = $this->getRequest()->getData('timecardID');
        $timecard = $this->TBLTTimeCard->find()->where(['TimeCardID' => $timecard_id])->first();
        $timecard->TimeOut = date('H:i:s');
        $total_time = abs(strtotime(Time::now()) - strtotime($timecard->TimeIn));
        $total_time = number_format($total_time / 3600, 2);
        $timecard->TotalTime = $total_time;
        $timecard->CheckoutLocation = $this->getRequest()->getData('coord');
        $timecard->Updated_at = date('Y-m-d H:i:s');
        if ($this->TBLTTimeCard->save($timecard)) {
            $result['success'] = 1;
            $result['timeChecked'] = $timecard->TimeOut;

            $capture = MypageController::savePhoto($img, $staffid, 'OUT', $timecard_id);

            if (isset($capture['success'])) {
                // src file
                $result['srcfile'] = $capture['srcfile'];
            }
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function checkTimecardOfCustomer()
    {
        $result = [];
        $staffid = $this->Auth->user('StaffID');
        $id = $this->getRequest()->getData('customerID');
        $timecard = $this->TBLTTimeCard->find()
            ->where(['CustomerID' => $id, 'StaffID' => $staffid, 'Date' => date('Y-m-d')])
            ->order(['TimeCardID' => 'DESC'])
            ->first();

        if ($timecard) {
            // declare
            $result['timeCheckin'] = '';
            $result['timeCheckout'] = '';
            $result['contentReport'] = '';

            $result['timecard'] = $timecard->toArray();
            // timein
            $timeIn = $timecard->TimeIn;
            $timeIn = $timeIn->format('H:i:s');
            $result['timeCheckin'] = date('H:i:s', strtotime($timeIn));
            // timeout
            if ($timecard->TimeOut) {
                $timeOut = $timecard->TimeOut;
                $timeOut = $timeOut->format('H:i:s');
                $result['timeCheckout'] = date('H:i:s', strtotime($timeOut));
            }
            // content report
            $report = $this->TBLTReport->find()->where(['TimeCardID' => $timecard->TimeCardID])->first();
            if ($report) {
                $result['contentReport'] = $report->Report;
            }
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function getArea()
    {
        $result['areas'] = $this->TBLMArea->find()->order('AreaID', 'ASC')->toArray();
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    /**
     * store checked click on Poopver Report
     * @return \Cake\Http\Response
     */
    public function queryCheck()
    {
        $result['success'] = 0;
        $timecard = $this->getRequest()->getData('timecard');
        $type = $this->getRequest()->getData('type');
        $id = $this->getRequest()->getData('id');
        $checked = $this->getRequest()->getData('checked');

        $check = $this->TBLTCheckResult->find()
            ->where([
                'TimeCardID' => $timecard,
                'CheckID' => $id
            ])
            ->first();
        if ($check) {
            $check->Result = ($checked == 'true') ? 1 : 0;
        } else {
            $check = $this->TBLTCheckResult->newEntity();
            $check->TimeCardID = $timecard;
            $check->TypeCode = $type;
            $check->CheckID = $id;
            $check->Result = ($checked == 'true') ? 1 : 0;
        }
        $this->TBLTCheckResult->save($check);

        $result['success'] = 1;
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function getImageReport()
    {
        $id = $this->getRequest()->getData('ReportID');
        $images = $this->TBLTImageReport->find()->where(['ReportID' => $id]);
        $response = [
            'success' => 1,
            'images' => $images
        ];
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($response));
    }

    //update report type
    public function getFormReports()
    {
        $request = $this->getRequest();
        $params = $request->getData();
        $session = $request->getSession();

        $typeCheck = 0;
        $sortedData = array();
        $submited = null;

        if (isset($params['report_id'])) { //on calendar page
            $id_report = $params['report_id'];
            $id_timecard = $params['id_timecard'];
            $report = $this->TBLTReport->findById($id_report)->first();
            $check = $this->TBLTCheckResult->find()
                ->where([
                    'TimeCardID' => $id_timecard
                ]);

            $submited = [
                'Content' => $report->Report,
                'Check' => $check,
            ];
            //$TypeCode = @$params['TypeCode'];
            $TypeCode = $report->TypeCode;
        } else {
            $TypeCode = $params['TypeCode'];
        }

        $language = $session->read('Config.language');
        $idxLanguage = Constants::$languages['report_idx'][$language];
        $checks = $this->TBLMRepType->getReportBy($TypeCode);
        if ($checks) {
            foreach ($checks as $check) {
                //$categories = $check->TBLMRepCategory;
                $categories = $this->TBLMRepCategory->getCategories(['TypeCode' => $check->TypeCode], ['CatSortNumber' => 'ASC']);

                foreach ($categories as $category) {
                    //$details = $category->TBLMRepDetail;
                    $type_cat = $category->TypeCat;
                    $idCat = $category->ID;
                    $details = $this->TBLMRepDetail->getReports(['CatCode' => $category->CatCode], ['DeSortNumber' => 'ASC']);
                    $col = "CatName{$idxLanguage}";
                    $Category = $category->$col;
                    foreach ($details as $detail) {
                        $col = "DeName{$idxLanguage}";
                        $CheckPoint = $detail->$col;
                        $sortedData[$Category][] = [
                            'Category' => $Category,
                            'TypeCat' => $type_cat,
                            'CheckCode' => $detail->DetailCode,
                            'CheckID' => $detail->ID,
                            'CheckPoint' => $CheckPoint,
                            'TypeCode' => $check->TypeCode,
                            'idCat' => $idCat
                        ];
                    }
                }
            }

            if (empty($sortedData) === false) {
                $typeCheck = 1;
            }
        }

        $response = [
            'typeCheck' => $typeCheck,
            'data' => $sortedData,
            'submited' => $submited,
        ];
        return $this->response->withType('application/json')->withStringBody(json_encode($response));
    }
}

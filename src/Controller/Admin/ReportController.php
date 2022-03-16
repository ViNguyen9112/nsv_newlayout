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

namespace App\Controller\Admin;

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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ReportController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TBLMStaff');
        $this->loadModel('TBLTTimeCard');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->viewBuilder()->setLayout('admin');
    }

    /**
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index()
    {
        if ($this->getRequest()->is('post')) {
            return $this->output();
        }
    }

    /**
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function output()
    {
        $target = $this->getRequest()->getData('target');
        //load template file
        $filename = \Constants::$export_file['idx'][$target];
        $template = CST_EXPORT_TEMPLATE_PATH.$filename;

        $fun = "output{$target}";
        $spreadsheet = $this->{$fun}($template);

        $download_file = \Constants::$export_file['file'][$target];

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition:attachment;filename={$download_file}");
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');exit();
    }

    /**
     * 集計管理.xls
     * @param $template
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function output1($template) {

        $params = $this->request->getData();

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($template);
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);

        $sheet = $spreadsheet->getActiveSheet();

        $conditions["FlagDelete ="] = 0;
        $positions = \Constants::$positions;
        $supper_admin = implode("','", $positions['supper_admin']);
        $admin = implode("','", $positions['admin']);
        $supper_leader = implode("','", $positions['supper_leader']);
        $order = [
            "CASE 
                WHEN TBLMStaff.Position IN ('{$supper_admin}') THEN 1
                WHEN TBLMStaff.Position IN ('{$admin}') THEN 2
                WHEN TBLMStaff.Position IN ('{$supper_leader}') THEN 3
                ELSE 4 END
            ",
            'TBLMStaff.StaffID'
        ];
        $staffs = $this->TBLMStaff->getStaffs($conditions, $order);

        //add number of row
        $sheet->insertNewRowBefore(7, $staffs->count());

        //add number of column
        $date1 = new \DateTime($params['datepicker_date']);
        $date = $from = $date1->format('Y-m-d');
        $date2 = new \DateTime($params['datepicker_date_to']);
        $to = $date2->format('Y-m-d');
        $diffs = $date2->diff($date1)->format("%a");
        $pNumCols = $diffs * 2;
        if ($diffs > 1) $sheet->insertNewColumnBefore("H", $pNumCols);

        //style day
        $idxColumn = 8;
        $day = "F";
        $night = "G";

        // values to set
        $dateFormat = date("m/d", strtotime($date));
        $dayJaFormat = $this->Date->makeFormat($date, 'w');
        $dayVnFormat = $this->Date->makeFormat($date, 'w', true);
        $sheet->setCellValue("{$day}4", $dateFormat);
        $sheet->setCellValue("{$day}5", $dayVnFormat);
        $sheet->setCellValue("{$night}5", $dayJaFormat);

        //add 1 day
        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));

        for ($idx = 1; $idx <= $pNumCols; $idx++) {
            // values to set
            $dateFormat = date("m/d", strtotime($date));
            $dayJaFormat = $this->Date->makeFormat($date, 'w');
            $dayVnFormat = $this->Date->makeFormat($date, 'w', true);

            $txtColumn = Coordinate::stringFromColumnIndex($idxColumn);

            if ($idx%2 == 0) {//night cell
                $sheet->duplicateStyle(
                    $sheet->getStyle("{$night}2"),
                    "{$txtColumn}2"
                );
                $sheet->setCellValue("{$txtColumn}2", $sheet->getCell("{$night}2")->getValue());
                $sheet->duplicateStyle(
                    $sheet->getStyle("{$night}3"),
                    "{$txtColumn}3"
                );
                $sheet->setCellValue("{$txtColumn}3", $sheet->getCell("{$night}3")->getValue());
                $sheet->setCellValue("{$txtColumn}5", $dayJaFormat);

                if ($dayJaFormat == '日') {
                    $sheet->getStyle("{$txtColumn}5")->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                }

                //add 1 day
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
            else {// day cell
                $sheet->duplicateStyle(
                    $sheet->getStyle("{$day}2"),
                    "{$txtColumn}2"
                );
                $sheet->setCellValue("{$txtColumn}2", $sheet->getCell("{$day}2")->getValue());
                $sheet->duplicateStyle(
                    $sheet->getStyle("{$day}3"),
                    "{$txtColumn}3"
                );
                $sheet->setCellValue("{$txtColumn}3", $sheet->getCell("{$day}3")->getValue());
                $sheet->setCellValue("{$txtColumn}4", $dateFormat);
                $sheet->setCellValue("{$txtColumn}5", $dayVnFormat);
            }
            $idxColumn++;

            //set width
            $spreadsheet->getActiveSheet()->getColumnDimension($txtColumn)->setWidth(10.5);
        }

        $conditions = [
            'TBLTTimeCard.Date >= ' => $from,
            'TBLTTimeCard.Date <= ' => $to,
        ];
        $order = [
            'TBLTTimeCard.Date',
        ];
        $entities = $this->TBLTTimeCard
            ->find()
            ->contain(['TBLMStaff', 'TBLMCustomer', 'TBLMCustomer.TBLMArea'])
            ->where($conditions)
            ->order($order);

        $works = [];
        foreach ($entities as $each) {
            $date = $this->Date->makeFormat($each->Date, 'Y-m-d');
            $checkIn = $this->Date->makeFormat($each->TimeIn, "Hs");

            //detect time worked
            $chk_day = $chk_night = "";

            if ($checkIn >= "0600" && $checkIn < "1801") {
                $chk_day = 1;
            }
            else {
                $chk_night = 1;
            }

            if ($chk_day && isset($works[$each->StaffID][$date]['chk_day']) === false) {
                $works[$each->StaffID][$date]['chk_day'] = 0;
            }

            if ($chk_day) {
                $works[$each->StaffID][$date]['chk_day'] += $chk_day;
            }

            if ($chk_night && isset($works[$each->StaffID][$date]['chk_night']) === false) {
                $works[$each->StaffID][$date]['chk_night'] = 0;
            }

            if ($chk_night) {
                $works[$each->StaffID][$date]['chk_night'] += $chk_night;
            }

            $works[$each->StaffID][$date]['Region'] = $each->TBLMCustomer ? $each->TBLMCustomer->TBLMArea->Region : "";
        }

        $row = 5;
        $no = 0;
        $columnHP = $columnHN = $columnHCM = $columnDN = array();
        foreach ($staffs as $staff) {
            $row++;
            $no++;
            $sheet->setCellValue("A{$row}", $no);
            $sheet->setCellValue("B{$row}", $staff->Position);
            $sheet->setCellValue("C{$row}", $staff->Name);
            $sheet->setCellValue("D{$row}", $staff->StaffID);
            $sheet->setCellValue("E{$row}", $staff->Region);

            $idxColumn = 6;
            $row_day = $row_night = $total_row = array();
            $begin = $from;
            for ($idx = 0; $idx <= $diffs; $idx++) {
                $txtColumn = Coordinate::stringFromColumnIndex($idxColumn);
                $txtNextColumn = Coordinate::stringFromColumnIndex($idxColumn+1);

                $chk_day = $chk_night = "";
                $tmp = "column{$staff->Region}";
                if (isset($works[$staff->StaffID][$begin]['chk_day']) && isset($$tmp)) {
                    $chk_day = $works[$staff->StaffID][$begin]['chk_day'];
                }
                if (isset($$tmp)) {
                    array_push($$tmp, "{$txtColumn}{$row}");
                }
                array_push($row_day, "{$txtColumn}{$row}");
                if (isset($works[$staff->StaffID][$begin]['chk_night']) && isset($$tmp)) {
                    $chk_night = $works[$staff->StaffID][$begin]['chk_night'];
                }
                if (isset($$tmp)) {
                    array_push($$tmp, "{$txtNextColumn}{$row}");
                }
                array_push($row_night, "{$txtNextColumn}{$row}");

                $sheet->setCellValue("{$txtColumn}{$row}", $chk_day);
                $sheet->setCellValue("{$txtNextColumn}{$row}", $chk_night);
                $idxColumn+=2;
                $begin = date("Y-m-d", strtotime("+1 day", strtotime($begin)));
            }
            $idxColumn--;

            //total day
            $idxColumn++;
            $txtColumn = Coordinate::stringFromColumnIndex($idxColumn);
            $row_day = implode(",", $row_day);
            $sheet->setCellValue("{$txtColumn}{$row}", "=SUM({$row_day})");
            array_push($total_row, "{$txtColumn}{$row}");

            //total night
            $idxColumn++;
            $txtColumn = Coordinate::stringFromColumnIndex($idxColumn);
            $row_night = implode(",", $row_night);
            $sheet->setCellValue("{$txtColumn}{$row}", "=SUM({$row_night})");
            array_push($total_row, "{$txtColumn}{$row}");

            //total
            $idxColumn++;
            $txtColumn = Coordinate::stringFromColumnIndex($idxColumn);
            $total_row = implode(",", $total_row);
            $sheet->setCellValue("{$txtColumn}{$row}", "=SUM({$total_row})");
        }

        $idxColumn = 6;
        $end_row = $row;
        $row+=2;
        for ($idx = 0; $idx <= $diffs; $idx++) {
            $txtColumn = Coordinate::stringFromColumnIndex($idxColumn);
            $txtNextColumn = Coordinate::stringFromColumnIndex($idxColumn + 1);

            $sumDay = "{$txtColumn}6:{$txtColumn}$end_row";
            $sumNight = "{$txtNextColumn}6:{$txtNextColumn}$end_row";
            $sheet->setCellValue("{$txtColumn}{$row}", "=SUM({$sumDay})");
            $sheet->setCellValue("{$txtNextColumn}{$row}", "=SUM({$sumNight})");
            $idxColumn+=2;
        }

        $idxColumn = 6;
        $end_row = $row + 1;
        for ($idx = 0; $idx <= $diffs; $idx++) {
            $txtColumn = Coordinate::stringFromColumnIndex($idxColumn);
            $txtNextColumn = Coordinate::stringFromColumnIndex($idxColumn + 1);
            $row = $end_row;

            $row++;
            if (empty($columnHP) === false) {
                $row_day = $this->__getTarget($columnHP, $txtColumn);
                if (empty($row_day) === false) {
                    $row_day = implode(",", $row_day);
                    $sheet->setCellValue("{$txtColumn}{$row}", "=SUM({$row_day})");
                }
                $row_night = $this->__getTarget($columnHP, $txtNextColumn);
                if (empty($row_night) === false) {
                    $row_night = implode(",", $row_night);
                    $sheet->setCellValue("{$txtNextColumn}{$row}", "=SUM({$row_night})");
                }
            }

            $row++;
            if (empty($columnHN) === false) {
                $row_day = $this->__getTarget($columnHN, $txtColumn);
                if (empty($row_day) === false) {
                    $row_day = implode(",", $row_day);
                    $sheet->setCellValue("{$txtColumn}{$row}", "=SUM({$row_day})");
                }
                $row_night = $this->__getTarget($columnHN, $txtNextColumn);
                if (empty($row_night) === false) {
                    $row_night = implode(",", $row_night);
                    $sheet->setCellValue("{$txtNextColumn}{$row}", "=SUM({$row_night})");
                }
            }

            $row++;
            if (empty($columnHCM) === false) {
                $row_day = $this->__getTarget($columnHCM, $txtColumn);
                if (empty($row_day) === false) {
                    $row_day = implode(",", $row_day);
                    $sheet->setCellValue("{$txtColumn}{$row}", "=SUM({$row_day})");
                }
                $row_night = $this->__getTarget($columnHCM, $txtNextColumn);
                if (empty($row_night) === false) {
                    $row_night = implode(",", $row_night);
                    $sheet->setCellValue("{$txtNextColumn}{$row}", "=SUM({$row_night})");
                }
            }

            $row++;
            if (empty($columnDN) === false) {
                $row_day = $this->__getTarget($columnDN, $txtColumn);
                if (empty($row_day) === false) {
                    $row_day = implode(",", $row_day);
                    $sheet->setCellValue("{$txtColumn}{$row}", "=SUM({$row_day})");
                }
                $row_night = $this->__getTarget($columnDN, $txtNextColumn);
                if (empty($row_night) === false) {
                    $row_night = implode(",", $row_night);
                    $sheet->setCellValue("{$txtNextColumn}{$row}", "=SUM({$row_night})");
                }
            }
            $idxColumn+=2;
        }

        return $spreadsheet;
    }

    /**
     * @param $columns
     * @param $txtColumn
     * @return array
     */
    private function __getTarget($columns, $txtColumn) {
        $response = array();
        foreach ($columns as $column) {
            $word = preg_replace('/[0-9]+/', '', $column);
            if ($txtColumn == $word) {
                array_push($response, $column);
            }
        }
        return $response;
    }

    /**
     * 2021.- COVID BAO CAO (rev.1).xls
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function output2($template) {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($template);
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        return $spreadsheet;
    }
}

<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Error\Debugger;
use Cake\I18n\FrozenTime;
use Cake\I18n\FrozenDate;
use Cake\ORM\TableRegistry;
/**
 * Common component
 */
class CommonComponent extends Component
{
    /**
     * @param $position
     * @return bool
     */
    public function isSupper($position) {
        $positions = array_map('strtolower', \Constants::$positions['supper_admin']);
        return in_array(strtolower($position), $positions) !== false;
    }

    /**
     * @param $position
     * @return bool
     */
    public function isAdmin($position) {
        $positions = array_map('strtolower', \Constants::$positions['admin']);
        return in_array(strtolower($position), $positions) !== false;
    }

    /**
     * @param $position
     * @return bool
     */
    public function isSupperLeader($position) {
        $positions = array_map('strtolower', \Constants::$positions['supper_leader']);
        return in_array(strtolower($position), $positions) !== false;
    }

    /**
     * @param $position
     * @return bool
     */
    public function isLeader($position) {
        $positions = array_map('strtolower', \Constants::$positions['leader']);
        return in_array(strtolower($position), $positions) !== false;
    }

    /**
     * @param $target
     * @return bool
     */
    public function isNew($target) {
        if (empty($target)) return 0;
        $n = date('Y-m-d');
        $created = date('Y-m-d', strtotime($target. ' + 30 days'));
        return $created >= $n ? 1 : 0;
    }

    /**
     * @param $target
     * @return array
     */
    public function chkAreas() {
        $controller = $this->getController();
        $user = $controller->TBLMStaff->find()->where(['StaffID' => $controller->Auth->user('StaffID')])->first();
        if (empty($user)) return [];
        if ($this->isSupperLeader($user->Position)) {
            $arr_areas = $controller->TBLMAreaStaff->getAreaManager($user->StaffID);
            if (!empty($arr_areas)) {
                return $arr_areas;
            }
        }
        else if ($this->isAdmin($user->Position)) {
			if($user->StaffID === 'A0002')
			{
				$arr_areas = $controller->TBLMArea->getAreaIDsByRegionNew($user->Region);
			}
            else
			{
				$arr_areas = $controller->TBLMArea->getAreaIDsByRegion($user->Region);
			}
            if (!empty($arr_areas)) {
                return $arr_areas->toArray();
            }
        }
        return [];
    }

    /**
     * @param $Bytes
     * @return string
     */
    public function dataSize($Bytes)
    {
        return round($Bytes / 1024 / 1024 / 1024, 1) ;
    }

    /**
     * @param $bytes
     * @param int $precision
     * @return string
     */
    function formatBytes($bytes, $precision = 2) {
        if ($bytes > pow(1024,3)) return round($bytes / pow(1024,3), $precision)."GB";
        else if ($bytes > pow(1024,2)) return round($bytes / pow(1024,2), $precision)."MB";
        else if ($bytes > 1024) return round($bytes / 1024, $precision)."KB";
        else return ($bytes)."B";
    }

    /**
     * @param $path
     * @param $days
     * @param bool $unlink
     * @return int
     */
    public function deleteFiles($path, $days, $unlink = false) {
        $total = 0;
        if (file_exists($path)) {

            // 指定の拡張子のファイルを取得
            $log_files  = scandir($path);

            // この日付以前のファイルを削除する(これは2ヶ月指定)
            $target_day = strtotime(date('Ymd235929') . "-$days day");

            foreach ($log_files as $_log_files) {
                if (in_array($_log_files, array(".", "..")) === false) {
                    // ファイルの最終更新日を取得
                    $m_date = filemtime($path . "/" . $_log_files);

                    // 最終更新日が指定日より前であれば削除
                    if (strtotime(date('YmdHis', $m_date)) <= $target_day) {
                        if ($unlink) {
                            unlink($path . "/" . $_log_files);
                        }
                        $total++;
                    }
                }
            }
        }
        return $total;
    }

    /**
     * @param $path
     * @param $from
     * @param $to
     * @param bool $zip
     * @param bool $unlink
     * @return array|int
     */
    public function backupFiles($path, $from, $to, $unlink = false) {
        $total = 0;
        $size = 0;
        $files = [];
        if (file_exists($path)) {

            // 指定の拡張子のファイルを取得
            $log_files  = scandir($path);

            // この日付以前のファイルを削除する(これは2ヶ月指定)
            $target_from = strtotime(date('Ymd000000') . "-$from day");
            $target_to = strtotime(date('Ymd235929') . "-$to day");

            foreach ($log_files as $_log_files) {
                if (in_array($_log_files, array(".", "..")) === false) {
                    // ファイルの最終更新日を取得
                    $m_date = filemtime($path . "/" . $_log_files);
                    $m_time = strtotime(date('YmdHis', $m_date));

                    // 最終更新日が指定日より前であれば削除
                    if ($target_from <= $m_time && $m_time <= $target_to) {
                        $file = $path . "/" . $_log_files;
                        $size += filesize($file);
                        if ($unlink) {
                            unlink($file);
                        }
                        $files[] = $file;
                        $total++;
                    }
                }
            }
        }
        return [
            'files' => $files,
            'total' => $total,
            'size' => $size,
        ];
    }

    /**
     * @param bool $return
     * @param string $upload_name
     * @return array
     */
    public function upload($return = false, $upload_name = 'upload')
    {
        $rest = [
            'code' => 1
        ];

        $file = $_FILES;
        $is_file = isset($file[$upload_name]);
        $is_save = $mime_type = $filename = $org_filename = '';

        if ($is_file) {
            $is_save = $this->upload_by_file($file, $mime_type, $filename, $org_filename);
        }

        $is_save = $this->build_path($is_save);

        $tmp_path = $this->build_path(TMP . CST_UPLOAD_FILE_PATH, true);

        $file_path = str_replace($tmp_path, "", $is_save);

        if (false === empty($is_save)) {
            $rest = [
                'code' => 0,
                'file' => $file_path,
                'org_filename' => $org_filename,
                'filename' => $filename,
                'filetype' => $mime_type,
            ];
        }

        if ($return) {
            return $rest;
        } else {
            echo json_encode($rest);exit;
        }
    }

    /**
     * @param $path
     * @param bool $rtrim
     * @return mixed|string
     */
    public function build_path($path, $rtrim = false)
    {
        $path = str_replace('\\', '/', $path);
        if ($rtrim) {
            return rtrim(str_replace(['\\\\', '//'], '/', $path), "/");
        }
        return str_replace(['\\\\', '//'], '/', $path);
    }

    /**
     * @param $dir
     * @param bool $y
     * @return bool
     */
    public function createDir($dir, $y = false)
    {
        if (trim($dir) == "") {
            return false;
        }
        $dir_year = substr($dir, 0, -5);
        if (is_dir($dir_year) == false && $y) {
            $created_folder_year = mkdir($dir_year, 0777, true);
            if ($created_folder_year) {
                if (is_dir($dir) == false) {
                    return mkdir($dir, 0777, true);
                }
            }
        } else {
            if (is_dir($dir) == false) {
                $oldmask = umask(000);
                $rst = mkdir($dir, 0777, true);
                umask($oldmask);
                return $rst;
            }
        }
        return false;
    }

    /**
     * @param $file
     * @param $mime_type
     * @param $filename
     * @param $org_filename
     * @return bool|string
     */
    private function upload_by_file($file, &$mime_type, &$filename, &$org_filename)
    {
        $tempFile = $file['upload']['tmp_name'];
        $nameFile = $file['upload']['name'];

        $ext = strtolower(strrpos($nameFile, '.') ? substr($nameFile, strrpos($nameFile, '.')) : '');

        $filename = date('YmdHis') . $ext;
        $org_filename = $nameFile;

        $des_file = $this->move_file($tempFile, $filename);

        if ($ext) {
            $mime_type = mime_content_type($des_file);
        }
        return $des_file;
    }

    /**
     * @param $file
     * @param $filename
     * @return bool|string
     */
    private function move_file($file, $filename)
    {
        try {
            $des_dir = rtrim(rtrim(TMP, DS) . CST_UPLOAD_FILE_PATH, DS) . DS . date('y') . DS . date('md') . DS;
            $this->createDir($des_dir);

            $des_file = $des_dir . $filename;

            if ($file) {
                move_uploaded_file($file, $des_file);
            }

            return $des_file;
        } catch (\Exception $e) {
            Debugger::log($e->getMessage(), 'error');
            return false;
        }
    }
    public function getMaxSort($tableName, $colName, $groupName, $groupValue){
       $table =  TableRegistry::getTableLocator()->get($tableName);
       $conds = [
            $groupName => $groupValue
       ];
       $MaxVal = $table->find()
       ->select([$colName])
       ->where([$conds])
       ->order([$colName => 'DESC'])
       ->first();
        if(empty($MaxVal)){
            $MaxVal = 0;
        }else{
            $MaxVal = $MaxVal->$colName;
        }
       
       return $MaxVal;
    }
}

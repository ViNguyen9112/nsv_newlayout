<?php
/**
 * Created by PhpStorm.
 * User: Thuy
 * Date: 2017/01/04
 * Time: 10:29
 */

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\I18n\FrozenTime;
use Cake\I18n\FrozenDate;
use Cake\I18n\Time;

class DateHelper extends Helper
{

    var $helpers = ['Common'];

    public function makeFormat($in, $format = 'Y年m月d日')
    {
        if ($in instanceof FrozenTime || $in instanceof FrozenDate || $in instanceof Time) {
            $in = $in->format('Y-m-d H:i:s');
        }
        if (empty($in)) return '';
        $days = ['日', '月', '火', '水', '木', '金', '土'];
        if (strpos($format, 'w') !== false) {
            $w = $days[date('w', strtotime($in))];
            $format = str_replace('w', $w, $format);
        }

        return date($format, strtotime($in));
    }
}

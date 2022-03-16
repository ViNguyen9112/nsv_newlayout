<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\I18n\FrozenTime;
use Cake\I18n\FrozenDate;

/**
 * Date component
 */
class DateComponent extends Component
{

    /**
     * Format the input date by the input format
     * @param string|FrozenTime|FrozenDate $in : input date
     * @param string $format
     * @param bool $eng
     * @return bool|string
     */
    public function makeFormat($in, $format = 'Y年m月d日', $eng = false)
    {
        if ($in instanceof FrozenTime || $in instanceof FrozenDate) {
            $in = $in->format('Y-m-d H:i:s');
        }
        if (empty($in)) {
            return '';
        }
        $days = ['日', '月', '火', '水', '木', '金', '土'];
        if (strpos($format, 'w') !== false) {
            $w = $days[date('w', strtotime($in))];
            $format = str_replace('w', $w, $format);
        }
        $response = date($format, strtotime($in));
        if ($eng) {
            $vn_days = ['日' => 'CN', '月' => 'TH2', '火' => 'TH3', '水' => 'TH4', '木' => 'TH5', '金' => 'TH6', '土' => 'TH7'];
            $response = $vn_days[$response];
        }
        return $eng ? $response : $response;
    }
}

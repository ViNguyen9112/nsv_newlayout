<?php
define("PAGE_LIMIT", 100);
define("PAGE_MAX_LIMIT",500);
// PAGE LIMIT FOR CUSTOMER, CHECK STAFF
define("PAGE_LIMIT_SPECIFIC",200);
define("PAGE_LIMIT_EXTENT", 100);
define("PAGE_LIMIT_FULL", 9999);
define("SYSTEM_TITLE", "Timecard Management System");
define("ADMIN_TITLE", "TMS Admin");
define("ADMIN_SYSTEM_TITLE", "TMS System Ver 1.2");
define('CST_EXPORT_TEMPLATE_PATH', WWW_ROOT . 'template/');
define('CST_UPLOAD_FILE_PATH', '/files/upload/');
define('CST_EXPORT_FILE', 'NSV');
define('CST_PASSWORD', 123456);
define('CST_SHOW_PASSWORD_DIALOG', 1);

define("CST_EXCEL_REPORT", 1);
define("CST_REPORT", 1);
define("CST_CONFIG", 1);
define("CST_DELETE", 1);
define("CST_BACKUP", 1);
define("CST_RESTORE", 1);

define('MAP_KEY_ENABLE', 1);

if (MAP_KEY_ENABLE) {
    define("MAP_KEY", "AIzaSyDCMrqbkNp_qg4sYqePomnUR427CltUvK4");
}
else {
    define("MAP_KEY", "");
}

class Constants
{
    /**
     * @var array
     */
    static $event_color = array(
        'Patrol' => 'patrolButton',
        'Meeting (Customer)' => 'meetingButton',
        'Desk Work' => 'deskButton',
        'In-house Meeting' => 'inhouseButton',
        'Recruitment Activities' => 'recruitmentButton',
        'Security Guard/ Post Disposition' => 'securiryButton',
        'Others' => 'otherButton'
    );

    /**
     * @var array
     */
    static $event_type = array(
        1 => 'Patrol',
        2 => 'Meeting (Customer)',
        3 => 'Desk Work',
        4 => 'In-house Meeting',
        5 => 'Recruitment Activities',
        6 => 'Security Guard/ Post Disposition',
        7 => 'Others'
    );

    /**
     * @var array
     * check position of staffs
     */
    static $positions = array(
        'supper_admin' => array(
            'Japanese Manager',
        ),
        'admin' => array(
            'HP Manager',
            'HN Manager',
            'HCM Manager',
        ),
        'supper_leader' => array(
            'Area Leader',
        ),
        'leader' => array(
            'Leader'
        )
    );

    /**
     * @var array
     * check position of staffs
     */
    static $languages = array(
        'lang' => [
            'vn_VN' => 'VietName',
            'en_US' => 'English',
            'jp_JP' => 'Japanase',
        ],
        'report_idx' => [
            'vn_VN' => 1,
            'en_US' => 2,
            'jp_JP' => 3,
        ],
        'admin_key_form' => [ //
            'col' => 3,
        ],
        'admin_report_idx' => [
            'VN' => 1,
            'EN' => 2,
            'JP' => 3,
        ]
    );

    /**
     * @var array
     * check position of staffs
     */
    static $export_file = array(
        'file' => [
            1 => "集計管理.xls",
            2 => "2021.- COVID BAO CAO (rev.1).xls",
        ],
        'idx' => [
            1 => "1.xls",
            2 => "2.xls",
        ],
    );

    /**
     * @var array
     */
    static $category_type = array(
        1 => 'Multiple choice',
        //2 => 'Type text',
        3 => 'One choice',
        //4 => 'Others'
    );
}

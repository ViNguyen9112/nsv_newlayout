<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="google" content="notranslate" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title><?=SYSTEM_TITLE?></title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo $this->Url->build('/', true); ?>img/favicon.ico">
</head>
<body style="background:#fff">
    <div id="container">
        <div id="header">
            <h1>404 - Page Not Found</h1>
        </div>
        <div id="content">
            This is not the web page you are looking for.
        </div>
    </div>
</body>
</html>

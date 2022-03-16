<?php

use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'error';

if (Configure::read('debug')) :
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'missing_action.ctp');

    $this->start('file');
?>
    <?php if (!empty($error->queryString)) : ?>
        404 NOT FOUND
    <?php endif; ?>
    <?php if (!empty($error->params)) : ?>
        404 NOT FOUND
    <?php endif; ?>
<?php endif; ?>
404 NOT FOUND

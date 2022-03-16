<?php
namespace App\Command;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;

class PasswordHelperCommand extends Command
{
    // encrypt all user emails
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Begin');

        $hasher     = new DefaultPasswordHasher();
        $staffTable = TableRegistry::getTableLocator()->get('TBLMStaff');

        $staffs     = $staffTable->find()
            ->all();
        foreach ($staffs as $staff) {
            $staffId = $staff->StaffID;
            $passwd  = $staff->Password;
            if ($passwd != '123456') {
                continue;
            }
            
            echo "Encrypt password for Staff ID " . $staffId . "\n";
            $staff->Password =  $hasher->hash($passwd);
            $staffTable->save($staff);
        }

        
        $io->out('End');
    }
}

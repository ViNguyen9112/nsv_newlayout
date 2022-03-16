<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

use App\Helper\Excel;

class MakeExcelCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        Excel::makeSalaryLevel();
        echo "done.\n";
    }
}

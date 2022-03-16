<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class TestCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $subject = "test shell";
        $content = "This is content test.";
        mail("tranquang6896@gmail.com", $subject, $content);
    }
}
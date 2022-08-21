<?php namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;

class DownCommand
{
    public function __invoke(OutputInterface $output)
    {
        $down_file = APP_ROOT . "/storage/down";
        if (file_exists($down_file)) {
            $output->writeln("Application is already down.");
        } else {
            touch($down_file);
            $output->writeln("Application is now down.");
        }
    }
}
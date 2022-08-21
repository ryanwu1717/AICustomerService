<?php namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;

class UpCommand
{
    public function __invoke(OutputInterface $output)
    {
        $down_file = APP_ROOT . "/storage/down";
        if (file_exists($down_file)) {
            unlink($down_file);
            $output->writeln("Application is now up.");
        } else {
            $output->writeln("Application is already up.");
        }
    }
}
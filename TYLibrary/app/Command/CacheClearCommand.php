<?php namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand
{
    public function __invoke(OutputInterface $output)
    {
        $storage = APP_ROOT . "/storage";
        shell_exec("ls -ad $storage/cache/* | grep -v '.gitignore' | grep -vw '.' | xargs rm -rf");
        shell_exec("ls -ad $storage/views/* | grep -v '.gitignore' | grep -vw '.' | xargs rm -rf");
        $output->writeln("Cache cleared!");
    }
}
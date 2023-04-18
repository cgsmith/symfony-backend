<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:php-cs-fixer',
    description: 'Add a short description for your command',
)]
class PhpCsFixerCommand extends Command
{
    protected function configure(): void
    {
        $this->setDescription('Runs PHP-CS-Fixer to fix coding style issues')
            ->setHelp('This command runs PHP-CS-Fixer to fix coding style issues.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        exec('php vendor/bin/php-cs-fixer fix --verbose --diff --allow-risky=yes', $outputLines, $returnCode);

        foreach ($outputLines as $line) {
            $output->writeln($line);
        }

        return $returnCode;
    }
}

<?php

namespace App\Command;

use App\Service\GithubService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-repo',
    description: 'Creates a new repository',
)]
class CreateRepoCommand extends Command
{
    public function __construct(
        private GithubService $service
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('repo', InputArgument::REQUIRED, 'Name of the repository to create')
            ->setHelp('Specify the repository name and it will be created under your access token');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $repoName = $input->getArgument('repo');

        if ($repoName) {
            $io->note(sprintf('Trying to create repo: %s', $repoName));
        }

        try {
            $repo = $this->service->create($repoName);
            $io->success('You have a new repo! Go create something amazing! '.$repo->getUrl());

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}

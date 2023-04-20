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
    name: 'app:delete-repo',
    description: 'Deletes an existing repository',
)]
class DeleteRepoCommand extends Command
{
    public function __construct(
        private GithubService $service
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('repo', InputArgument::OPTIONAL, 'Repo path to delete <owner/repo-name>');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $repoFullName = $input->getArgument('repo');

        if ($repoFullName) {
            $io->note(sprintf('You passed an argument: %s', $repoFullName));
        }

        try {
            $this->service->delete($repoFullName);
            $io->success('Your repo has been deleted!');

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }
    }
}

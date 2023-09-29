<?php

namespace App\Command;

use App\Entity\Link;
use App\Repository\LinkRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'modify-icons',
)]
class ModifyIconsCommand extends Command
{
    /**
     * @param LinkRepository $linkRepository
     */
    public function __construct(
        protected readonly LinkRepository $linkRepository,
    )
    {
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $links = $this->linkRepository->findAll();
        foreach ($links as $link) {
            $link->setIcon($this->modifyIcon($link->getIcon()));
            $this->linkRepository->save($link, true);
        }
        return Command::SUCCESS;
    }

    /**
     * @return Link[]
     */
    protected function getLinks(): array
    {
        return $this->linkRepository->findAll();
    }

    /**
     * @param string $icon
     * @return string
     */
    protected function modifyIcon(string $icon): string
    {
        return str_replace('https://v2.api.aftaa.ru', 'https://v3.api.aftaa.ru', $icon);
    }
}

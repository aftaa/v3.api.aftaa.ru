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
    name: 'modify-hrefs',
)]
class ModifyHrefsCommand extends Command
{
    public function __construct(
        protected readonly LinkRepository $linkRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        return Command::SUCCESS;
    }

    protected function getLinks()
    {
        return $this->linkRepository->findAll();
    }

    protected function modifyHref(Link &$link)
    {
        $icon = $link
        str_replace('https://v2.api.aftaa.ru', 'https://v3.api.aftaa.ru', $icon);
    }
}

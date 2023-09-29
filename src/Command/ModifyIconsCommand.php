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
final class ModifyIconsCommand extends Command
{
    final public const OLD_ICON_URL = 'https://v3.api.aftaa.ru';
    final public const NEW_ICON_URL = 'https://icons.aftaa.ru';

    /**
     * @param LinkRepository $linkRepository
     */
    public function __construct(
        private readonly LinkRepository $linkRepository,
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
    private function getLinks(): array
    {
        return $this->linkRepository->findAll();
    }

    /**
     * @param string $icon
     * @return string
     */
    private function modifyIcon(string $icon): string
    {
        return str_replace(self::OLD_ICON_URL, self::NEW_ICON_URL, $icon);
    }
}

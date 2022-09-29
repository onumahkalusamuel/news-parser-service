<?php

namespace App\Command;

use App\Message\NewsParserMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'news:start-parsing',
    description: 'News parser for initiating news parsing',
)]
class NewsParserCommand extends Command
{

    protected $bus;

    public function __construct(MessageBusInterface $bus)
    {
        parent::__construct();
        $this->bus = $bus;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // $pageId can be dynamic in case we want to process more pages, for instance
        $this->bus->dispatch(new NewsParserMessage(1));
        $this->bus->dispatch(new NewsParserMessage(2));

        $io->success('News Parser dispatched for processing...');

        return Command::SUCCESS;
    }
    
}
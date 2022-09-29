<?php

namespace App\MessageHandler;

use App\Message\NewsParserMessage;
use App\Service\NewsParserService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewsParserMessageHandler implements MessageHandlerInterface
{

    private $newsParser;

    public function __construct(NewsParserService $newsParserService)
    {
        $this->newsParser = $newsParserService;
    }

    public function __invoke(NewsParserMessage $newsParserMessage)
    {
        $this->newsParser->parseNews($newsParserMessage->getPageId());
    }
}

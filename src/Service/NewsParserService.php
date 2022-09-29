<?php

namespace App\Service;

use App\Entity\News;
use App\Repository\NewsRepository;
use DOMDocument;
use DOMXPath;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsParserService
{
    private $client;
    private $newsRepository;

    private $highLoadNewsUrl = "https://highload.today/wp-content/themes/supermc/ajax/loadarchive.php";
    private $highLoadParams = [
        'action' => 'archiveload',
        'stick' => 50,
        'page' => 1,
        'cat' => 537
    ];

    public function __construct(HttpClientInterface $client, NewsRepository $newsRepository)
    {
        $this->client = $client;
        $this->newsRepository = $newsRepository;
    }

    /**
     * Fetch and parse news feed
     * @return void
     */
    public function parseNews(int $pageId): void
    {
        
        $this->highLoadParams['page'] = $pageId;

        $response = $this->client->request(
            "POST",
            $this->highLoadNewsUrl,
            [
                'body' => $this->highLoadParams
            ]
        );

        $source = $response->getContent();

        if (empty($source)) return;

        $dom = new DOMDocument();

        @$dom->loadXML("<div>" . $source . "</div>");

        $xpath = new DOMXPath($dom);

        $items = $xpath->query("//div[contains(@class,'lenta-item')]");

        foreach ($items as $item) {

            $picture = $item->getElementsByTagName('img')[0]->getAttribute('src');
            $title = $item->getElementsByTagName('h2')[0]->nodeValue;
            $description = $item->getElementsByTagName('p')[0]->nodeValue;

            $this->saveNews($title, $description, $picture);
        }
    }

    /**
     * Save news article if it doesn't already exist in database
     * @param string $title
     * @param string $description
     * @param string $picture
     * @return void
     */
    private function saveNews(string $title, string $description, string $picture): void
    {
        if (count($news = $this->newsRepository->findBy(["title" => $title]))) {
            $news[0]->setDateModified(new \DateTime());
            $this->newsRepository->save($news[0], true);
        } else {
            $news = new News();
            $news->setTitle($title);
            $news->setDescription($description);
            $news->setPicture($picture);
            $news->setDateCreated(new \DateTime());
            $this->newsRepository->save($news, true);
        }
    }
}

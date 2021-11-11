<?php

namespace App\Service;

class QuotesJsonClient implements QuotesClientInterface
{
    private array $quotes;

    public function __construct()
    {
        $quotes = file_get_contents('/var/www/app/quotes.json');
        $this->quotes = json_decode($quotes, true)['quotes'];
    }

    /**
     * @return string[]
     */
    public function getQuotesByAuthor(string $author, int $limit): array
    {
        $author = $this->convertAuthorName($author);

        $result = [];

        foreach ($this->quotes as $item) {
            if ($item['author'] === $author) {
                $result[] = $item['quote'];
            }

            if (count($result) === $limit) {
                break;
            }
        }

        return $result;
    }

    private function convertAuthorName(string $author): string
    {
        return ucwords(str_replace('-', ' ', $author));
    }
}

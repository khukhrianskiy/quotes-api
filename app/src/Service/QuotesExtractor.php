<?php

namespace App\Service;

use App\Exception\ExceededQuotesLimitException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class QuotesExtractor
{
    public const QUOTES_MAX_RESULTS = 10;
    private const CACHE_LIFETIME = 3600;

    private QuotesClientInterface $client;
    private FilesystemAdapter $cache;
    private QuotesFormatter $formatter;

    public function __construct(QuotesClientInterface $client, QuotesFormatter $formatter)
    {
        $this->client = $client;
        $this->formatter = $formatter;

        $this->cache = new FilesystemAdapter();
    }

    /**
     * @throws InvalidArgumentException
     * @throws ExceededQuotesLimitException
     *
     * @return string[]
     */
    public function getAuthorQuotes(string $author, int $limit): array
    {
        if ($limit > self::QUOTES_MAX_RESULTS) {
            throw new ExceededQuotesLimitException();
        }

        return $this->cache->get("quotes-$author-$limit", function (ItemInterface $item) use ($author, $limit) {
            $item->expiresAfter(self::CACHE_LIFETIME);

            $quotes = $this->client->getQuotesByAuthor($author, $limit);

            return $this->formatter->formatQuotes($quotes);
        });
    }
}

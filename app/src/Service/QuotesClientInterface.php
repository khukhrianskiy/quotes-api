<?php

namespace App\Service;

interface QuotesClientInterface
{
    /**
     * @return string[]
     */
    public function getQuotesByAuthor(string $author, int $limit): array;
}

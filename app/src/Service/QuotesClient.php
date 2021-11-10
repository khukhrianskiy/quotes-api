<?php

namespace App\Service;

interface QuotesClient
{
    /**
     * @return string[]
     */
    public function getQuotesByAuthor(string $author, int $limit): array;
}

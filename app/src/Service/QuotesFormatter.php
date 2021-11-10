<?php

namespace App\Service;

class QuotesFormatter
{
    /**
     * @return string[]
     */
    public function formatQuotes(array $quotes): array
    {
        foreach ($quotes as &$quote) {
            $quote = strtoupper($quote);
        }

        return $quotes;
    }
}
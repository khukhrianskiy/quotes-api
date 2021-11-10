<?php

namespace App\Exception;

use App\Service\QuotesExtractor;
use Exception;
use JetBrains\PhpStorm\Pure;

class ExceededQuotesLimitException extends Exception
{
    #[Pure]
    public function __construct()
    {
        parent::__construct('Limit should be equal or less than ' . QuotesExtractor::QUOTES_MAX_RESULTS);
    }
}

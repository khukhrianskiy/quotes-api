<?php

namespace App\Tests\Unit\Service;

use App\Exception\ExceededQuotesLimitException;
use App\Service\QuotesClientInterface;
use App\Service\QuotesExtractor;
use App\Service\QuotesFormatter;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * @covers QuotesExtractor
 * @group quotesExtractor
 */
class QuotesExtractorTest extends TestCase
{
    #[ArrayShape(['case_1' => "array"])]
    public function quotesDataProvider(): array
    {
        return [
            'case_1' => [
                'author' => 'steve-jobs',
                'limit' => 1,
                'quotes' => ['Life isn’t about getting and having, it’s about giving and being.'],
                'formattedQuotes' => ['LIFE ISN’T ABOUT GETTING AND HAVING, IT’S ABOUT GIVING AND BEING.'],
            ],
        ];
    }

    /**
     * @dataProvider quotesDataProvider
     */
    public function testGetAuthorQuotes(string $author, int $limit, array $quotes, array $formattedQuotes): void
    {
        (new FilesystemAdapter())->deleteItem("quotes-$author-$limit");

        $quotesClientMock = $this->createMock(QuotesClientInterface::class);
        $quotesClientMock->expects(self::once())->method('getQuotesByAuthor')->with($author, $limit)->willReturn($quotes);

        $quotesFormatter = $this->createMock(QuotesFormatter::class);
        $quotesFormatter->expects(self::once())->method('formatQuotes')->with($quotes)->willReturn($formattedQuotes);

        $quotesExtractor = new QuotesExtractor(
            $quotesClientMock,
            $quotesFormatter
        );

        $quotesExtractor->getAuthorQuotes($author, $limit);
    }

    #[ArrayShape(['without_cache' => "array", 'with_cache' => "array"])]
    public function quotesDataProviderWithCache(): array
    {
        return [
            'without_cache' => [
                'author' => 'steve-jobs',
                'limit' => 1,
                'quotes' => ['Life isn’t about getting and having, it’s about giving and being.'],
                'formattedQuotes' => ['LIFE ISN’T ABOUT GETTING AND HAVING, IT’S ABOUT GIVING AND BEING.'],
                'hasCache' => false,
            ],
            'with_cache' => [
                'author' => 'steve-jobs',
                'limit' => 1,
                'quotes' => ['Life isn’t about getting and having, it’s about giving and being.'],
                'formattedQuotes' => ['LIFE ISN’T ABOUT GETTING AND HAVING, IT’S ABOUT GIVING AND BEING.'],
                'hasCache' => true,
            ],
        ];
    }

    /**
     * @dataProvider quotesDataProviderWithCache
     */
    public function testGetAuthorQuotesCache(string $author, int $limit, array $quotes, array $formattedQuotes, bool $hasCache): void
    {
        if (!$hasCache) {
            (new FilesystemAdapter())->deleteItem("quotes-$author-$limit");
        }

        $quotesClientMock = $this->createMock(QuotesClientInterface::class);
        $quotesClientMock->expects(self::exactly($hasCache ? 0 : 1))->method('getQuotesByAuthor')->with($author, $limit)->willReturn($quotes);

        $quotesFormatter = $this->createMock(QuotesFormatter::class);
        $quotesFormatter->expects(self::exactly($hasCache ? 0 : 1))->method('formatQuotes')->with($quotes)->willReturn($formattedQuotes);

        $quotesExtractor = new QuotesExtractor(
            $quotesClientMock,
            $quotesFormatter
        );

        $quotesExtractor->getAuthorQuotes($author, $limit);
    }

    /**
     * @dataProvider quotesDataProvider
     */
    public function testGetAuthorQuotesWhenLimitExceeded(): void
    {
        $quotesClientMock = $this->createMock(QuotesClientInterface::class);
        $quotesFormatter = $this->createMock(QuotesFormatter::class);

        $quotesExtractor = new QuotesExtractor(
            $quotesClientMock,
            $quotesFormatter
        );

        $this->expectException(ExceededQuotesLimitException::class);

        $quotesExtractor->getAuthorQuotes('steve-jobs', 11);
    }
}

<?php

namespace App\Tests\Unit\Service;

use App\Service\QuotesFormatter;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

/**
 * @covers QuotesFormatter
 * @group quotesFormatter
 */
class QuotesFormatterTest extends TestCase
{
    #[ArrayShape(['case_1' => "\string[][]", 'case_2' => "\string[][]", 'case_3' => "\string[][]"])]
    public function quotesDataProvider(): array
    {
        return [
            'case_1' => [
                'initialQuotes' => [
                    "Life isn’t about getting and having, it’s about giving and being.",
                ],
                'expectedQuotes' => [
                    "LIFE ISN’T ABOUT GETTING AND HAVING, IT’S ABOUT GIVING AND BEING.",
                ],
            ],
            'case_2' => [
                'initialQuotes' => [
                    "Life isn’t about getting and having, it’s about giving and being.",
                    "Whatever the mind of man can conceive and believe, it can achieve.",
                ],
                'expectedQuotes' => [
                    "LIFE ISN’T ABOUT GETTING AND HAVING, IT’S ABOUT GIVING AND BEING.",
                    "WHATEVER THE MIND OF MAN CAN CONCEIVE AND BELIEVE, IT CAN ACHIEVE.",
                ],
            ],
            'case_3' => [
                'initialQuotes' => [
                    "",
                    "Whatever the mind of man can conceive and believe, it can achieve.",
                ],
                'expectedQuotes' => [
                    "",
                    "WHATEVER THE MIND OF MAN CAN CONCEIVE AND BELIEVE, IT CAN ACHIEVE.",
                ],
            ],
        ];
    }

    /**
     * @dataProvider quotesDataProvider
     */
    public function testFormatQuotes(array $initialQuotes, array $expectedQuotes): void
    {
        $quotesFormatter = new QuotesFormatter();

        $formattedQuotes = $quotesFormatter->formatQuotes($initialQuotes);

        $this->assertSame($expectedQuotes, $formattedQuotes);
    }
}

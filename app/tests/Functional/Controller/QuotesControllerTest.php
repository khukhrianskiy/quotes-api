<?php

namespace App\Tests\Functional\Controller;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers QuotesController
 * @group quotesController
 */
class QuotesControllerTest extends WebTestCase
{
    public function testShoutAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/shout/steve-jobs');

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        $this->assertJson($content);
    }

    #[ArrayShape(['limit_1' => "int[]", 'limit_2' => "int[]"])]
    public function limitsDataProvider(): array
    {
        return [
            'limit_1' => [
                'limit' => 1,
            ],
            'limit_2' => [
                'limit' => 2,
            ],
        ];
    }

    /**
     * @dataProvider limitsDataProvider
     */
    public function testLimitInShoutAction(int $limit): void
    {
        $client = static::createClient();

        $client->request('GET', "/shout/steve-jobs?limit=$limit");

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        $this->assertJson($content);
        $this->assertCount($limit, json_decode($content));
    }

    public function testExceededLimitInShoutAction(): void
    {
        $client = static::createClient();

        $client->request('GET', "/shout/steve-jobs?limit=11");

        $this->assertResponseStatusCodeSame(422);

        $content = $client->getResponse()->getContent();

        $this->assertJson($content);
        $this->assertArrayHasKey('error', json_decode($content, true));
    }
}

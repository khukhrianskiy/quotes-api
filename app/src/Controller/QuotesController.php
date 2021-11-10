<?php

namespace App\Controller;

use App\Exception\ExceededQuotesLimitException;
use App\Service\QuotesExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuotesController
{
    private QuotesExtractor $quotesExtractor;

    public function __construct(QuotesExtractor $quotesExtractor)
    {
        $this->quotesExtractor = $quotesExtractor;
    }

    #[Route('/shout/{author}', methods: ['GET'])]
    public function shout(Request $request, string $author): Response
    {
        $limit = $request->query->getInt('limit', QuotesExtractor::QUOTES_MAX_RESULTS);

        try {
            return new JsonResponse($this->quotesExtractor->getAuthorQuotes($author, $limit));
        } catch (ExceededQuotesLimitException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $exception) {
            // TODO: log exception
            return new JsonResponse(['error' => 'Something went wrong'], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}

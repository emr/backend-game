<?php

namespace App\Http\Controller;

use App\Http\ArgumentValueResolver\RequestBody;
use App\Http\Schema\Request\EndGameRequest;
use App\Http\Schema\Response\SuccessResponse;
use App\Rank\Leaderboard;
use App\User\LoginHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/endgame', name: 'endGame', methods: ['POST'])]
class EndGameController
{
    public function __invoke(
        #[RequestBody] EndGameRequest $request,
        SerializerInterface $serializer,
        Leaderboard $leaderboard,
        LoginHandler $sessionService
    ): JsonResponse {
        foreach ($request->scores as $score) {
            $leaderboard->addScore($score);
            $sessionService->logout($score->id);
        }

        $response = new SuccessResponse($request->scores);

        return JsonResponse::fromJsonString($serializer->serialize($response, 'json'));
    }
}

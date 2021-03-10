<?php

namespace App\Http\Controller;

use App\Http\Schema\PaginationMeta;
use App\Http\Schema\PlayerRank;
use App\Http\Schema\Response\LeaderboardResponse;
use App\Rank\Leaderboard;
use App\User\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/leaderboard', name: 'leaderboard', methods: ['GET'])]
class LeaderboardController
{
    public function __construct(private Leaderboard $rankService, private UserService $userService)
    {
    }

    public function __invoke(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = max(1, $request->query->getInt('limit', 10));

        $ranks = $this->rankService->list(($page - 1) * $limit, $limit);

        $paginationMeta = $this->createPaginationMeta($page, $limit, \count($ranks));

        $response = new LeaderboardResponse($this->formatRanks($ranks), $paginationMeta);

        return JsonResponse::fromJsonString($serializer->serialize($response, 'json'));
    }

    /**
     * @param array<int, float> $ranks
     *
     * @return \Generator<PlayerRank>
     */
    private function formatRanks(array $ranks): \Generator
    {
        foreach ($ranks as $id => $rank) {
            $user = $this->userService->findById($id);

            if (null === $user || null === $user->id) {
                continue;
            }

            yield new PlayerRank(
                $user->id,
                $user->username,
                $rank,
            );
        }
    }

    private function createPaginationMeta(int $page, int $limit, int $count): PaginationMeta
    {
        $total = $this->rankService->countList();
        $totalPage = (int) ceil($total / $limit);

        return new PaginationMeta($count, $total, $totalPage, $page);
    }
}

<?php

namespace App\Tests\E2e;

class LeaderboardTest extends TestCase
{
    public function testCreateLeaderboard(): void
    {
        // register 10 users
        for ($i = 1; $i <= 10; ++$i) {
            $username = 'user'.$i;
            $this->signUp($username, '123');
            $this->signIn($username, '123');
        }

        // save scores
        $this->client->request(
            'POST',
            '/api/v1/endgame',
            content: '
                {"scores": [
                    {"id": 1, "score": 10},
                    {"id": 2, "score": 20},
                    {"id": 3, "score": 30},
                    {"id": 4, "score": 40.05},
                    {"id": 5, "score": 50.03},
                    {"id": 6, "score": 60.01},
                    {"id": 7, "score": 55.02},
                    {"id": 8, "score": 45.04},
                    {"id": 9, "score": 35.07}
                ]}
            '
        );
        static::assertResponseIsSuccessful();

        // request leaderboard page 1
        $this->client->request('GET', '/api/v1/leaderboard?limit=5');
        static::assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent() ?: '', true);

        static::assertEquals('success', $response['status']);
        $time = \DateTime::createFromFormat(\DateTime::ISO8601, $response['timestamp']);
        static::assertNotFalse($time);
        static::assertEqualsWithDelta(time(), $time->getTimestamp(), 2);
        static::assertEquals([
            'list' => [
                ['id' => '6', 'username' => 'user6', 'rank' => 60.01],
                ['id' => '7', 'username' => 'user7', 'rank' => 55.02],
                ['id' => '5', 'username' => 'user5', 'rank' => 50.03],
                ['id' => '8', 'username' => 'user8', 'rank' => 45.04],
                ['id' => '4', 'username' => 'user4', 'rank' => 40.05],
            ],
            'meta' => [
                'itemCount' => 5,
                'totalItems' => 9,
                'totalPage' => 2,
                'currentPage' => 1,
            ],
        ], $response['result']);

        // request leaderboard page 2
        $this->client->request('GET', '/api/v1/leaderboard?limit=5&page=2');
        static::assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent() ?: '', true);

        static::assertEquals('success', $response['status']);
        $time = \DateTime::createFromFormat(\DateTime::ISO8601, $response['timestamp']);
        static::assertNotFalse($time);
        static::assertEqualsWithDelta(time(), $time->getTimestamp(), 2);
        static::assertEquals([
            'list' => [
                ['id' => '9', 'username' => 'user9', 'rank' => 35.07],
                ['id' => '3', 'username' => 'user3', 'rank' => 30],
                ['id' => '2', 'username' => 'user2', 'rank' => 20],
                ['id' => '1', 'username' => 'user1', 'rank' => 10],
            ],
            'meta' => [
                'itemCount' => 4,
                'totalItems' => 9,
                'totalPage' => 2,
                'currentPage' => 2,
            ],
        ], $response['result']);
    }

    public function testUpdateLeaderboard(): void
    {
        // register 10 users
        for ($i = 1; $i <= 10; ++$i) {
            $username = 'user'.$i;
            $this->signUp($username, '123');
            $this->signIn($username, '123');
        }

        // save scores
        $this->client->request(
            'POST',
            '/api/v1/endgame',
            content: '
                {"scores": [
                    {"id": 1, "score": 10},
                    {"id": 2, "score": 20},
                    {"id": 3, "score": 30},
                    {"id": 4, "score": 40},
                    {"id": 5, "score": 50},
                    {"id": 6, "score": 60},
                    {"id": 7, "score": 55},
                    {"id": 8, "score": 45},
                    {"id": 9, "score": 35}
                ]}
            '
        );
        static::assertResponseIsSuccessful();
    }

    public function testEmptyLeaderboard(): void
    {
        $this->client->request('GET', '/api/v1/leaderboard');
        static::assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent() ?: '', true);

        static::assertEquals('success', $response['status']);
        $time = \DateTime::createFromFormat(\DateTime::ISO8601, $response['timestamp']);
        static::assertNotFalse($time);
        static::assertEqualsWithDelta(time(), $time->getTimestamp(), 2);
        static::assertEquals([
            'list' => [],
            'meta' => [
                'itemCount' => 0,
                'totalItems' => 0,
                'totalPage' => 0,
                'currentPage' => 1,
            ],
        ], $response['result']);
    }
}

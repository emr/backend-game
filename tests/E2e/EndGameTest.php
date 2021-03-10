<?php

namespace App\Tests\E2e;

class EndGameTest extends TestCase
{
    public function testEndGame(): void
    {
        // register and sign in 4 users
        $this->signUp('user1', '123');
        $this->signUp('user2', '123');
        $this->signUp('user3', '123');
        $this->signUp('user4', '123');
        $this->signIn('user1', '123');
        $this->signIn('user2', '123');
        $this->signIn('user3', '123');
        $this->signIn('user4', '123');

        // end game
        $this->client->request(
            'POST',
            '/api/v1/endgame',
            content: '
                {"scores": [
                    {"id": 1, "score": 10},
                    {"id": 2, "score": 20.74},
                    {"id": 3, "score": 30.03},
                    {"id": 4, "score": 40}
                ]}
            '
        );
        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent() ?: '', true);

        static::assertEquals('success', $response['status']);
        $time = \DateTime::createFromFormat(\DateTime::ISO8601, $response['timestamp']);
        static::assertNotFalse($time);
        static::assertEqualsWithDelta(time(), $time->getTimestamp(), 2);
        static::assertEquals([
            ['id' => 1, 'score' => 10],
            ['id' => 2, 'score' => 20.74],
            ['id' => 3, 'score' => 30.03],
            ['id' => 4, 'score' => 40],
        ], $response['result']);
    }

    /**
     * @return array<string, array>
     */
    public static function failCases(): array
    {
        return [
            'Player id is missing' => [
                '{"scores": [{"score": 10}, {"id": 2, "score": 20}]}',
                '{"message":"Invalid player id \"0\"","code":400}',
                400,
            ],
            'Player has not logged in' => [
                '{"scores": [{"id": 1, "score": 10}, {"id": 2, "score": 20}]}',
                '{"message":"Player with id \"1\" has not logged in","code":400}',
                400,
            ],
            'Player has more than 1 score' => [
                '{"scores": [{"id": 1, "score": 10}, {"id": 2, "score": 20.12}, {"id": 1, "score": 30}]}',
                '{"message":"Player with id \"1\" has multiple scores","code":400}',
                400,
            ],
        ];
    }

    /**
     * @dataProvider failCases
     */
    public function testFailCases(string $request, string $response, int $statusCode): void
    {
        $this->client->request('POST', '/api/v1/endgame', content: $request);

        static::assertResponseStatusCodeSame($statusCode);
        static::assertEquals($response, $this->client->getResponse()->getContent());
    }

    public function testLogoutAfterGameEnds(): void
    {
        // register and sign in 4 users
        $this->signUp('user1', '123');
        $this->signUp('user2', '123');
        $this->signUp('user3', '123');
        $this->signUp('user4', '123');
        $this->signIn('user1', '123');
        $this->signIn('user2', '123');
        $this->signIn('user3', '123');
        $this->signIn('user4', '123');

        // end game between first 2 users
        $this->client->request(
            'POST',
            '/api/v1/endgame',
            content: '
                {"scores": [
                    {"id": 1, "score": 10},
                    {"id": 2, "score": 20.3}
                ]}
            '
        );
        static::assertResponseIsSuccessful();

        // the users should be logged out and so they should not be able to end the game again
        $this->client->request(
            'POST',
            '/api/v1/endgame',
            content: '
                {"scores": [
                    {"id": 1, "score": 10.001},
                    {"id": 2, "score": 20}
                ]}
            '
        );
        static::assertResponseStatusCodeSame(400);
        static::assertEquals(
            '{"message":"Player with id \"1\" has not logged in","code":400}',
            $this->client->getResponse()->getContent()
        );

        // the other users can still end the game
        $this->client->request(
            'POST',
            '/api/v1/endgame',
            content: '
                {"scores": [
                    {"id": 3, "score": 30.003},
                    {"id": 4, "score": 40}
                ]}
            '
        );
        static::assertResponseIsSuccessful();

        // now the other users should not be able to end the game again too
        $this->client->request(
            'POST',
            '/api/v1/endgame',
            content: '
                {"scores": [
                    {"id": 3, "score": 10},
                    {"id": 4, "score": 20.49}
                ]}
            '
        );
        static::assertResponseStatusCodeSame(400);
        static::assertEquals(
            '{"message":"Player with id \"3\" has not logged in","code":400}',
            $this->client->getResponse()->getContent()
        );
    }
}

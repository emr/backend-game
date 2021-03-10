<?php

namespace App\Tests\E2e;

class SignInTest extends TestCase
{
    public function testSignIn(): void
    {
        // register
        $this->client->request('POST', '/api/v1/user/signup', content: '{"username": "test", "password": "123"}');
        static::assertResponseIsSuccessful();

        // login
        $this->client->request('POST', '/api/v1/user/signin', content: '{"username": "test", "password": "123"}');
        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent() ?: '');

        static::assertEquals('success', $response->status);
        $time = \DateTime::createFromFormat(\DateTime::ISO8601, $response->timestamp);
        static::assertNotFalse($time);
        static::assertEqualsWithDelta(time(), $time->getTimestamp(), 2);
        static::assertEquals((object) [
            'id' => '1',
            'username' => 'test',
        ], $response->result);
    }

    /**
     * @return array<string, array>
     */
    public static function failCases(): array
    {
        return [
            'Empty username' => [
                '{"username": "", "password": ""}',
                '{"message":"Bad credentials","code":401}',
                401,
            ],
            'Empty password' => [
                '{"username": "test", "password": ""}',
                '{"message":"Bad credentials","code":401}',
                401,
            ],
            'Invalid request body' => [
                'this is not a json string',
                '{"message":"Request body is not a valid json","code":400}',
                400,
            ],
            'Bad credentials' => [
                '{"username": "test", "password": "test"}',
                '{"message":"Bad credentials","code":401}',
                401,
            ],
        ];
    }

    /**
     * @dataProvider failCases
     */
    public function testFailCases(string $request, string $response, int $statusCode): void
    {
        $this->client->request('POST', '/api/v1/user/signin', content: $request);

        static::assertResponseStatusCodeSame($statusCode);
        static::assertEquals($response, $this->client->getResponse()->getContent());
    }
}

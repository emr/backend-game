<?php

namespace App\Tests\E2e;

class SignUpTest extends TestCase
{
    public function testSignUp(): void
    {
        $this->client->request('POST', '/api/v1/user/signup', content: '{"username": "test", "password": "123"}');
        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent() ?: '');

        static::assertEquals('success', $response->status);
        $time = \DateTime::createFromFormat(\DateTime::ISO8601, $response->timestamp);
        static::assertNotFalse($time);
        static::assertEqualsWithDelta(time(), $time->getTimestamp(), 2);
        static::assertEquals((object) [
            'id' => '1',
            'username' => 'test',
            'password' => '123',
        ], $response->result);
    }

    public function testDuplicateUsername(): void
    {
        $this->client->request('POST', '/api/v1/user/signup', content: '{"username": "test", "password": "123"}');
        static::assertResponseIsSuccessful();

        $this->client->request('POST', '/api/v1/user/signup', content: '{"username": "test", "password": "123"}');

        $response = $this->client->getResponse();
        static::assertResponseStatusCodeSame(400);
        static::assertEquals('{"message":"Username \"test\" is already in use","code":400}', $response->getContent());
    }

    /**
     * @return array<string, array>
     */
    public static function failCases(): array
    {
        return [
            'Empty username' => [
                '{"username": "", "password": ""}',
                '{"message":"Username should not be empty","code":400}',
                400,
            ],
            'Empty password' => [
                '{"username": "test", "password": ""}',
                '{"message":"Password should not be empty","code":400}',
                400,
            ],
            'Invalid request body' => [
                'this is not a json string',
                '{"message":"Invalid request body","code":400}',
                400,
            ],
        ];
    }

    /**
     * @dataProvider failCases
     */
    public function testFailCases(string $request, string $expectedResponse, int $expectedStatusCode): void
    {
        $this->client->request('POST', '/api/v1/user/signup', content: $request);

        $response = $this->client->getResponse();
        static::assertResponseStatusCodeSame($expectedStatusCode);
        static::assertEquals($expectedResponse, $response->getContent());
    }
}

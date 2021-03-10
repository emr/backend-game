<?php

namespace App\Http\Controller;

use App\Http\ArgumentValueResolver\RequestBody;
use App\Http\Schema\Request\SignInRequest;
use App\Http\Schema\Response\SuccessResponse;
use App\User\CreateUser;
use App\User\Exception\DuplicateRecordException;
use App\User\LoginHandler;
use App\User\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/user', name: 'user.')]
class AuthController
{
    public function __construct(private SerializerInterface $serializer, private UserService $userService)
    {
    }

    #[Route('/signup', name: 'signUp', methods: ['POST'])]
    public function signUp(#[RequestBody] CreateUser $input): JsonResponse
    {
        try {
            $user = $this->userService->register($input);
        } catch (DuplicateRecordException) {
            throw new BadRequestHttpException("Username \"{$input->username}\" is already in use");
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                new SuccessResponse($user),
                'json',
                ['groups' => ['response', 'signUp']]
            ),
            JsonResponse::HTTP_CREATED
        );
    }

    #[Route('/signin', name: 'signIn', methods: ['POST'])]
    public function signIn(#[RequestBody] SignInRequest $input, LoginHandler $loginHandler): JsonResponse
    {
        $user = $this->userService->findByUsername($input->username);

        if (null === $user || $user->password !== $input->password) {
            throw new UnauthorizedHttpException('', 'Bad credentials');
        }

        if ($user->id) {
            $loginHandler->login($user->id);
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                new SuccessResponse($user),
                'json',
                ['groups' => ['response', 'signIn']]
            )
        );
    }
}

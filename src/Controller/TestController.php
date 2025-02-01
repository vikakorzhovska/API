<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1')]
class TestController extends AbstractController
{

    public const USERS = [
        [
            'id'    => '1',
            'email' => 'ipz234_kvv@student.ztu.edu.ua',
            'name'  => 'Vika'
        ],
        [
            'id'    => '2',
            'email' => 'ipz234_kvv2@student.ztu.edu.ua',
            'name'  => 'Nadia'
        ],
        [
            'id'    => '3',
            'email' => 'ipz234_kvv3@student.ztu.edu.ua',
            'name'  => 'Dima'
        ],
        [
            'id'    => '4',
            'email' => 'ipz234_kvv4@student.ztu.edu.ua',
            'name'  => 'Denis'
        ],
        [
            'id'    => '5',
            'email' => 'ipz234_kvv5@student.ztu.edu.ua',
            'name'  => 'Vlad'
        ],
        [
            'id'    => '6',
            'email' => 'ipz234_kvv6@student.ztu.edu.ua',
            'name'  => 'Artem'
        ],
        [
            'id'    => '7',
            'email' => 'ipz234_kvv7@student.ztu.edu.ua',
            'name'  => 'Nastia'
        ],
    ];

    #[Route('/users', name: 'coll_users', methods: ['GET'])]
    public function getColl(): JsonResponse
    {
        return new JsonResponse([
            'data' => self::USERS
        ], Response::HTTP_OK);
    }

    #[Route('/users/{id}', name: 'item_users', methods: ['GET'])]
    public function getItem(string $id): JsonResponse
    {
        $userData = $this->findUser($id);

        return new JsonResponse([
            'data' => $userData
        ], Response::HTTP_OK);
    }

    #[Route('/users', name: 'new_users', methods: ['POST'])]
    public function newItem(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['email'], $requestData['name'])) {
            throw new UnprocessableEntityHttpException("name and email are required");
        }

        // TODO check by regex

        $countOfUsers = count(self::USERS);

        $newUser = [
            'id'    => $countOfUsers + 1,
            'name'  => $requestData['name'],
            'email' => $requestData['email']
        ];

        // TODO add new user to collection

        return new JsonResponse([
            'data' => $newUser
        ], Response::HTTP_CREATED);
    }

    #[Route('/users/{id}', name: 'delete_users', methods: ['DELETE'])]
    public function deleteItem(string $id): JsonResponse
    {
        $this->findUser($id);

        // TODO remove user from collection

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/users/{id}', name: 'update_users', methods: ['PATCH'])]
    public function updateItem(string $id, Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['name'])) {
            throw new UnprocessableEntityHttpException("name is required");
        }

        $userData = $this->findUser($id);

        // TODO update user name

        $userData['name'] = $requestData['name'];

        return new JsonResponse(['data' => $userData], Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return string[]
     */
    public function findUser(string $id): array
    {
        $userData = null;

        foreach (self::USERS as $user) {
            if (!isset($user['id'])) {
                continue;
            }

            if ($user['id'] == $id) {
                $userData = $user;

                break;
            }

        }

        if (!$userData) {
            throw new NotFoundHttpException("User with id " . $id . " not found");
        }

        return $userData;
    }

}
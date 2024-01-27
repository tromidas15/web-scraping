<?php

namespace App\Controller;

use App\Services\ParliamentMemberService;
use App\Traits\ApiResponseTrait;
use Symfony\Component\HttpFoundation\JsonResponse;

class ParliamentMembersController
{
    use ApiResponseTrait;

    public function __construct(private ParliamentMemberService $parliamentMemberService)
    {
    }

    /**
     * @Route("/persons", name="get_persons", methods={"GET"})
     */
    public function getPersons(): JsonResponse
    {
        try {
            return $this->successResponse($this->parliamentMemberService->getAll());
        } catch (\Throwable $th) {
            //to add some logs
            return $this->serverErrorResponse();
        }
    }

    /**
     * @Route("/persons/{id}", name="get_person", methods={"GET"})
     *
     * just as a preference I do not inject the entity
     */
    public function getPerson(int $id): JsonResponse
    {
        try {
            $person = $this->parliamentMemberService->getById($id);
            if(empty($person)) {
                return $this->notFount();
            }
            return $this->successResponse($person);
        } catch (\Throwable $th) {
            //to add some logs
            return $this->serverErrorResponse();
        }
    }

}
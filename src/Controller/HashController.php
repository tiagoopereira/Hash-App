<?php

namespace App\Controller;

use App\Service\HashService;
use App\Exceptions\ValidationException;
use App\Service\ResponseService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class HashController extends AbstractController
{
    protected HashService $hashService;

    public function __construct(HashService $hashService)
    {
        $this->hashService = $hashService;
    }

    public function index(Request $request): JsonResponse
    {
        $limit = $request->get('limit') ? $request->get('limit') : 10;
        $offset = $request->get('offset') ? $request->get('offset') : 1;
        $filter = $request->get('filter');

        $entities = $this->hashService->findAll($limit, $offset, $filter);
        $response =  new ResponseService($entities, $limit, $offset);

        return $response->getResponse();
    }

    public function create(Request $request, RateLimiterFactory $anonymousApiLimiter, string $string): JsonResponse
    {
        $limiter = $anonymousApiLimiter->create($request->getClientIp());

        if ($limiter->consume(1)->isAccepted() === false) {
            throw new TooManyRequestsHttpException();
        }

        if (!isset($string) || empty($string)) {
            return new JsonResponse(['error' => 'No string provided'], 400);
        }

        $batch = $request->get('batch');
        $previousBlock = $request->get('block') ? $request->get('block') : 0;

        try {
            $entity = $this->hashService->create($string, $batch, $previousBlock);
            return new JsonResponse($entity, JsonResponse::HTTP_CREATED);
        } catch(ValidationException  $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
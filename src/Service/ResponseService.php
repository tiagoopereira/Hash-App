<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseService
{
    private array $data;
    private int $limit;
    private int $offset;
    private bool $success;
    private int $code;

    public function __construct(
        array $data,
        int $limit,
        int $offset,
        bool $success = true,
        int $code = 200
    )
    {
        $this->data = $data;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->success = $success;
        $this->code = $code;
    }

    public function getResponse(): JsonResponse
    {
        return new JsonResponse(
            [
                'success' => $this->success,
                'limit' => $this->limit,
                'page' => $this->offset,
                'data' => $this->data
            ],
            $this->code
        );
    }
}
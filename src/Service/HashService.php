<?php

namespace App\Service;

use App\Entity\Hash;
use App\Factory\HashEntityFactory;
use App\Repository\HashRepository;
use App\Service\GenerateHashService;
use Doctrine\ORM\EntityManagerInterface;

class HashService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll(int $limit, int $offset, ?array $filter): array
    {
        /** @var HashRepository */
        $repository = $this->entityManager->getRepository(Hash::class);
        $entities = $repository->getAll($limit, $offset, $filter);

        if (!count($entities)) {
            return [];
        }

        $data  = [];

        foreach($entities as $entity) {
            $entityArr = [
                'batch' => $entity->getBatch()->format('Y-m-d H:i:s'),
                'block' => $entity->getBlock(),
                'string' => $entity->getString(),
                'key' => $entity->getKey()
            ];

            array_push($data, $entityArr);
        }

        return $data;
    }

    public function create(string $string, ?string $batch = null, int $previousBlock = 0): array
    {
        $data = GenerateHashService::generate($string, $previousBlock);

        if (!is_null($batch) && !empty($batch)) {
            $data['batch'] = new \DateTime($batch, new \DateTimeZone('America/Sao_Paulo'));
        }

        $entity = HashEntityFactory::create($data);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return [
            'hash' => $entity->getGeneratedHash(),
            'key' => $entity->getKey(),
            'attempts' => $entity->getAttempts()
        ];
    }
}
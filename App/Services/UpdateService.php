<?php

namespace Api\Services;

use Api\Models\UpdateModel;
use Core\Exceptions\EntityNotFound;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use UnexpectedValueException;

class UpdateService
{
    private EntityManager $entityManager;
    private EntityRepository $entityRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityManager->getRepository(UpdateModel::class);
    }

    /**
     * Возвращает обновление для выбранного продукта
     * @param string $product
     * @param string|null $sort
     * @return array
     * @throws EntityNotFound
     */
    public function get(string $product, ?string $sort): array
    {
        /** @var UpdateModel $productUpdate */
        $productUpdate = $this->entityRepository->findOneBy(["product" => $product], ["version" => $sort ?? "desc"]);

        if ($productUpdate === null) throw new EntityNotFound("current entity 'update by product' not found");

        return [
            "product" => $productUpdate->getProduct(),
            "version" => $productUpdate->getVersion(),
            "description" => $productUpdate->getDescription()
        ];
    }

    /**
     * Возвращает обновления для выбранного продукта
     * @param string $product
     * @param string|null $sort
     * @return array
     * @throws UnexpectedValueException
     * @throws EntityNotFound
     */
    public function getAll(string $product, ?string $sort): array
    {
        /** @var UpdateModel[] $productUpdates */
        $productUpdates = $this->entityRepository->findBy(["product" => $product], ["version" => $sort ?? "desc"]);

        if ($productUpdates === null) throw new EntityNotFound("current entities 'updates by product' not found");

        $preparedData = [];

        foreach ($productUpdates as $update) {
            $preparedData[] = [
                "product" => $update->getProduct(),
                "version" => $update->getVersion(),
                "description" => $update->getDescription()
            ];
        }

        return $preparedData;
    }
}
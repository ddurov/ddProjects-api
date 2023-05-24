<?php declare(strict_types=1);

namespace Api\Services;

use Api\Models\UpdateModel;
use Core\Exceptions\EntityException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use UnexpectedValueException;

class UpdateService
{
    private EntityRepository $entityRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityRepository = $entityManager->getRepository(UpdateModel::class);
    }

    public function add($file, string $version, string $description): bool
    {


        return true;
    }

    /**
     * Возвращает обновление для выбранного продукта
     * @param string $product
     * @param string|null $sort
     * @return array
     * @throws EntityException
     */
    public function get(string $product, ?string $sort): array
    {
        /** @var UpdateModel $productUpdate */
        $productUpdate = $this->entityRepository->findOneBy(["product" => $product], ["version" => $sort ?? "desc"]);

        if ($productUpdate === null) throw new EntityException("current entity 'update by product' not found", 404);

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
     * @throws EntityException
     */
    public function getAll(string $product, ?string $sort): array
    {
        /** @var UpdateModel[] $productUpdates */
        $productUpdates = $this->entityRepository->findBy(["product" => $product], ["version" => $sort ?? "desc"]);

        if ($productUpdates === null)
            throw new EntityException("current entities 'updates by product' not found", 404);

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
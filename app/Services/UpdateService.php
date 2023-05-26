<?php declare(strict_types=1);

namespace Api\Services;

use Api\Models\UpdateModel;
use Core\Exceptions\EntityException;
use Core\Exceptions\InternalError;
use Core\Exceptions\ParametersException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
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
     * @param array $file
     * @param string $product
     * @param string $version
     * @param string $description
     * @return bool
     * @throws InternalError
     * @throws ORMException
     * @throws OptimisticLockException|EntityException
     */
    public function add(array $file, string $product, string $version, string $description): bool
    {
        $path = "/var/www/updates/{$file['file']['name']}";

        if (!move_uploaded_file($file["file"]["tmp_name"], $path))
            throw new InternalError("update file not moved to specify directory");

        if ($this->entityRepository->findOneBy(["product" => $product, "version" => $version]))
            throw new EntityException("current entity 'update by product and version' are exists", 422);

        $newUpdate = new UpdateModel();
        $newUpdate->setPath($path);
        $newUpdate->setProduct($product);
        $newUpdate->setVersion($version);
        $newUpdate->setDescription($description);

        $this->entityManager->persist($newUpdate);
        $this->entityManager->flush();

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
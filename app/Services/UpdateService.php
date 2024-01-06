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
     * Добавляет обновление для указанного продукта
     * @param array $file
     * @param string $product
     * @param string $versionName
     * @param int $versionCode
     * @param string $description
     * @return bool
     * @throws EntityException
     * @throws InternalError
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(array $file, string $product, string $versionName, int $versionCode, string $description): bool
    {
        $path = "/var/www/updates/{$file['file']['name']}";

        if (!move_uploaded_file($file["file"]["tmp_name"], $path))
            throw new InternalError("update file not moved to specify directory");

        if ($this->entityRepository->findOneBy(["product" => $product, "versionCode" => $versionCode]))
            throw new EntityException("current entity 'update by product and versionCode' are exists", 422);

        $this->entityManager->persist(new UpdateModel(
            $product,
            $versionName,
            $versionCode,
            $path,
            $description
        ));
        $this->entityManager->flush();

        return true;
    }

    /**
     * Отдаёт обновление на скачивание
     * @param string $product
     * @param string $versionName
     * @return void
     * @throws EntityException
     */
    public function get(string $product, string $versionName): void
    {
        /** @var UpdateModel $productUpdate */
        $productUpdate = $this->entityRepository->findOneBy(["product" => $product, "versionName" => $versionName]);

        if ($productUpdate === null)
            throw new EntityException("current entity 'update by product and versionName' not found", 404);

        header("Content-Length:" . filesize($productUpdate->getPath()));
        header("Content-Disposition: attachment; filename=update.apk");
        readfile($productUpdate->getPath());
        die();
    }

    /**
     * Возвращает обновление для выбранного продукта
     * @param string $product
     * @param string|null $sort
     * @return array
     * @throws EntityException
     */
    public function info(string $product, ?string $sort): array
    {
        /** @var UpdateModel $productUpdate */
        $productUpdate = $this->entityRepository->findOneBy(["product" => $product], ["versionCode" => $sort ?? "desc"]);

        if ($productUpdate === null) throw new EntityException("current entity 'update by product' not found", 404);

        return [
            "product" => $productUpdate->getProduct(),
            "versionName" => $productUpdate->getVersionName(),
            "versionCode" => $productUpdate->getVersionCode(),
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
    public function infoAll(string $product, ?string $sort): array
    {
        /** @var UpdateModel[] $productUpdates */
        $productUpdates = $this->entityRepository->findBy(["product" => $product], ["versionCode" => $sort ?? "desc"]);

        if ($productUpdates === null)
            throw new EntityException("current entities 'updates by product' not found", 404);

        $preparedData = [];

        foreach ($productUpdates as $update) {
            $preparedData[] = [
                "product" => $update->getProduct(),
                "versionName" => $update->getVersionName(),
                "versionCode" => $update->getVersionCode(),
                "description" => $update->getDescription()
            ];
        }

        return $preparedData;
    }
}
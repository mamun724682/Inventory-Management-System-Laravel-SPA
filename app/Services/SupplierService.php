<?php

namespace App\Services;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Supplier\SupplierFieldsEnum;
use App\Enums\Supplier\SupplierFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Exceptions\SupplierNotFoundException;
use App\Helpers\ArrayHelper;
use App\Helpers\BaseHelper;
use App\Models\Supplier;
use App\Repositories\SupplierRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SupplierService
{
    public function __construct(
        private readonly SupplierRepository $repository,
        private readonly FileManagerService $fileManagerService
    )
    {
    }

    /**
     * @param array $queryParameters
     * @return LengthAwarePaginator
     */
    public function getAll(array $queryParameters): LengthAwarePaginator
    {
        $page = $queryParameters["page"] ?? 1;
        $perPage = BaseHelper::perPage($queryParameters["per_page"] ?? null);

        return $this->repository->getAll(
            page: $page,
            perPage: $perPage,
            filters: ArrayHelper::getFiltersValues($queryParameters, SupplierFiltersEnum::values()),
            fields: $queryParameters["fields"] ?? [],
            expand: $queryParameters["expand"] ?? [],
            sortBy: $queryParameters["sort_by"] ?? SupplierFieldsEnum::CREATED_AT->value,
            sortOrder: $queryParameters["sort_order"] ?? SortOrderEnum::DESC->value,
        );
    }

    /**
     * @param int $id
     * @param array $expands
     * @return Supplier|null
     * @throws SupplierNotFoundException
     */
    public function findByIdOrFail(int $id, array $expands = []): ?Supplier
    {
        $supplier = $this->repository->find([
            SupplierFiltersEnum::ID->value => $id
        ], $expands);

        if (!$supplier) {
            throw new SupplierNotFoundException('Supplier not found by the given id.');
        }

        return $supplier;
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws DBCommitException
     */
    public function create(array $payload): mixed
    {
        $photo = null;
        if (isset($payload['photo'])) {
            $photo = $this->fileManagerService->uploadFile(
                file: $payload['photo'],
                uploadPath: Supplier::PHOTO_PATH
            );
        }

        $processPayload = [
            SupplierFieldsEnum::NAME->value      => $payload[SupplierFieldsEnum::NAME->value],
            SupplierFieldsEnum::EMAIL->value     => $payload[SupplierFieldsEnum::EMAIL->value],
            SupplierFieldsEnum::PHONE->value     => $payload[SupplierFieldsEnum::PHONE->value],
            SupplierFieldsEnum::ADDRESS->value   => $payload[SupplierFieldsEnum::ADDRESS->value],
            SupplierFieldsEnum::PHOTO->value     => $photo,
            SupplierFieldsEnum::SHOP_NAME->value => $payload[SupplierFieldsEnum::SHOP_NAME->value],
        ];

        return $this->repository->create($processPayload);
    }

    /**
     * @param int $id
     * @param array $payload
     * @return Supplier
     * @throws SupplierNotFoundException
     * @throws Exception
     */
    public function update(int $id, array $payload): Supplier
    {
        $supplier = $this->findByIdOrFail($id);

        $photo = $supplier->getRawOriginal(SupplierFieldsEnum::PHOTO->value);
        if (isset($payload['photo'])) {
            $photo = $this->fileManagerService->uploadFile(
                file: $payload['photo'],
                uploadPath: Supplier::PHOTO_PATH,
                deleteFileName: $photo
            );
        }

        $processPayload = [
            SupplierFieldsEnum::NAME->value      => $payload[SupplierFieldsEnum::NAME->value] ?? $supplier->name,
            SupplierFieldsEnum::EMAIL->value     => $payload[SupplierFieldsEnum::EMAIL->value] ?? $supplier->email,
            SupplierFieldsEnum::PHONE->value     => $payload[SupplierFieldsEnum::PHONE->value] ?? $supplier->phone,
            SupplierFieldsEnum::ADDRESS->value   => $payload[SupplierFieldsEnum::ADDRESS->value] ?? $supplier->address,
            SupplierFieldsEnum::PHOTO->value     => $photo,
            SupplierFieldsEnum::SHOP_NAME->value => $payload[SupplierFieldsEnum::SHOP_NAME->value] ?? $supplier->shop_name,
        ];

        return $this->repository->update($supplier, $processPayload);
    }

    /**
     * @param int $id
     * @return bool|null
     * @throws SupplierNotFoundException
     */
    public function delete(int $id): ?bool
    {
        $supplier = $this->findByIdOrFail($id);
        $photo = $supplier->getRawOriginal(SupplierFieldsEnum::PHOTO->value);
        if ($photo) {
            $this->fileManagerService->delete(
                fileName: $photo,
                path: Supplier::PHOTO_PATH,
            );
        }

        return $this->repository->delete($supplier);
    }
}

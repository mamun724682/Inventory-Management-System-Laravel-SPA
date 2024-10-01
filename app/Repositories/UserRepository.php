<?php

namespace App\Repositories;

use App\Enums\User\UserFieldsEnum;
use App\Enums\User\UserFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderWhenProxy;

class UserRepository
{
    const MAX_RETRY = 5;

    /**
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @param array $fields
     * @param array $expand
     * @param string $sortBy
     * @param string $sortOrder
     * @return LengthAwarePaginator
     */
    public function getAll(
        int    $page,
        int    $perPage,
        array  $filters = [],
        array  $fields = [],
        array  $expand = [],
        string $sortBy = "id",
        string $sortOrder = "ASC"
    ): LengthAwarePaginator
    {
        $query = $this->getQuery($filters)
            ->orderBy($sortBy, $sortOrder)
            ->with($expand);

        if (count($fields) > 0) {
            $query = $query->select($fields);
        }

        return $query->paginate(
            perPage: $perPage,
            page: $page
        )->withQueryString();
    }

    /**
     * @param array $filters
     * @return bool
     */
    public function exists(array $filters = []): bool
    {
        return $this->getQuery($filters)->exists();
    }

    /**
     * @param array $filters
     * @param array $expand
     * @return User|null
     */
    public function find(array $filters = [], array $expand = []): ?User
    {
        return $this->getQuery($filters)
            ->with($expand)
            ->first();
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws DBCommitException
     */
    public function create(array $payload): mixed
    {
        try {
            DB::beginTransaction();
            $user = User::create($payload);
            DB::commit();
            return $user;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param User $user
     * @param array $changes
     * @return User
     * @throws Exception
     */
    public function update(User $user, array $changes): User
    {
        $attempt = 1;
        do {
            $updated = $user->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during user update");
        }

        return $user->refresh();
    }

    /**
     * @param User $user
     * @return bool|null
     */
    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

    /**
     * @param array $filters
     * @return Builder|HigherOrderWhenProxy
     */
    private function getQuery(array $filters): Builder|HigherOrderWhenProxy
    {
        return User::query()
            ->when(isset($filters[UserFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(UserFieldsEnum::ID->value, $filters[UserFiltersEnum::ID->value]);
            })
            ->when(isset($filters[UserFiltersEnum::NAME->value]), function ($query) use ($filters) {
                $query->where(UserFieldsEnum::NAME->value, "like", "%" . $filters[UserFiltersEnum::NAME->value] . "%");
            })
            ->when(isset($filters[UserFiltersEnum::EMAIL->value]), function ($query) use ($filters) {
                $query->where(UserFieldsEnum::EMAIL->value, $filters[UserFiltersEnum::EMAIL->value]);
            })
            ->when(isset($filters[UserFiltersEnum::EMAIL_VERIFIED_AT->value]), function ($query) use ($filters) {
                $query->whereNotNull(UserFieldsEnum::EMAIL_VERIFIED_AT->value);
            })
            ->when(isset($filters[UserFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(UserFieldsEnum::CREATED_AT->value, $filters[UserFiltersEnum::CREATED_AT->value]);
            });
    }
}

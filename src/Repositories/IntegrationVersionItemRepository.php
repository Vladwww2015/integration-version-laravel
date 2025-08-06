<?php

namespace IntegrationHelper\IntegrationVersionLaravel\Repositories;

use IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersionItem;
use Webkul\Core\Eloquent\Repository;
use IntegrationHelper\IntegrationVersion\Model\IntegrationVersionItemInterface;
use IntegrationHelper\IntegrationVersion\Repository\IntegrationVersionItemRepositoryInterface;

/**
 *
 */
class IntegrationVersionItemRepository extends Repository implements IntegrationVersionItemRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return 'IntegrationHelper\IntegrationVersionLaravel\Contracts\IntegrationVersionItem';
    }

    /**
     * @param int $parentId
     * @param array $identityValues
     * @return iterable|IntegrationVersionItemInterface[]
     */
    public function getItems(int $parentId, array $identityValues): iterable
    {
        if(!$identityValues) return [];

        if(count($identityValues) === 1) {
            return $this->findWhere([
                'parent_id' => $parentId,
                'identity_value' => current($identityValues)
            ]);
        }

        return $this->findWhere([
            'parent_id' => $parentId,
            'identity_value' => [
                'identity_value', 'IN', $identityValues
            ]
        ]);
    }

    /**
     * @param int $id
     * @return IntegrationVersionItemInterface
     */
    public function getItemById(int $id): IntegrationVersionItemInterface
    {
        return $this->find($id);
    }

    /**
     * @param int $parentId
     * @param string $oldExternalHash
     * @param string $oldHashDateTime
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getIdentitiesForNewestVersions(int $parentId, string $oldExternalHash, string $oldHashDateTime, int $page = 1, int $limit = 50000): iterable
    {
        /**
         * @var $model \IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersionItem
         */
        $page = ($page - 1);
        $offset = $page <= 0 ? 0 : $page * $limit;
        $query = $this->getModel()
            ->where('parent_id', '=', $parentId)
            ->where('version_hash', '!=', $oldExternalHash)
            ->where('status', '=', IntegrationVersionItem::STATUS_SUCCESS)
            ->where('hash_date_time', '>', $oldHashDateTime)
            ->orderBy('identity_value');

        $query->limit($limit);
        if($offset) {
            $query->offset($offset);
        }

        return $query->get('identity_value');
    }

    public function getIdentitiesTotalForNewestVersions(int $parentId, string $oldExternalHash, string $oldHashDateTime): int
    {
        /**
         * @var $model \IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersionItem
         */
        $query = $this->getModel()
            ->where('parent_id', '=', $parentId)
            ->where('version_hash', '!=', $oldExternalHash)
            ->where('status', '=', IntegrationVersionItem::STATUS_SUCCESS)
            ->where('hash_date_time', '>', $oldHashDateTime);


        return $query->count();
    }


    /**
     * @param array $values
     * @param array $uniqueBy
     * @return IntegrationVersionItemRepositoryInterface
     */
    public function multiCreateOrUpdate(array $values, array $uniqueBy = ['id']): IntegrationVersionItemRepositoryInterface
    {
        foreach (array_chunk($values, 500) as $chunk) {
            $this->getModel()->upsert($chunk, $uniqueBy);
        }

        return $this;
    }

    /**
     * @param array $ids
     * @return IntegrationVersionItemRepositoryInterface
     */
    public function deleteByIds(array $ids): IntegrationVersionItemRepositoryInterface
    {
        $this->deleteWhere(['id' => ['id', 'IN', $ids]]);

        return $this;
    }
    /**
     * @param array $values
     * @param int $parentId
     * @return IntegrationVersionItemRepositoryInterface
     */
    public function updateAll(array $values, int $parentId): IntegrationVersionItemRepositoryInterface
    {
        $lastId = 0;

        do {
            $updatedRows = $this->getModel()
                ->where('parent_id', '=', $parentId)
                ->where('id', '>', $lastId)
                ->orderBy('id')
                ->limit(1000)
                ->get(['id']);

            if ($updatedRows->isEmpty()) {
                break;
            }

            $ids = $updatedRows->pluck('id')->toArray();

            $this->getModel()
                ->whereIn('id', $ids)
                ->update($values);

            $lastId = max($ids);
        } while (count($ids) === 1000);

        return $this;
    }

    public function setStatusDeletedIfNotSuccess(int $parentId, string $identityValue = ''): IntegrationVersionItemRepositoryInterface
    {
        do {
            $queryBuilder = $this->getModel()
                ->where('parent_id', '=', $parentId)
                ->where('status', '!=', IntegrationVersionItemInterface::STATUS_SUCCESS);

            if($identityValue) {
                $queryBuilder->where('identity_value', '=', $identityValue);
            }

            $updated = $queryBuilder
                ->limit(1000)
                ->update([
                    'status' => IntegrationVersionItemInterface::STATUS_DELETED
                ]);
        } while ($updated > 0);


        return $this;
    }


    public function getDeletedIdentities(int $parentId, array $identitiesForCheck, string $identityColumn): array
    {
        return array_diff($identitiesForCheck, $this->findWhere([
            'parent_id' => $parentId,
            $identityColumn => [
                $identityColumn, 'IN', $identitiesForCheck
            ]
        ])->pluck($identityColumn)->toArray());
    }

    public function getItemsWithDeletedStatus(): iterable
    {
        /**
         * @var $model IntegrationVersionItem
         */
        $model = $this->getModel();
        $page = 1;
        while(true) {
            $items = $model->query()
                ->where('status', IntegrationVersionItemInterface::STATUS_DELETED)
                ->forPage($page++, 10000)
                ->get();
            if(!$items->count()) break;

            yield $items;
        }
    }
}

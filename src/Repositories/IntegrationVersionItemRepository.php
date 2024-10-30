<?php

namespace IntegrationHelper\IntegrationVersionLaravel\Repositories;

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
     * @param string $updatedAt
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getIdentitiesForNewestVersions(int $parentId, string $oldExternalHash, string $updatedAt, int $page = 1, int $limit = 10000): iterable
    {
        /**
         * @var $model \IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersionItem
         */
        $model = $this->getModel();
        $page = ($page - 1);
        $offset = $page <= 0 ? 0 : $page * $limit;
        return $model
            ->where('parent_id', '=', $parentId)
            ->where('version_hash', '!=', $oldExternalHash)
            ->where('updated_at', '>', $updatedAt)
            ->limit($limit)
            ->offset($offset);
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
        $this->deleteWhere(['id' => $ids]);

        return $this;
    }

    public function updateAll(array $values): IntegrationVersionItemRepositoryInterface
    {
        $this->getModel()->update($values);

        return $this;
    }

    public function setStatusDeletedIfNotSuccess(int $parentId): IntegrationVersionItemRepositoryInterface
    {
        $this->getModel()
            ->where('parent_id', '=', $parentId)
            ->where('status', '!=', IntegrationVersionItemInterface::STATUS_SUCCESS)
            ->update([
            'status' => IntegrationVersionItemInterface::STATUS_DELETED
        ]);

        return $this;
    }
}
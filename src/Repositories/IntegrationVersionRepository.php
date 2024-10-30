<?php

namespace IntegrationHelper\IntegrationVersionLaravel\Repositories;

use IntegrationHelper\IntegrationVersion\Model\IntegrationVersion;
use Webkul\Core\Eloquent\Repository;
use IntegrationHelper\IntegrationVersion\Model\IntegrationVersionInterface;
use IntegrationHelper\IntegrationVersion\Repository\IntegrationVersionRepositoryInterface;

/**
 *
 */
class IntegrationVersionRepository extends Repository implements IntegrationVersionRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return 'IntegrationHelper\IntegrationVersionLaravel\Contracts\IntegrationVersion';
    }

    /**
     * @return array|IntegrationVersionInterface[]
     */
    public function getItems(): iterable
    {
        return $this->all();
    }

    /**
     * @param string $source
     * @return IntegrationVersionInterface
     */
    public function getItemBySource(string $source): IntegrationVersionInterface
    {
        return $this->findOneWhere(['source' => $source]) ?: new IntegrationVersion();
    }

    /**
     * @param int $id
     * @return IntegrationVersionInterface
     */
    public function getItemById(int $id): IntegrationVersionInterface
    {
        return $this->find($id) ?: new IntegrationVersion();
    }

    /**
     * @param IntegrationVersionInterface $item
     * @return IntegrationVersionInterface
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateItem(IntegrationVersionInterface $item): IntegrationVersionInterface
    {
        $item->setChecksumColumns($item->getChecksumColumns());

        $this->update($item->getAttributes(), $item->getId());

        return $item;
    }

    /**
     * @param array $data
     * @return IntegrationVersionInterface
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function createItem(array $data): IntegrationVersionInterface
    {
        $columns = $data['checksum_columns'] ?? [];
        $data['checksum_columns'] = is_array($columns) ? json_encode($columns) : '';

        return $this->create($data);
    }
}

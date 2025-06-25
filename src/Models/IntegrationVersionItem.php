<?php

namespace IntegrationHelper\IntegrationVersionLaravel\Models;

use Illuminate\Database\Eloquent\Model;
use IntegrationHelper\IntegrationVersion\Model\IntegrationVersionItemInterface;

class IntegrationVersionItem
    extends Model
    implements \IntegrationHelper\IntegrationVersionLaravel\Contracts\IntegrationVersionItem, IntegrationVersionItemInterface
{
    public const TABLE = 'integration_version_item';

    protected $fillable = [
        'parent_id',
        'identity_value',
        'version_hash',
        'checksum',
        'status',
        'hash_date_time'
    ];

    public $table = self::TABLE;

    /**
     * @return int
     */
    public function getIdValue(): int
    {
        return (int) $this->id;
    }

    /**
     * @param int $id
     * @return IntegrationVersionItemInterface
     */
    public function setIdValue(int $id): IntegrationVersionItemInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return (int) $this->parent_id;
    }

    /**
     * @param int $parentId
     * @return IntegrationVersionItemInterface
     */
    public function setParentId(int $parentId): IntegrationVersionItemInterface
    {
        $this->parent_id = $parentId;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersionHash(): string
    {
        return $this->version_hash;
    }

    /**
     * @param string $versionHash
     * @return IntegrationVersionItemInterface
     */
    public function setVersionHash(string $versionHash, string $hashDateTime): IntegrationVersionItemInterface
    {
        $this->version_hash = $versionHash;
        $this->setHashDateTime($hashDateTime);

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentityValue(): string
    {
        return $this->identity_value;
    }

    /**
     * @param string $identityValue
     * @return IntegrationVersionItemInterface
     */
    public function setIdentityValue(string $identityValue): IntegrationVersionItemInterface
    {
        $this->identity_value = $identityValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getChecksum(): string
    {
        return (string) $this->checksum;
    }

    /**
     * @param string $checksum
     * @return IntegrationVersionItemInterface
     */
    public function setChecksum(string $checksum): IntegrationVersionItemInterface
    {
        $this->checksum = $checksum;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAtValue(): string
    {
        return $this->updated_at;
    }

    /**
     * @param string $updatedAt
     * @return IntegrationVersionItemInterface
     */
    public function setUpdatedAtValue(string $updatedAt): IntegrationVersionItemInterface
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return IntegrationVersionItemInterface
     */
    public function setStatus(string $status): IntegrationVersionItemInterface
    {
        $this->status = $status;

        return $this;
    }

    public function getHashDateTime(): string
    {
        return $this->hash_date_time ?: '';
    }

    protected function setHashDateTime(string $hashDateTime): IntegrationVersionItemInterface
    {
        $this->hash_date_time = $hashDateTime;

        return $this;
    }
}

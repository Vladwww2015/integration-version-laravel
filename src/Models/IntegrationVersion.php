<?php

namespace IntegrationHelper\IntegrationVersionLaravel\Models;

use Illuminate\Database\Eloquent\Model;
use IntegrationHelper\IntegrationVersion\Model\IntegrationVersionInterface;

class IntegrationVersion
    extends Model
    implements \IntegrationHelper\IntegrationVersionLaravel\Contracts\IntegrationVersion, IntegrationVersionInterface
{
    public const TABLE = 'integration_version';

    public $table = self::TABLE;

    protected $fillable = [
        'identity_column',
        'checksum_columns',
        'hash',
        'source',
        'status',
        'table_name'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): IntegrationVersionInterface
    {
        $this->id = $id;

        return $this;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): IntegrationVersionInterface
    {
        $this->source = $source;

        return $this;
    }

    public function getTableName(): string
    {
        return $this->table_name;
    }

    public function setTableName(string $tableName): IntegrationVersionInterface
    {
        $this->table_name = $tableName;

        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): IntegrationVersionInterface
    {
        $this->hash = $hash;

        return $this;
    }

    public function getIdentityColumn(): string
    {
        return $this->identity_column;
    }

    public function setIdentityColumn(string $identityColumn): IntegrationVersionInterface
    {
        $this->identity_column = $identityColumn;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): IntegrationVersionInterface
    {
        $this->status = $status;

        return $this;
    }

    public function getUpdatedAtValue(): string
    {
        return $this->updated_at;
    }

    public function setUpdatedAtValue(string $updatedAt): IntegrationVersionInterface
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    public function getChecksumColumns(): array
    {
        if(is_array($this->checksum_columns)) return $this->checksum_columns;

        return json_decode($this->checksum_columns, true) ?? [];
    }

    public function setChecksumColumns(array $columns): IntegrationVersionInterface
    {
        $this->checksum_columns = json_encode($columns);

        return $this;
    }
}

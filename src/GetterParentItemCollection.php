<?php

namespace IntegrationHelper\IntegrationVersionLaravel;

use Illuminate\Support\Facades\DB;
use IntegrationHelper\IntegrationVersion\GetterParentItemCollectionInterface;

class GetterParentItemCollection implements GetterParentItemCollectionInterface
{
    public function getItems(string $table, string $identityColumn, array $columns, int $page = 1, int $limit = 50000): iterable
    {
        $offset = (($page - 1) <= 0 ? 0 : $page - 1) * $limit;

        $queryBuilder = DB::connection()->table($table);

        if(function_exists('core')) { //TODO
            try {
                $core = core();
                if(is_object($core) && method_exists($core, 'getProductSourceGroupPriceAlgorithm')) {
                    $queryBuilder->orderBy('product_id')
                        ->groupBy('product_id', 'customer_group_id');
                }
            } catch (\Throwable $e) {}
        }

        if($offset) {
            $queryBuilder->offset($offset);
        }
        $queryBuilder->limit($limit);

        return $queryBuilder->get([...$columns, $identityColumn]);
    }

    public function getItem(string $table, string $identityColumn, mixed $identityValue, array $columns): iterable
    {
        $queryBuilder = DB::connection()->table($table);

        $queryBuilder->where($identityColumn, '=', $identityValue);

        return $queryBuilder->get([...$columns, $identityColumn]);
    }
}

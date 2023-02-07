<?php

namespace App\Contract\Repository;

use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Product\Listing;

interface ProductRepositoryInterface
{
    /**
     * @param int $id
     * @return Product|null
     */
    public function getById(int $id): Product|null;


    /**
     * @param bool $withDeleted
     * @return Listing
     */
    public function getAll(bool $withDeleted = false): Product\Listing;

    /**
     * @param array $options
     * @return Product
     */
    public function create(array $params): Product;

    /**
     * @param $key
     * @return Product|null
     */
    public function getByKey($key): Product|null;

}

<?php

namespace App\Contract\Repository;

use App\Model\DataObject\Product;

interface ReviewRepositoryInterface
{

    public function create(Product $product, array $params): Product;

}

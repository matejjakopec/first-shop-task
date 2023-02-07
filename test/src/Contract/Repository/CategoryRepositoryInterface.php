<?php

namespace App\Contract\Repository;

use Pimcore\Model\DataObject\Category;

interface CategoryRepositoryInterface
{
    public function getAll(): Category\Listing;

    public function getFormArray(): array;

}

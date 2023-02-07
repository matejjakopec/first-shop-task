<?php

namespace App\Repository;

use App\Contract\Repository\CategoryRepositoryInterface;
use Pimcore\Model\DataObject\Category;

class CategoryRepository implements CategoryRepositoryInterface
{

    public function getAll(): Category\Listing
    {
        return new Category\Listing();
    }

    public function getFormArray(): array
    {
        $listing = $this->getAll();
        $output = [];
        foreach ($listing->getData() as $item){
            $output[$item->getName()] = $item->getId();
        }

        return $output;
    }
}

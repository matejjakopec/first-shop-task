<?php

namespace App\Repository;

use App\Contract\Repository\TagRepositoryInterface;
use Pimcore\Model\DataObject\Tag;

class TagRepository implements TagRepositoryInterface
{

    public function getAll(): Tag\Listing
    {
        return new Tag\Listing();
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

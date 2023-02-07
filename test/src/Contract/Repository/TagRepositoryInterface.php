<?php

namespace App\Contract\Repository;

use Pimcore\Model\DataObject\Tag;

interface TagRepositoryInterface
{

    public function getAll(): Tag\Listing;

    public function getFormArray(): array;

}

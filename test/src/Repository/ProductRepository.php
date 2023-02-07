<?php

namespace App\Repository;

use App\Contract\Repository\ProductRepositoryInterface;
use Carbon\Carbon;
use Pimcore\Model\DataObject\Category;
use Pimcore\Model\DataObject\Data\UrlSlug;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Service;

class ProductRepository implements ProductRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function getById(int $id): Product|null
    {
        return Product::getById($id);
    }


    /**
     * @inheritDoc
     */
    public function getAll(bool $withDeleted = false): Product\Listing
    {
        $listing = new Product\Listing();
        if(!$withDeleted){
            $listing->onCreateQueryBuilder(
                function (\Doctrine\DBAL\Query\QueryBuilder $queryBuilder){
                    $queryBuilder
                        ->join('object_localized_product_en', 'object_product', 'object',
                            'object_localized_product_en.oo_id = object.oo_id');
                });
            $listing->addConditionParam('object.deletedAt IS NULL');
        }

        return $listing;
    }


    /**
     * @inheritDoc
     */
    public function create(array $params): Product
    {
        $params['createdAt'] = Carbon::now();
        $params['deletedAt'] = null;
        $params['published'] = true;
        $key = Service::getValidKey($this->getKeyFromName($params['name']), 'object');
        $params['key'] = $key;
        $params['urlSlug'] = [new UrlSlug('/' . $key)];
        $folder = Service::createFolderByPath('/Products/' . $params['category']->getName());
        $params['parentId'] = $folder->getId();
        $product = Product::create($params);
        $product->save();

        return $product;
    }


    /**
     * @inheritDoc
     */
    public function getByKey($key): Product|null{
        $product = $this->getAll(true);
        $product->onCreateQueryBuilder(
            function (\Doctrine\DBAL\Query\QueryBuilder $queryBuilder){
                $queryBuilder
                    ->join('object_localized_product_en', 'objects', 'objects',
                        'object_localized_product_en.oo_id = objects.o_id');
            }
        );
        $product->addConditionParam("objects.o_key = :key", ['key' => $key]);
        if(1 !== $product->count()){
            return null;
        }

        return $product->current();
    }

    private function getKeyFromName($key){
        $newKey = strtolower($key);
        $newKey = str_replace(' ', '-', $newKey);
        $listing = $this->getAll(true);
        $count = $listing->addConditionParam("o_key like '{$newKey}%'");
        $count = $listing->addConditionParam("o_key not like '{$newKey}-%-%'")->count();
        $suffix = $count >= 1 ? '-' . $count + 1 : null;
        return $newKey . $suffix;
    }
}

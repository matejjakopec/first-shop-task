<?php

namespace App\Website\LinkGenerator;




use Pimcore\Model\DataObject\ClassDefinition\LinkGeneratorInterface;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Product;

class ProductLinkGenerator implements LinkGeneratorInterface
{
    /**
     * @param Concrete $object
     * @param array $params
     *
     * @return string
     */
    public function generate(Concrete $object, array $params = []): string
    {
        if (!($object instanceof Product)) {
            throw new \InvalidArgumentException('Given object is not a Product');
        }

        return $this->doGenerate($object, $params);
    }

    /**
     * @param Product $object
     * @param array $params
     * @return string
     */
    public function generateWithMockup(Product $object, array $params = []): string {
        return $this->doGenerate($object, $params);
    }

    /**
     * @param Product $object
     * @param array $params
     * @return string
     */
    protected function doGenerate($object, $params): string
    {
        return "/product/{$object->getCategory()->getKey()}/{$object->getKey()}";
    }
}

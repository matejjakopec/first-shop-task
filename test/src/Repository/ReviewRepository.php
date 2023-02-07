<?php

namespace App\Repository;

use App\Contract\Repository\ReviewRepositoryInterface;
use App\Model\DataObject\Product;
use Pimcore\Model\DataObject\Fieldcollection;
use Pimcore\Model\DataObject\Fieldcollection\Data\Review;

class ReviewRepository implements ReviewRepositoryInterface
{

    public function create(Product|null $product, array $params): Product
    {
        $reviews = $product->getReviews();
        if(!$reviews){
            $reviews = new Fieldcollection();
        }
        $review = new Review();
        $review->setAuthor($params['author']);
        $review->setText($params['text']);
        $review->setRating($params['rating']);

        $reviews->add($review);
        $product->setReviews($reviews);
        $product->save();

        return $product;
    }
}

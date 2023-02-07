<?php

namespace App\Model\DataObject;

class Product extends \Pimcore\Model\DataObject\Product
{

    public function getAverageReview(){
        $total = 0;
        $reviews = $this->getReviews();
        if(!$reviews){
            return false;
        }
        $reviews = $reviews->getItems();
        foreach ($reviews as $review){
            $total += $review->getRating();
        }

        return $total/count($reviews);
    }

}

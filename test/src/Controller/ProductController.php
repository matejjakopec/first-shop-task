<?php

namespace App\Controller;

use App\Contract\Repository\ProductRepositoryInterface;
use App\Contract\Repository\ReviewRepositoryInterface;
use App\Form\Type\ProductType;
use App\Form\Type\ReviewType;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends FrontendController
{

    /**
     * @Route("/product/create", name="product_create")
     */
    public function createAction(
        Request $request,
        ProductRepositoryInterface $repository
    ){
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            try {
                $repository->create($form->getData());
            }catch (\Exception $exception){
                return new JsonResponse('failure');
            }

            return new JsonResponse('success');
        }

        return $this->render('product/default.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/product/list", name="product_list")
     */
    public function listAction(
        ProductRepositoryInterface $repository,
        Request $request
    ){
        $category = $request->get('category');
        $tags = $request->get('tags');
        $withDeleted = $request->get('withDeleted', 0);
        $withDeleted = $withDeleted == 1;
        $listing = $repository->getAll($withDeleted);

        $listing->onCreateQueryBuilder(
            function (\Doctrine\DBAL\Query\QueryBuilder $queryBuilder){
                $queryBuilder
                    ->join('object_localized_product_en', 'object_product', 'object',
                        'object_localized_product_en.oo_id = object.oo_id');
        });

        if($category){
            $listing->addConditionParam("object.category__id = :category", ['category' => $category]);
        }

        if($tags){
            foreach ($tags as $tag){
                $listing->addConditionParam("object.tags like '%,{$tag},%'");
            }
        }

        return $this->render('product/list.html.twig',[
            'products' => $listing->getData(),
        ]);
    }


    /**
     * @Route("/product/{category}/{name}", name="product_details")
     */
    public function detailsAction(
        string                     $category,
        string                     $name,
        ProductRepositoryInterface $productRepository,
        ReviewRepositoryInterface  $reviewRepository,
        Request $request
    ){
        $product = $productRepository->getByKey($name);
        $form = $this->createForm(ReviewType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            try {
                $reviewRepository->create($product, $form->getData());
            }catch (\Exception $exception){
                return new JsonResponse('failure');
            }

            return new JsonResponse('success');
        }


        return $this->render('product/details.html.twig', [
            'product' => $product,
            'reviews' => $product->getReviews() ? $product->getReviews()->getItems() : null,
            'connectivity' => $product->getAdditionalData()->getConnectivity(),
            'dimensions' => $product->getAdditionalData()->getDimensions(),
            'form' => $form->createView(),
        ]);
    }

}

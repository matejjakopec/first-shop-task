<?php

namespace App\Form\Type;

use App\Contract\Repository\CategoryRepositoryInterface;
use App\Contract\Repository\TagRepositoryInterface;
use Pimcore\Model\DataObject\Category;
use Pimcore\Model\DataObject\Data\QuantityValue;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\QuantityValue\Unit;
use Pimcore\Model\DataObject\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Positive;

class ProductType extends AbstractType implements DataMapperInterface
{
    private TagRepositoryInterface $tagRepository;

    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(TagRepositoryInterface $tagRepository, CategoryRepositoryInterface $categoryRepository){
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('category', ChoiceType::class, [
                'choices' => $this->categoryRepository->getFormArray(),
            ])
            ->add('tags', ChoiceType::class, [
                'choices' => $this->tagRepository->getFormArray(),
                'multiple' => true,
                'required' => false
            ])
            ->add('price', NumberType::class, [
                'constraints' => [new Positive()]
            ])
            ->add('currency', ChoiceType::class,[
                'choices' => ['kn' => 'kn', 'EUR' => 'EUR']
            ])
            ->add('submit', SubmitType::class)
            ->setDataMapper($this);
    }

    public function mapDataToForms($viewData, \Traversable $forms)
    {
        if (null === $viewData) {
            return;
        }

        if (!$viewData instanceof Product) {
            throw new UnexpectedTypeException($viewData, Product::class);
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $forms['name']->setData($viewData->getName());
        $forms['category']->setData($viewData->getCategory()->getId());
        $tags = [];
        foreach ($viewData->getTags() as $tag){
            $tags[] = $tag->getId();
        }
        $forms['tags']->setData($tags);
    }

    public function mapFormsToData(\Traversable $forms, &$viewData)
    {
        $forms = iterator_to_array($forms);

        $viewData['name'] = $forms['name']->getData();
        $viewData['category'] = Category::getById($forms['category']->getData());
        $tags = [];
        foreach ($forms['tags']->getNormData() as $tag){
            $tags[] = Tag::getById($tag);
        }

        $viewData['tags'] = $tags;
        $viewData['price'] = new QuantityValue(
            $forms['price']->getData(),
            Unit::getByAbbreviation($forms['currency']->getData())
        );
    }
}

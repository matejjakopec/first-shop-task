<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class ReviewType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('author', TextType::class, [
            'constraints' => [new Length(['min' => 5])]
        ])
            ->add('text', TextType::class, [
                'constraints' => [new Length(['min' => 10])]
            ])
            ->add('rating', IntegerType::class, [
                'constraints' => [new Range(['min' => 1, 'max' => 5])]
            ])
            ->add('submit', SubmitType::class);
    }

}

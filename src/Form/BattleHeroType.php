<?php

namespace App\Form;

use App\Entity\Hero;
use App\Form\DataTransformer\HeroToNumberTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class BattleHeroType extends AbstractType
{
    /**
     * @var HeroToNumberTransformer
     */
    private $transformer;

    /**
     * BattleHeroType constructor.
     *
     * @param HeroToNumberTransformer $transformer
     */
    public function __construct(HeroToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', EntityType::class, [
                'class' => Hero::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'Choice',
                'attr' => ['required' => true, 'class' => 'form-control'],
            ])

            ->add(
                'attack',
                IntegerType::class,
                [
                    'label' => 'Attack',
                    'attr' => ['required' => true, 'class' => 'form-control', 'min' => 0],
                    'data' => 0,
                ]
            )
            ->add(
                'abilityAttack',
                IntegerType::class,
                [
                    'label' => 'Ability to attack',
                    'attr' => ['required' => true, 'class' => 'form-control', 'min' => 0],
                    'data' => 0,
                ]
            )
            ->add(
                'defence',
                IntegerType::class,
                [
                    'label' => 'Defence',
                    'attr' => ['required' => true, 'class' => 'form-control', 'min' => 0],
                    'data' => 0,
                ]
            )
            ->add(
                'abilityDefence',
                IntegerType::class,
                [
                    'label' => 'Ability to defence',
                    'attr' => ['required' => true, 'class' => 'form-control', 'min' => 0],
                    'data' => 0,
                ]
            );

        $builder->get('name')->addModelTransformer($this->transformer);
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Hero::class,
            ]);
    }
}

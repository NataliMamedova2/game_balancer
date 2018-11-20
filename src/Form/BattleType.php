<?php

namespace App\Form;

use App\Entity\Battle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BattleType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstHero',  BattleHeroType::class, ['label' => 'Attacker Hero'])
            ->add('secondHero', BattleHeroType::class, ['label' => 'Defender Hero'])
            ->add(
                'amountDraw',
                IntegerType::class,
                [
                    'label' => 'Amount of draw coins',
                    'data' => '1',
                    'attr' => [
                        'required' => true,
                        'class' => 'form-control',
                        'min' => 1,
                        'max' => 100000,
                    ],
                    'constraints' => [new GreaterThan(['value' => 0])],
                ]
            )
            ->add(
                    'amount',
                    IntegerType::class,
                    [
                        'label' => 'Amount battles',
                        'data' => '1',
                        'attr' => [
                            'required' => true,
                            'class' => 'form-control',
                            'min' => 1,
                            'max' => 100000,
                        ],
                        'constraints' => [new GreaterThan(['value' => 0])],
                    ]
            )
            ->add('calculateResult', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('saveResultToFile', SubmitType::class, [
                 'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Battle::class,
        ]);
    }
}

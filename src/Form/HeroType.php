<?php

namespace App\Form;

use App\Entity\Hero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeroType extends AbstractType
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
            ->add(
            'name',
            null,
                [
                    'label' => 'Name',
                    'attr' => ['autofocus' => true, 'required' => true, 'class' => 'form-control'],
                ]
            )
            ->add(
                'attack',
                IntegerType::class,
                [
                    'label' => 'Attack',
                    'attr' => ['required' => true, 'class' => 'form-control', 'min' => 0],
                    'data' => 1,
                ]
            )
            ->add(
                'abilityAttack',
                IntegerType::class,
                [
                    'label' => 'Ability to attack',
                    'attr' => ['required' => true, 'class' => 'form-control', 'min' => 0],
                    'data' => 1,
                ]
            )
            ->add(
                'defence',
                IntegerType::class,
                [
                    'label' => 'Defence',
                    'attr' => ['required' => true, 'class' => 'form-control', 'min' => 0],
                    'data' => 1,
                ]
            )
            ->add(
                'abilityDefence',
                IntegerType::class,
                [
                    'label' => 'Ability to defence',
                    'attr' => ['required' => true, 'class' => 'form-control', 'min' => 0],
                    'data' => 1,
                ]
            );
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hero::class,
        ]);
    }
}

<?php

namespace App\Form\DataTransformer;

use App\Entity\Hero;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class HeroToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (hero) to a string (number).
     *
     * @param Hero|null $issue
     *
     * @return string
     */
    public function transform($hero)
    {
        if ($hero === null) {
            return '';
        }

        return $hero;
    }

    /**
     * Transforms a string (number) to an object (hero).
     *
     * @param string $heroNumber
     *
     * @return Hero|null
     *
     * @throws TransformationFailedException if object (hero) is not found
     */
    public function reverseTransform($heroNumber)
    {
        if (!$heroNumber) {
            return;
        }

        $hero = $this->entityManager
            ->getRepository(Hero::class)
            // query for the issue with this id
            ->find($heroNumber)
        ;

        if ($hero === null) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $heroNumber
            ));
        }

        return $hero->getName();
    }
}

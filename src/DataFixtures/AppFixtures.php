<?php

namespace App\DataFixtures;

use App\Entity\Hero;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getHeroData() as [$name, $attack, $abilityAttack, $defence, $abilityDefence]) {
            $hero = new Hero();

            $hero->setName($name);
            $hero->setAttack($attack);
            $hero->setDefence($defence);
            $hero->setAbilityDefence($abilityDefence);

            $manager->persist($hero);
        }
        $manager->flush();
    }

    /**
     * @return array
     */
    private function getHeroData(): array
    {
        return[
            ['Hero1', 5, 1, 0, 0],
            ['Hero2', 0, 0, 5, 1],
            ['Hero3', 3, 2, 0, 0],
            ['Hero4', 0, 0, 4, 2],
        ];
    }
}

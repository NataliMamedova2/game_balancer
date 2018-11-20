<?php

namespace App\Entity;

use App\Interfaces\CharacterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HeroRepository")
 */
class Hero implements CharacterInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $attack;

    /**
     * @ORM\Column(type="smallint")
     */
    private $abilityAttack;

    /**
     * @ORM\Column(type="smallint")
     */
    private $defence;

    /**
     * @ORM\Column(type="smallint")
     */
    private $abilityDefence;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Battle", mappedBy="firstHero")
     */
    private $battles;

    /**
     * Hero constructor.
     */
    public function __construct()
    {
        $this->battles = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Hero
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAttack(): ?int
    {
        return $this->attack;
    }

    /**
     * @param int $attack
     *
     * @return Hero
     */
    public function setAttack(int $attack): self
    {
        $this->attack = $attack;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAbilityAttack(): ?int
    {
        return $this->abilityAttack;
    }

    /**
     * @param int $abilityAttack
     *
     * @return Hero
     */
    public function setAbilityAttack(int $abilityAttack): self
    {
        $this->abilityAttack = $abilityAttack;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDefence(): ?int
    {
        return $this->defence;
    }

    /**
     * @param int $defence
     *
     * @return Hero
     */
    public function setDefence(int $defence): self
    {
        $this->defence = $defence;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAbilityDefence(): ?int
    {
        return $this->abilityDefence;
    }

    /**
     * @param int $abilityDefence
     *
     * @return Hero
     */
    public function setAbilityDefence(int $abilityDefence): self
    {
        $this->abilityDefence = $abilityDefence;

        return $this;
    }

    /**
     * @param array $hero
     * @param Hero  $heroEntity
     *
     * @return Hero
     */
    public function setProperties(array $hero, $heroEntity): self
    {
        $heroEntity->setAttack($hero['attack']);
        $heroEntity->setAbilityAttack($hero['abilityAttack']);
        $heroEntity->setDefence($hero['defence']);
        $heroEntity->setAbilityDefence($hero['abilityDefence']);

        return $heroEntity;
    }

    /**
     * @return array
     */
    public function getAllProperties(): array
    {
        return [
            'attack' => $this->getAttack(),
            'abilityAttack' => $this->getAbilityAttack(),
            'defence' => $this->getDefence(),
            'abilityDefence' => $this->getAbilityDefence(),
        ];
    }

    /**
     * @return Collection|Battle[]
     */
    public function getBattles(): Collection
    {
        return $this->battles;
    }

    /**
     * @param Battle $battle
     *
     * @return Hero
     */
    public function addBattle(Battle $battle): self
    {
        if (!$this->battles->contains($battle)) {
            $this->battles[] = $battle;
            $battle->setFirstHero($this);
        }

        return $this;
    }

    /**
     * @param Battle $battle
     *
     * @return Hero
     */
    public function removeBattle(Battle $battle): self
    {
        if ($this->battles->contains($battle)) {
            $this->battles->removeElement($battle);
            // set the owning side to null (unless already changed)
            if ($battle->getFirstHero() === $this) {
                $battle->setFirstHero(null);
            }
        }

        return $this;
    }
}

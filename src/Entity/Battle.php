<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BattleRepository")
 */
class Battle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hero", inversedBy="battles")
     */
    private $firstHero;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hero", inversedBy="battles")
     */
    private $secondHero;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $result;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountDraw;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Hero|null
     */
    public function getFirstHero(): ?Hero
    {
        return $this->firstHero;
    }

    /**
     * @param Hero|null $firstHero
     *
     * @return Battle
     */
    public function setFirstHero(?Hero $firstHero): self
    {
        $this->firstHero = $firstHero;

        return $this;
    }

    /**
     * @return Hero|null
     */
    public function getSecondHero(): ?Hero
    {
        return $this->secondHero;
    }

    /**
     * @param Hero|null $secondHero
     *
     * @return Battle
     */
    public function setSecondHero(?Hero $secondHero): self
    {
        $this->secondHero = $secondHero;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getResult(): ?string
    {
        return $this->result;
    }

    /**
     * @param string $result
     *
     * @return Battle
     */
    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @return Battle
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAmountDraw(): ?int
    {
        return $this->amountDraw;
    }

    /**
     * @param int $amountDraw
     *
     * @return Battle
     */
    public function setAmountDraw(int $amountDraw): self
    {
        $this->amountDraw = $amountDraw;

        return $this;
    }
}

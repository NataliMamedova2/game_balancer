<?php

namespace App\Dto;

use App\Interfaces\CharacterInterface;

class CoinDTO
{
    /**
     * @var int
     */
    private $amountAttackCoins;

    /**
     * @var int
     */
    private $amountAbilityAttackCoins;

    /**
     * @var int
     */
    private $amountDefenceCoins;

    /**
     * @var int
     */
    private $amountAbilityDefenceCoins;

    /**
     * @var int
     */
    private $amountDrawCoins;

    /**
     * CoinDTO constructor.
     *
     * @param CharacterInterface $attacker
     * @param CharacterInterface $defender
     * @param int                $amountDraw
     */
    public function __construct(CharacterInterface $attacker, CharacterInterface $defender, int $amountDraw)
    {
        $this->amountAttackCoins = $attacker->getAttack();
        $this->amountAbilityAttackCoins = $attacker->getAbilityAttack();

        $this->amountDefenceCoins = $defender->getDefence();
        $this->amountAbilityDefenceCoins = $defender->getAbilityDefence();

        $this->amountDrawCoins = $amountDraw;
    }

    /**
     * @return int
     */
    public function getAmountAttackCoins(): int
    {
        return $this->amountAttackCoins;
    }

    /**
     * @return int
     */
    public function getAmountAbilityAttackCoins(): int
    {
        return $this->amountAbilityAttackCoins;
    }

    /**
     * @return int
     */
    public function getAmountDefenceCoins(): int
    {
        return $this->amountDefenceCoins;
    }

    /**
     * @return int
     */
    public function getAmountAbilityDefenceCoins(): int
    {
        return $this->amountAbilityDefenceCoins;
    }

    /**
     * @return int
     */
    public function getAmountDrawCoins(): int
    {
        return $this->amountDrawCoins;
    }
}

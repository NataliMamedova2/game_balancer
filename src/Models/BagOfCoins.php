<?php

namespace App\Models;

use App\Interfaces\BagOfCoinsInterface;
use App\Interfaces\CoinTypesInterface;
use App\Dto\CoinDTO;

class BagOfCoins implements BagOfCoinsInterface
{
    /**
     * @var array
     */
    private $coins = [];

    /**
     * {@inheritdoc}
     */
    public function getRandomCoin(CoinDTO $coinDTO, int $amountRandomCoins = 1): string
    {
        $this->fillBagCoins($coinDTO);

        return $this->coins[array_rand($this->coins, $amountRandomCoins)];
    }

    /**
     * clear $coins array.
     */
    public function clearBagCoins()
    {
        $this->coins = [];
    }

    /**
     * fill in Bag Coins.
     *
     * @param CoinDTO $coinDTO
     */
    protected function fillBagCoins(CoinDTO $coinDTO)
    {
        $this->clearBagCoins();

        $this->addCoinToBag($coinDTO->getAmountAttackCoins(), CoinTypesInterface::ATTACK);
        $this->addCoinToBag($coinDTO->getAmountAbilityAttackCoins(), CoinTypesInterface::ABILITY_ATTACK);

        $this->addCoinToBag($coinDTO->getAmountDefenceCoins(), CoinTypesInterface::DEFENCE);
        $this->addCoinToBag($coinDTO->getAmountAbilityDefenceCoins(), CoinTypesInterface::ABILITY_DEFENCE);

        $this->addCoinToBag($coinDTO->getAmountDrawCoins(), CoinTypesInterface::DRAW);
    }

    /**
     * @param int    $amountCoins
     * @param string $coinType
     */
    protected function addCoinToBag(int $amountCoins, string $coinType)
    {
        $this->coins = array_merge($this->coins, array_fill(0, $amountCoins, $coinType));
    }
}

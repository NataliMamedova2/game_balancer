<?php

namespace App\Interfaces;

use App\Dto\CoinDTO;

interface BagOfCoinsInterface
{
    /**
     * @param CoinDTO $coinDTO
     * @param int     $amountRandomCoins
     *
     * @return string
     */
    public function getRandomCoin(CoinDTO $coinDTO, int $amountRandomCoins = 1): string;
}

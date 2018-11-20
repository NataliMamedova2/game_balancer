<?php

namespace App\Interfaces;

interface StatisticInterface
{
    /**
     * @param string $winnerName
     */
    public function saveBattleResult(string $winnerName);

    /**
     * @return array
     */
    public function getSummaryStatistics(): array;
}

<?php

namespace App\Service;

use App\Interfaces\BagOfCoinsInterface;
use App\Interfaces\CharacterInterface;
use App\Interfaces\StatisticInterface;
use App\Interfaces\CoinTypesInterface;
use App\Dto\CoinDTO;

class GameBalancerService implements StatisticInterface
{
    /**
     * @var BagOfCoinsInterface
     */
    private $bagCoins;

    /**
     * @var CharacterInterface
     */
    private $attacker;

    /**
     * @var CharacterInterface
     */
    private $defender;

    /**
     * @var array
     */
    private $buttleResults = [];

    /**
     * @var string
     */
    private $attackAbilityLabel = 'Ability to attack';

    /**
     * @var string
     */
    private $defenceAbitilyLabel = 'Ability to defence';

    /**
     * @var string
     */
    private $attackLabel = 'Attack';

    /**
     * @var string
     */
    private $defenceLabel = 'Defence';
    /**
     * @var string
     */
    private $drawLabel = 'Draw';

    /**
     * @var int
     */
    private $amountDrawCoins;

    /**
     * GameBalancerService constructor.
     *
     * @param BagOfCoinsInterface $bagCoins
     */
    public function __construct(BagOfCoinsInterface $bagCoins)
    {
        $this->buttleResults = [
            $this->defenceLabel => 0,
            $this->defenceAbitilyLabel => 0,
            $this->attackLabel => 0,
            $this->attackAbilityLabel => 0,
            $this->drawLabel => 0,
        ];
        $this->bagCoins = $bagCoins;
    }

    /**
     * @param CharacterInterface $attacker
     * @param CharacterInterface $defender
     */
    public function setCharacters(CharacterInterface $attacker, CharacterInterface $defender)
    {
        $this->attacker = $attacker;
        $this->defender = $defender;
    }

    /**
     * @param int $amountDrawCoins
     */
    public function setAmountDrawCoins(int $amountDrawCoins)
    {
        $this->amountDrawCoins = $amountDrawCoins;
    }

    /**
     * @param CharacterInterface $attacker
     * @param CharacterInterface $defender
     * @param int                $amount
     * @param int                $amountDrawCoins
     *
     * @return array
     */
    public function runBattle(CharacterInterface $attacker, CharacterInterface $defender, int $amountDrawCoins, int $amount = 1): array
    {
        $this->attacker = $attacker;
        $this->defender = $defender;
        $this->amountDrawCoins = $amountDrawCoins;

        $this->stepByStepExecutionBattle($amount);

        return $this->getSummaryStatistics();
    }

    /**
     * {@inheritdoc}
     */
    public function getSummaryStatistics(): array
    {
        return $this->buttleResults;
    }

    /**
     * {@inheritdoc}
     */
    public function saveBattleResult(string $winnerName)
    {
        ++$this->buttleResults[$winnerName];
    }

    /**
     * @return string
     */
    public function getRandomCoin(): string
    {
        $coinDto = new CoinDTO($this->attacker, $this->defender, $this->amountDrawCoins);

        try {
            $randCoin = $this->bagCoins->getRandomCoin($coinDto);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $randCoin;
    }

    /**
     * @param string $randCoin
     *
     * @return string
     */
    protected function getWinnerByCoin(string $randCoin): string
    {
        $battleResult = [
            CoinTypesInterface::ATTACK => $this->attackLabel,
            CoinTypesInterface::DEFENCE => $this->defenceLabel,
            CoinTypesInterface::DRAW => $this->drawLabel,
            CoinTypesInterface::ABILITY_ATTACK => $this->attackAbilityLabel,
            CoinTypesInterface::ABILITY_DEFENCE => $this->defenceAbitilyLabel,
        ];

        return $battleResult[$randCoin];
    }

    /**
     * @param int $amountBattle
     */
    protected function stepByStepExecutionBattle(int $amountBattle)
    {
        for ($i = 0; $i < $amountBattle; ++$i) {
            $randCoin = $this->getRandomCoin();
            $winnerName = $this->getWinnerByCoin($randCoin);

            $this->saveBattleResult($winnerName);
        }
    }
}

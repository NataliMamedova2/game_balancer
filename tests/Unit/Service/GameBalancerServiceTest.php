<?php

namespace App\Tests\Service;

use App\Interfaces\CoinTypesInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\GameBalancerService;
use App\Models\BagOfCoins;
use App\Tests\Helpers\PHPUnitHelper;

class GameBalancerServiceTest extends WebTestCase
{
    /**
     * @var GameBalancerService
     */
    private $gameBalancerService;

    /**
     * @var array
     */
    private $buttleResults = [];

    /**
     * @var MockObject
     */
    private $attackerMock;

    /**
     * @var MockObject
     */
    private $defenderMock;

    /**
     * @var array
     */
    private $bagCoins = [
        CoinTypesInterface::ATTACK,
        CoinTypesInterface::ABILITY_ATTACK,
        CoinTypesInterface::ABILITY_DEFENCE,
        CoinTypesInterface::DEFENCE,
        CoinTypesInterface::DRAW,
    ];

    protected function setUp()
    {
        $bagCoins = new BagOfCoins();
        $this->gameBalancerService = new GameBalancerService($bagCoins);

        $this->buttleResults = [
           'Attack' => 0,
           'Defence' => 0,
           'Ability to attack' => 0,
           'Ability to defence' => 0,
           'Draw' => 0,
        ];
        $this->attackerMock = $this->getMockObject(['getAttack' => 5, 'getAbilityAttack' => 1]);
        $this->defenderMock = $this->getMockObject(['getDefence' => 3, 'getAbilityDefence' => 2]);
    }

    protected function tearDown()
    {
        $this->gameBalancerService = null;
        $this->attackerMock = null;
        $this->defenderMock = null;
        $this->buttleResults = [];
    }

    public function testGetWinnerByCoin()
    {
        $testCoin = PHPUnitHelper::callPrivateMethodWithArgs($this->gameBalancerService, 'getWinnerByCoin', [CoinTypesInterface::ATTACK]);

        $this->assertEquals('Attack', $testCoin);
    }

    public function testSaveBattleResult()
    {
        $this->gameBalancerService->saveBattleResult('Attack');
        $buttleResults = $this->gameBalancerService->getSummaryStatistics();

        $this->assertEquals(1, $buttleResults['Attack']);
    }

    public function testGetSummaryStatictics()
    {
        $buttleResults = $this->gameBalancerService->getSummaryStatistics();

        $this->assertEquals($this->buttleResults, $buttleResults);
    }

    public function testCheckBattleResults()
    {
        $battleResults = $this->gameBalancerService->runBattle($this->attackerMock, $this->defenderMock, 100);

        $this->assertEquals($battleResults, $this->gameBalancerService->getSummaryStatistics());
    }

    public function testGetRandomCoin()
    {
        $this->gameBalancerService->setCharacters($this->attackerMock, $this->defenderMock);
        $this->gameBalancerService->setAmountDrawCoins(2);
        $randCoin = $this->gameBalancerService->getRandomCoin();

        $this->assertContains($randCoin, $this->bagCoins);
    }

    /**
     * @param $mockParams
     *
     * @return MockObject
     */
    private function getMockObject($mockParams)
    {
        return PHPUnitHelper::builderCharacterMock($mockParams, $this);
    }
}

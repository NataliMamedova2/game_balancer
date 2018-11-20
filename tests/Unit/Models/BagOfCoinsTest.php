<?php

namespace App\Tests\Models;

use App\Dto\CoinDTO;
use App\Models\BagOfCoins;
use App\Interfaces\CoinTypesInterface;
use App\Tests\Helpers\PHPUnitHelper;
use App\Entity\Hero;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BagOfCoinsTest extends WebTestCase
{
    /**
     * @var BagOfCoins
     */
    private $bagOfCoins;

    /**
     * @var array
     */
    private $sampleFillBagCoins = [
        CoinTypesInterface::ATTACK,
        CoinTypesInterface::ATTACK,
        CoinTypesInterface::ATTACK,
        CoinTypesInterface::ATTACK,
        CoinTypesInterface::ATTACK,
        CoinTypesInterface::ABILITY_ATTACK,
        CoinTypesInterface::DEFENCE,
        CoinTypesInterface::DEFENCE,
        CoinTypesInterface::DEFENCE,
        CoinTypesInterface::ABILITY_DEFENCE,
        CoinTypesInterface::ABILITY_DEFENCE,
        CoinTypesInterface::DRAW,
        CoinTypesInterface::DRAW,
    ];

    /**
     * @var CoinDTO
     */
    private $coinDTO;

    protected function setUp()
    {
        $this->bagOfCoins = new BagOfCoins();

        $attacker = new Hero();
        $defender = new Hero();

        $attacker->setAttack(5);
        $attacker->setAbilityAttack(1);

        $defender->setDefence(3);
        $defender->setAbilityDefence(2);

        $this->coinDTO = new CoinDTO($attacker, $defender, 2);
    }

    protected function tearDown()
    {
        $this->bagOfCoins = null;
        $this->coinDTO = null;
    }

    public function testAddCoinToBag()
    {
        $this->addCoinToBag();
        $bagCoins = $this->getBagCoins();

        $this->assertEquals($this->sampleFillBagCoins, $bagCoins);
    }

    public function testClearBagCoins()
    {
        $this->addCoinToBag();
        $this->bagOfCoins->clearBagCoins();
        $bagCoins = $this->getBagCoins();

        $this->assertEmpty($bagCoins);
    }

    public function testGetRandomCoin()
    {
        $randomCoin = $this->bagOfCoins->getRandomCoin($this->coinDTO);
        $bagCoins = $this->getBagCoins();

        $this->assertTrue(in_array($randomCoin, $bagCoins));
    }

    public function testFillBagCoins()
    {
        PHPUnitHelper::callPrivateMethodWithArgs($this->bagOfCoins, 'fillBagCoins', [$this->coinDTO]);

        $bagCoins = $this->getBagCoins();

        $this->assertEquals($this->sampleFillBagCoins, $bagCoins);
    }

    private function getBagCoins()
    {
        return PHPUnitHelper::getPrivatePropertyValue($this->bagOfCoins, 'coins');
    }

    private function addCoinToBag()
    {
        PHPUnitHelper::callPrivateMethodWithArgs($this->bagOfCoins, 'addCoinToBag', [
            5,
            CoinTypesInterface::ATTACK,
            2,
        ]);

        PHPUnitHelper::callPrivateMethodWithArgs($this->bagOfCoins, 'addCoinToBag', [
            1,
            CoinTypesInterface::ABILITY_ATTACK,
        ]);

        PHPUnitHelper::callPrivateMethodWithArgs($this->bagOfCoins, 'addCoinToBag', [
            3,
            CoinTypesInterface::DEFENCE,
        ]);
        PHPUnitHelper::callPrivateMethodWithArgs($this->bagOfCoins, 'addCoinToBag', [
            2,
            CoinTypesInterface::ABILITY_DEFENCE,
        ]);
        PHPUnitHelper::callPrivateMethodWithArgs($this->bagOfCoins, 'addCoinToBag', [
            2,
            CoinTypesInterface::DRAW,
        ]);
    }
}

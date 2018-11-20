<?php

namespace App\Tests\Unit\Dto;

use App\Dto\CoinDTO;
use App\Entity\Hero;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoinDTOTest extends WebTestCase
{
    /**
     * @var CoinDTO
     */
    private $coinDtoTest;

    protected function setUp()
    {
        $attacker = new Hero();
        $defender = new Hero();

        $attacker->setAttack(5);
        $attacker->setAbilityAttack(1);

        $defender->setDefence(3);
        $defender->setAbilityDefence(2);

        $this->coinDtoTest = new CoinDTO($attacker, $defender, 2);
    }

    protected function tearDown()
    {
        $this->coinDtoTest = null;
    }

    public function testGetAmountAttackCoins()
    {
        $this->assertEquals(5, $this->coinDtoTest->getAmountAttackCoins());
    }

    public function testAmountAbilityAttackCoins()
    {
        $this->assertEquals(1, $this->coinDtoTest->getAmountAbilityAttackCoins());
    }

    public function testGetAmountDefenceCoins()
    {
        $this->assertEquals(3, $this->coinDtoTest->getAmountDefenceCoins());
    }

    public function testGetAmountAbilityDefenceCoins()
    {
        $this->assertEquals(2, $this->coinDtoTest->getAmountAbilityDefenceCoins());
    }
}

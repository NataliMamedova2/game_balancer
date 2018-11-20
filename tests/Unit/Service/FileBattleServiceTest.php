<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\FileBattleService;
use App\Tests\Helpers\PHPUnitHelper;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;

class FileBattleServiceTest extends WebTestCase
{
    /**
     * @var FileBattleService
     */
    private $fileBattleService;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $csvFileContent = "'Hero name','Winning result';'Hero1','48 %';'Hero2','43 %';'Draw','9 %'";

    public function setUp()
    {
        $this->fileBattleService = new FileBattleService('/tmp/');
        $this->filePath = PHPUnitHelper::getPrivatePropertyValue($this->fileBattleService, 'filePath');
    }

    public function tearDown()
    {
        $this->fileBattleService = null;
        $this->filePath = null;
    }

    public function testGetEncodeToCsvContent()
    {
        $attackerMock = $this->getMockObject(['getName' => 'Hero1']);
        $defenderMock = $this->getMockObject(['getName' => 'Hero2']);

        $battleResult['result'] = [
            'Attack' => 50,
            'Ability to attack' => 10,
            'Defence' => 30,
            'Ability to defence' => 10,
            'Draw' => 0,
        ];
        $battleResult['amount'] = 100;
        $battleResult['attacker'] = $attackerMock;
        $battleResult['defender'] = $defenderMock;

        $csvFileContent = $this->fileBattleService->getEncodeToCsvContent($battleResult);

        $this->assertTrue(is_array(str_getcsv($csvFileContent)));
    }

    public function testSaveToFile()
    {
        $this->fileBattleService->saveToFile($this->csvFileContent);
        $this->assertFileExists($this->filePath);
    }

    public function testIsFileResponseObject()
    {
        $this->fileBattleService->saveToFile($this->csvFileContent);
        $responseFile = $this->fileBattleService->getFileContent();

        $this->assertTrue(is_object($responseFile) == Response::class);
    }

    public function testIsFileWritable()
    {
        $this->fileBattleService->saveToFile($this->csvFileContent);
        $this->assertIsWritable($this->filePath);
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

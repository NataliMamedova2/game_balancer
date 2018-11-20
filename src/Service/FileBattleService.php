<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileBattleService
{
    /**
     * @var string
     */
    private $filePath;

    public function __construct($catalogDir)
    {
        $this->filePath = $catalogDir . 'battle_' . time() . '.csv';
    }

    /**
     * @param array  $battleResult
     * @param string $encodeTo
     *
     * @return string
     */
    public function getEncodeToCsvContent(array $battleResult, $encodeTo = 'csv')
    {
        $fileContent = $this->getFormatFileContent($battleResult);
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

        return $serializer->encode($fileContent, $encodeTo);
    }

    /**
     * @param string $fileContent
     */
    public function saveToFile(string $fileContent)
    {
        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($this->filePath, $fileContent);
    }

    /**
     * @throws \Exception
     *
     * @return BinaryFileResponse
     */
    public function getFileContent()
    {
        if (!file_exists($this->filePath) || !is_readable($this->filePath)) {
            throw new \Exception('Report was not generated!');
        }

        $fileInfo = new \SplFileInfo($this->filePath);
        $fileResponse = new BinaryFileResponse($fileInfo);

        $fileResponse->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileInfo->getBasename()
        );

        return $fileResponse;
    }

    /**
     * @param array $battleResult
     *
     * @return array
     */
    private function getFormatFileContent(array $battleResult): array
    {
        $result = $battleResult['result'];

        $attackResult = $result['Attack'] + $result['Ability to attack'];
        $defenceResult = $result['Defence'] + $result['Ability to defence'];
        $amountBattle = $battleResult['amount'];

        return
            [
                [
                    'Hero name',
                    'Winning result',
                ],
                [
                    $battleResult['attacker']->getName(),
                    (($attackResult * 100) / $amountBattle) . ' %',
                ],
                [
                    $battleResult['defender']->getName(),
                    (($defenceResult * 100) / $amountBattle) . ' %',
                ],
                [
                    'Draw',
                    (($result['Draw'] * 100) / $amountBattle) . ' %',
                ],
            ];
    }
}

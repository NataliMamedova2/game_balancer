<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Entity\Hero;
use App\Form\BattleType;
use App\Repository\HeroRepository;
use App\Service\GameBalancerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileBattleService;

class BattleController extends AbstractController
{
    /**
     * @param Request             $request
     * @param GameBalancerService $gameBalancer
     * @param FileBattleService   $fileBattle
     * @param HeroRepository      $heroRepository
     *
     * @Route("/battle/", name="battle_index")
     *
     * @return Response
     */
    public function battleIndexPage(Request $request, GameBalancerService $gameBalancer, FileBattleService $fileBattle, HeroRepository $heroRepository): Response
    {
        $form = $this->createForm(BattleType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('battle/index.html.twig', [
                'form' => $form->createView(),
                'gameStatistic' => false,
            ]);
        }

        $battleResult = $this->getBattleResult($request, $gameBalancer, $heroRepository);

        return $this->calculateResult($form, $battleResult) ??
            $this->saveResultToFile($form, $battleResult, $fileBattle);
    }

    /**
     * @Route("/battle/ajax", name="battle_response")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function battleAjaxHandler(Request $request, HeroRepository $heroRepository): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $id = intval($request->get('id'));
            if ($id > 0) {
                $hero = $heroRepository->find($id);

                return new JsonResponse(
                    [
                        'status' => '200',
                        'data' => $hero->getAllProperties(),
                    ], 200
                );
            }
        }

        return new JsonResponse(
           [
             'status' => 'Error',
             'message' => 'Error',
           ],
        400);
    }

    /**
     * @param FormInterface $form
     * @param array         $battleResult
     *
     * @return Response|null
     */
    private function calculateResult($form, array $battleResult)
    {
        if ($form->get('calculateResult')->isClicked()) {
            return $this->render(
                'battle/index.html.twig',
                [
                    'form' => $form->createView(),
                    'gameStatistic' => $battleResult['gameStatistic'],
                    'amount' => $battleResult['amount'],
                    'attacker' => $battleResult['attacker'],
                    'defender' => $battleResult['defender'],
                ]
            );
        }

        return null;
    }

    /**
     * @param FormInterface     $form
     * @param array             $battleResult
     * @param FileBattleService $fileBattle
     *
     * @return string|\Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    private function saveResultToFile($form, array $battleResult, FileBattleService $fileBattle)
    {
        if ($form->get('saveResultToFile')->isClicked()) {
            $result = [
                'result' => $battleResult['gameStatistic'],
                'attacker' => $battleResult['attacker'],
                'defender' => $battleResult['defender'],
                'amount' => $battleResult['amount'],
            ];

            return $this->saveBattleResultToFile($result, $fileBattle);
        }

        return null;
    }

    /**
     * @param array             $battleResult
     * @param FileBattleService $fileBattleService
     *
     * @return string|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function saveBattleResultToFile(array $battleResult, FileBattleService $fileBattleService)
    {
        $fileContent = $fileBattleService->getEncodeToCsvContent($battleResult);

        try {
            $fileBattleService->saveToFile($fileContent);

            return $fileBattleService->getFileContent();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param Request             $request
     * @param GameBalancerService $gameBalancer
     * @param HeroRepository      $heroRepository
     *
     * @return array
     */
    private function getBattleResult(Request $request, GameBalancerService $gameBalancer, HeroRepository $heroRepository): array
    {
        $battleFormData = $request->get('battle');
        $attacker = new Hero();
        $defender = new Hero();

        $attacker = $attacker->setProperties($battleFormData['firstHero'], $heroRepository->find($battleFormData['firstHero']['name']));
        $defender = $defender->setProperties($battleFormData['secondHero'], $heroRepository->find($battleFormData['secondHero']['name']));

        $arStatistic = $gameBalancer->runBattle($attacker, $defender, $battleFormData['amountDraw'], $battleFormData['amount']);

        $em = $this->getDoctrine()->getManager();

        $em->persist($attacker);
        $em->persist($defender);

        $em->flush();

        return [
            'gameStatistic' => $arStatistic,
            'amount' => $battleFormData['amount'],
            'attacker' => $attacker,
            'defender' => $defender,
        ];
    }
}

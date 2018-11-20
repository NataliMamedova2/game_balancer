<?php

namespace App\Controller;

use App\Entity\Hero;
use App\Form\HeroType;
use App\Repository\HeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hero")
 */
class HeroController extends AbstractController
{
    /**
     * @Route("/", name="hero_index", methods="GET")
     */
    public function index(HeroRepository $heroRepository): Response
    {
        return $this->render('hero/index.html.twig', ['heroes' => $heroRepository->findAll()]);
    }

    /**
     * @Route("/add", name="hero_add", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $hero = new Hero();
        $form = $this->createForm(HeroType::class, $hero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($hero);
            $em->flush();

            $this->addFlash('success', $hero->getName() . ' successfully added');

            return $this->redirectToRoute('hero_index');
        }

        return $this->render('hero/new.html.twig', [
            'hero' => $hero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hero_show", methods="GET")
     */
    public function show(Request $request, HeroRepository $heroRepository): Response
    {
        $hero = $heroRepository->find($request->get('id'));

        return $this->render('hero/show.html.twig', ['hero' => $hero]);
    }

    /**
     * @Route("/{id}/edit", name="hero_edit", methods="GET|POST")
     */
    public function edit(Request $request, HeroRepository $heroRepository): Response
    {
        $hero = $heroRepository->find($request->get('id'));

        $form = $this->createForm(HeroType::class, $hero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($hero);
            $em->flush();

            $this->addFlash('success', $hero->getName() . ' successfully updated');

            return $this->redirectToRoute('hero_index');
        }

        return $this->render('hero/edit.html.twig', [
            'hero' => $hero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="hero_delete", methods="GET|POST")
     */
    public function delete(Request $request, HeroRepository $heroRepository): Response
    {
        $hero = $heroRepository->find($request->get('id'));

        if ($hero->getId()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($hero);
            $em->flush();
        }
        $this->addFlash('success', $hero->getName() . ' successfully deleted');

        return $this->redirectToRoute('hero_index');
    }
}

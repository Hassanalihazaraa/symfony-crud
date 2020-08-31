<?php

namespace App\Controller;

use App\Entity\Chocolate;
use App\Form\ChocolateType;
use App\Repository\ChocolateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chocolate")
 */
class ChocolateController extends AbstractController
{
    /**
     * @Route("/", name="chocolate_index", methods={"GET"})
     * @param ChocolateRepository $chocolateRepository
     * @return Response
     */
    public function index(ChocolateRepository $chocolateRepository): Response
    {
        return $this->render('chocolate/index.html.twig', [
            'chocolates' => $chocolateRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="chocolate_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $chocolate = new Chocolate();
        $form = $this->createForm(ChocolateType::class, $chocolate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chocolate);
            $entityManager->flush();

            return $this->redirectToRoute('chocolate_index');
        }

        return $this->render('chocolate/new.html.twig', [
            'chocolate' => $chocolate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="chocolate_show", methods={"GET"})
     * @param Chocolate $chocolate
     * @return Response
     */
    public function show(Chocolate $chocolate): Response
    {
        return $this->render('chocolate/show.html.twig', [
            'chocolate' => $chocolate,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="chocolate_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Chocolate $chocolate
     * @return Response
     */
    public function edit(Request $request, Chocolate $chocolate): Response
    {
        $form = $this->createForm(ChocolateType::class, $chocolate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('chocolate_index');
        }

        return $this->render('chocolate/edit.html.twig', [
            'chocolate' => $chocolate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="chocolate_delete", methods={"DELETE"})
     * @param Request $request
     * @param Chocolate $chocolate
     * @return Response
     */
    public function delete(Request $request, Chocolate $chocolate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chocolate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($chocolate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('chocolate_index');
    }
}

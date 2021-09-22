<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ItemRepository $itemRepository, Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $item->setIsBuy(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/check/{id}", name="check", methods={"GET","POST"})
     */
    public function check(Item $item): Response
    {
        $item->setIsBuy(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($item);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function delete(Item $item): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}

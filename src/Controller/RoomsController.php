<?php

namespace App\Controller;

use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Room;

use Symfony\Component\Routing\Annotation\Route;

class RoomsController extends AbstractController
{
    /**
     * @Route("/rooms", name="app_rooms_index")
     */
    public function index(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll();

            return $this->render('rooms/index.html.twig', compact ('rooms'));
    }

    /**
     * @Route("/rooms/new", name="app_rooms_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $room = new Room;
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($room);
            $em->flush();

            $this->addFlash(
                'success',
                sprintf('Room %s was successfully created.', $room->getName()));

            return $this->redirectToRoute('app_rooms_show', ['id' => $room->getId()]);
        }
        return $this->render('rooms/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/rooms/{id<\d+>}", name="app_rooms_show")
     */
    public function show(Room $room)
    {
        return $this->render('rooms/show.html.twig', compact ('room'));

    }
}

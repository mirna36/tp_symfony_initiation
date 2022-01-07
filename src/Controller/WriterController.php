<?php

namespace App\Controller;

use App\Entity\Writer;
use App\Form\WriterType;
use App\Repository\WriterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WriterController extends AbstractController
{
    #[Route('/writer', name: 'writer')]
    public function index(): Response
    {
        return $this->render('writer/index.html.twig', [
            'controller_name' => 'WriterController',
        ]);
    }
     
    #[Route('/writers', name: 'writer_list')]
    public function writerList(WriterRepository $writerRepository)
    {
        $writers = $writerRepository->findAll();

        return $this->render("writer/writers.html.twig", ['writer' => $writers]);
    }
     
    #[Route('/writer/{id}', name: 'writer_show')]
    public function categoryShow($id, WriterRepository $writerRepository)
    {
        $writer = $writerRepository->find($id);

        return $this->render("writer/writer.html.twig", ['writer' => $writer]);
    }

    #[Route('/update/{id}', name: 'update_writer')]
    public function updateCategory(
        $id,
        WriterRepository $writerRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ) {

        $writer = $writerRepository->find($id);

        $writerForm = $this->createForm(WriterType::class, $writer);

        $writerForm->handleRequest($request);

        if ($writerForm->isSubmitted() && $writerForm->isValid()) {
            $entityManagerInterface->persist($writer);
            $entityManagerInterface->flush();

           

            return $this->redirectToRoute("writer_list");
        }

        return $this->render("writer/writerform.html.twig", ['writerForm' => $writerForm->createView()]);
    }

    
    #[Route('/create/writer', name: 'create_writer')]
    public function createWriter(Request $request, EntityManagerInterface $entityManagerInterface)
    {

        $writer = new Writer();

        $writerForm = $this->createForm(WriterType::class, $writer);

        $writerForm->handleRequest($request);

        if ($writerForm->isSubmitted() && $writerForm->isValid()) {
            $entityManagerInterface->persist($writer);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("writer_list");
        }

        return $this->render("writer/writerform.html.twig", ['writerForm' => $writerForm->createView()]);
    }

    #[Route('/delete/writer/{id}', name: 'delete_writer')]
    public function deleteWriter(
        $id,
        WriterRepository $writerRepository,
        EntityManagerInterface $entityManagerInterface
    ) {
        $writer = $writerRepository->find($id);

        $entityManagerInterface->remove($writer);
        $entityManagerInterface->flush();

       

        return $this->redirectToRoute("writer_list");
    }
}


<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route('/book', name: 'book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
       
    #[Route('/books', name: 'book_list')]
    public function bookList(BookRepository $bookRepository )
    {
        
        $books = $bookRepository->findAll();

        return $this->render("book/books.html.twig", [
            'book' => $books
        ]);
    }
     
    #[Route('/book/{id}', name: 'book_show')]
    public function bookShow($id, BookRepository $bookRepository)
    {
        $book = $bookRepository->find($id);

        return $this->render("book/writer.html.twig", ['book' => $book]);
    }

    #[Route('/update/{id}', name: 'update_book')]
    public function updateCategory(
        $id,
        BookRepository $bookRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ) {

        $book = $bookRepository->find($id);

        $bookForm = $this->createForm(BookType::class, $book);

        $bookForm->handleRequest($request);

        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            $entityManagerInterface->persist($book);
            $entityManagerInterface->flush();

           

            return $this->redirectToRoute("book_list");
        }

        return $this->render("book/bookform.html.twig", ['bookForm' => $bookForm->createView()]);
    }

    
    #[Route('/create/book', name: 'create_book')]
    public function createWriter(Request $request, EntityManagerInterface $entityManagerInterface)
    {

        $book = new Book();

        $bookForm = $this->createForm(BookType::class, $book);

        $bookForm->handleRequest($request);

        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            $entityManagerInterface->persist($book);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("book_list");
        }

        return $this->render("book/bookform.html.twig", ['bookForm' => $bookForm->createView()]);
    }

    #[Route('/delete/book/{id}', name: 'delete_book')]
    public function deleteBook(
        $id,
        BookRepository $bookRepository,
        EntityManagerInterface $entityManagerInterface
    ) {
        $book = $bookRepository->find($id);

        $entityManagerInterface->remove($book);
        $entityManagerInterface->flush();

       

        return $this->redirectToRoute("book_list");
    }
}

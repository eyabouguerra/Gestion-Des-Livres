<?php

namespace App\Controller;

use DateTime;
use App\Entity\Livres;
use App\Form\LivreType;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
class LivresController extends AbstractController
{
    #[Route('/admin/livres', name: 'admin_livres')]

    public function index(LivresRepository $rep): Response
    {
        $livres = $rep->findAll();
        //dd($livres);
        return $this->render('Livres/index.html.twig', ['livres' => $livres]);
    }
    #[Route('/admin/livres/show/{id}', name: 'admin_livres_show')]
    public function show(Livres $livre): Response
    {

        return $this->render('Livres/show.html.twig', ['livre' => $livre]);
    }
    #[Route('/admin/livres/create', name: 'app_admin_livres_create')]
    public function create(EntityManagerInterface $em): Response
    {
        $livre1 = new Livres();
        $livre1->setAuteur('auteur 1')
            ->setEditedAt(new \DateTimeImmutable('01-01-2023'))
            ->setTitre('Titre 4')
            ->setQte(100)
            ->setResume('jhgkjhkjhlhdjfjfdgpghkgmgbkmgblkgm')
            ->setSlug('titre-4')
            ->setPrix(200)
            ->setEditeur('Eni')
            ->setISBN('111.1111.1111.1115')
            ->setImage('https://picsum.photos/300');
        $livre2 = new Livres();
        $livre2->setAuteur('auteur 3')
            ->setEditedAt(new \DateTimeImmutable('01-01-2023'))
            ->setTitre('Titre 4')
            ->setQte(100)
            ->setResume('jhgkjhkjhlhdjfjfdgpghkgmgbkmgblkgm')
            ->setSlug('titre-4')
            ->setPrix(200)
            ->setEditeur('Eni')
            ->setISBN('111.1111.1111.1115')
            ->setImage('https://picsum.photos/300');
        $em->persist($livre1);
        $em->persist($livre2);
        $em->flush();
        dd($livre1);
    }

    #[Route('/admin/livres/delete/{id}', name: 'app_admin_livres_delete')]
    public function delete(EntityManagerInterface $em, Livres $livre): RedirectResponse
    {
        $em->remove($livre);
        $em->flush();
        return $this->redirectToRoute('admin_livres');
    }

    #[Route('/admin/livres/add', name: 'admin_livres_add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $livre = new Livres();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($livre);
            $em->flush();
            return $this->redirectToRoute('admin_livres');
        }

        $livresList = $em->getRepository(Livres::class)->findAll();
    
        return $this->render('livres/add.html.twig', [
            'f' => $form->createView(),
            'livresList' => $livresList, 
        ]);
    }

    public function search(Request $request, LivresRepository $livresRepository): Response
    {
        $query = $request->query->get('query');
    
        $livres = $livresRepository->searchLivres($query);
    
        return $this->render('Livres/index.html.twig', [
            'livres' => $livres,
        ]);
    }
    
    #[Route('/admin/livres/update/{id}', name: 'app_admin_livres_update')]
public function update(Livres $livre, Request $request, EntityManagerInterface $em): Response
{
    $form = $this->createForm(LivreType::class, $livre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $em->flush();
        return $this->redirectToRoute('admin_livres');
    }
    return $this->render('livres/update.html.twig', [
        'form' => $form->createView(),
    ]);
}
    


}

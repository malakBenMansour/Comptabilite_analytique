<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Controller\CategorieController;
use App\Entity\ProduitSearch;
use App\Form\CategorieType;
use App\Form\ProduitSearchType;
use App\Form\ProduitType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index", methods={"GET"})
     */
    public function index(Request $request,PaginatorInterface $paginator)
    {
         $search=new ProduitSearch();
         $form=$this->createForm(ProduitSearchType::class,$search);
         $form->handleRequest($request);
        $this->getDoctrine()->getRepository(Produit::class)->findAllVisibleQuery($search);

        $donnes=$this->getDoctrine()->getRepository(Produit::class)->findBy([],['nom'=>'asc']);
        $produits= $paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('produit/index.html.twig', ['produits'=>$produits,
            'form'=> $form->createView()]);
    }

    /**
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("test/{id}", name="produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("test/{id}/edit", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("test/{id}", name="produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @return void
     */

    /**
     * @Route("/stat",name="stat")
     */
    public function statistique(CategorieRepository $categRepo,ProduitRepository $produitsrepo)
    {

        $categories= $categRepo->findAll();
        $categNom = [];
        $categColor = [];
        $categdescrip=[];
        $categCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($categories as $categorie){
            $categNom[] = $categorie->getNom();
            $categColor[] = $categorie->getColor();
            $categdescrip[]=$categorie->getDescrip();
            $categCount[] = count($categorie->getProdcat());
        }
        // On va chercher le nombre de produit publiées par date

        $produits = $this->getDoctrine()->getRepository(produit::class)->countByprix();
        // dump($produits);
        // die("test");
        $prix = [];
        $quantite=[];
        $produitCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($produits as $produit){

           $prix[] = $produit['prixProduit'];
           $quantite[]=$produit['quantiteProduit'];
            $produitCount[] = $produit['count'];
        }

        return $this->render('/stat.html.twig', [
            'categNom' => json_encode($categNom),
            'categColor' => json_encode($categColor),
            'categCount' => json_encode($categCount),
            'prix' => json_encode($prix),
            'quantite'=>json_encode($quantite),
            'produitCount' => json_encode($produitCount),
        ]);
    }

    /**
     * @Route ("/produitcat",name="produitcat")
     */
    public function produitParCategorie(Request $request) {

        $categorySearch = new Categorie();
        dump($this->getDoctrine()->getRepository(Categorie::class)->findAll());

        $form = $this->createForm(CategorieType::class,$categorySearch);

        $form->handleRequest($request);

        $produits= [];

        if($form->isSubmitted() && $form->isValid()) {

            $category = $categorySearch->getId();

            if ($category!="")
                $produits= $category->getProdcat();
            else
                $produits= $this->getDoctrine()->getRepository(Produit::class)->findAll();
        }

        return $this->render('produit/produitcat.html.twig',['form' => $form->createView(),'produits' => $produits]);
    }


}
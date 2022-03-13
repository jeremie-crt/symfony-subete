<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    private EntityManagerInterface $manager;
    private SluggerInterface $slugger;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->manager = $entityManager;
        $this->slugger = $slugger;
    }

    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $articleRepository->findAll();

        $pagination = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('article/index.html.twig', [
            'articles' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET", "POST"}, requirements={"page"="\d+"})
     */
    public function new(Request $request): Response
    {
//        $this->denyAccessUnlessGranted('ROLE_EDITOR', null, 'User tried to access a page without having ROLE_ADMIN - not able to access ');

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setSlug(strtolower($this->slugger->slug($article->getTitle())));
            $article->setAuthor($this->getUser());

            foreach ($form->getViewData()->extra_categories as $newCat) {
                $article->addCategory($newCat);
            }
            foreach ($form->getViewData()->extra_tags as $newTag) {
                $article->addTag($newTag);
            }

            $this->manager->persist($article);
            $this->manager->flush();

            $this->addFlash('success', 'The article has been saved');

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/topdf", name="article_topdf_show")
     */
    public function articleToPdf(Article $article, Pdf $pdf): Response
    {
        $content = $this->renderView('article/topdf.html.twig', [
            'article' => $article,
        ]);
        $pdf->setTemporaryFolder("public/files");
        return new PdfResponse($pdf->getOutputFromHtml($content, [
            'lowquality' => false,
        ]),
            "export.pdf"
        );
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET", "POST"}, requirements={"page"="\d+"})
     */
    public function edit(Request $request, Article $article): Response
    {
//        $this->denyAccessUnlessGranted('ROLE_EDITOR', null, 'User tried to access a page without having ROLE_ADMIN - not able to access ');

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setSlug(strtolower($this->slugger->slug($article->getTitle())));

            foreach ($form->getViewData()->extra_categories as $newCat) {
                $article->addCategory($newCat);
            }
            foreach ($form->getViewData()->extra_tags as $newTag) {
                $article->addTag($newTag);
            }

            $this->manager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"POST"}, requirements={"page"="\d+"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $this->manager->remove($article);
            $this->manager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }
}

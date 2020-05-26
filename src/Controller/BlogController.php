<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    // Un commentaire qui commence par un @ est une annotation tres importante, Symfony explique que lorsqu'on lancera www.monsite.com/blog, on fera appel à la methode index()

    //Pas besoin de présiser templates/blog/index.html.twig, Symfony sait ou se trouve les fichiers template de rendu 

    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {

        /*
            Pour selectionner des données en BDD, nous avons besoin de la classe Repository de la classe Article
            Une classe Repository permet uniquement de selectionner des données en BDD (requete SQL SELECT)
            On a besion de l'ORM DOCTRINE pour faire la relation entre la BDD et notre application (getDoctrine())
            getRepository() : méthode issue de l'object DOCTRINE qui permet d'importer une classe Repository (SELECT)

            $repo est un objet issu de la classe ArticleRepositiry, cette méthode contient des méthodes prédéfinie par SYMFONY permettant de selectionner des données en BDD (find, findBy, findAll, findOneBy)

            dumb(): équivault de var_dump, permet selectionner l'ensemble de la table (similaire à SELECT * FROM article)
        */
        $repo = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repo->findAll();

        dump($articles);

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     *  @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenue sur le blog Symfony',
            'age' => 25
        ]);
    }


    //show est une méthode permettant d'afficher le détail d'1 article 

    /**
     * @route("/blog/45", name="blog_show")
     */
    public function show()
    {
        return $this->render('blog/show.html.twig');
    }

    // créer une méthode create() (route '/create ') renvoi le template create.html.twig + un peu de contenu dans le template + test navigateur

        /**
     * @route("/blog/create", name="create")
     */
    public function create()
    {
        return $this->render('blog/create.html.twig');
    }

}

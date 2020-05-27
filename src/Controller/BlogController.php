<?php

namespace App\Controller;

/*imporatation de classe => Ctrl + Alt + I */

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    // Un commentaire qui commence par un @ est une annotation tres importante, Symfony explique que lorsqu'on lancera www.monsite.com/blog, on fera appel à la methode index()

    //Pas besoin de présiser templates/blog/index.html.twig, Symfony sait ou se trouve les fichiers template de rendu 

    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {

        /*
            Pour selectionner des données en BDD, nous avons besoin de la classe Repository de la classe Article
            Une classe Repository permet uniquement de selectionner des données en BDD (requete SQL SELECT)
            On a besion de l'ORM DOCTRINE pour faire la relation entre la BDD et notre application (getDoctrine())
            getRepository() : méthode issue de l'object DOCTRINE qui permet d'importer une classe Repository (SELECT)

            $repo est un objet issu de la classe ArticleRepositiry, cette méthode contient des méthodes prédéfinie par SYMFONY permettant de selectionner des données en BDD (find, findBy, findAll, findOneBy)

            dumb(): équivault de var_dump, permet selectionner l'ensemble de la table (similaire à SELECT * FROM article)
        */
        // $repo = $this->getDoctrine()->getRepository(Article::class);  ------ pas besoin de cette ligne avec l'argument ArticleRepository $repo dans index()

        $articles = $repo->findAll();

        dump($articles);

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'artciles' => $articles, 
        ]);
            // on envoie les articles selectionnés en BDD directement sur le navigateur dans le template index.html.twig
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
     * @route("/blog/new", name="blog_create")
     */
    public function create(Request $request)
    {
        dump($request);

        return $this->render('blog/create.html.twig');
    }

    /**
     * @route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article) 
    {
        /*
        Pour selectionner un article dans la BDD, nous utilisons le principe de route paramètrées
        Dans la route, on définit un paramètre de type {id}
        Lorsque nous transmettons dans l'URL par exemple une route '/blog/9', donc on envoie un id connu en BDD dans l'URL
        Synfony va automatiquement recupérer ce paramètre et le transmettre en argument de la méthode show()
        Cela veut dire que nous avons accès à l'id àl'intérieur de la méthode show()
        Le but est de selectionner les données en BDD de l'id à l'interieur de la méthode show() 
        Nous avons besoin pour cela de la classe ArticleRepository afin de pouvoir selectonner en BDD
        La méthode find() est issue de la classe ArticleRepository et permet de selestionner des données en BDD à partir d'un paramètre de type {id}
        getDoctrine() : l'ORN fait le travail pour nous, c'est à dire qu'elle récupère la requete de selection pour l'executer en BDD 
        Et Doctrine récupère le résultat de la requete de selection pour l'envoyer dans le controller
        
        $repo est un objet issu de la classe ArticleRepository, nous avons accès à toute les méthode déclarée dans cette classe (find, findAll, findBy, findOneBy, ect...)
        */ 

        // $repo = $this->getDoctrine()->getRepository(Article::class);  ------ pas besoin de cette ligne avec l'argument ArticleRepository $repo, $id dans index()

        // $article = $repo->find($id);// On transmet en argument de la méthodefind(), le paramètre {id} récupérer dans l'URL
        // find() : SELECT * FROM article WHERE id = ... + FETCH  ------ pas besoin de cette ligne avec l'argument (Article $article), $id dans index()

        dump($article);

        return $this->render('blog/show.html.twig', [
            'article'=> $article
        ]);
        // On envoie dans le template show.html.twig, les données selectionnée en BDD, c'est à dire le détail d'un article 
        // extract (['article => $article]) => 'article' decient une variable TWIG dans le template show.html.twig

           
            //        
            //   ______     Doctrine  __________ 
            //  |      | <---------  |          |
            //  | BDD  |             |Controller|---------> Libère les templates + données BDD sur le navigateur   
            //  |______| ----------> |__________|
            //              Doctrine         
            //         
            

    }

    // créer une méthode create() (route '/create ') renvoi le template create.html.twig + un peu de contenu dans le template + test navigateur



}

/*

Injections de dépendances

Dans Symfony nous avons un service container, tout ce qui est contenu dans Symfony est géré par Symfony 
Si nous observons la classe BlogController, nous ne l'avons jamais instanciée, c'est Symfony lui-même qui se charge de l'instancier, donc il instancie des classes et appel ses fonctions

dans Symfony, ces objet utiles sont appelés 'service' et chaque services vit à l'intérieur d'un object très spécial appelé conteneur de service. Il vous facilite la vie, favorise une architechture solide et super rapide !! 

La fonction index() a pour rôle de nous afficher la liste des articles de la base de donnée et pour fonctionner, elle a donc besoin d'un repository (requete de selection), quand une fonction a besoin de quelque chose pour fonctionner, on appel ca une dépendance, la fonction dépend d'un repository pour aller chercher la liste des articles 

Donc si nous avons une dépendance, nous pouvons demander à Symfony de nous la fournir plutôt que de la fabriquer nous même 

La fonction index() ce n'est pas nous qui l'exectons, c'est Symfony qui le fait pour nous 

Nous Devons fournir à la méthode index() en argument, un objet issu de la classe ArticleRepository

*/
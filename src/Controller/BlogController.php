<?php

namespace App\Controller;

/*imporatation de classe => Ctrl + Alt + I */

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
    /*
    On declare une route permettant d'insérer un article '/blog/new'
    On déclare une route paramétrée '/blog/{id}/edit' permettant de modifier une article 

    Si nous envoyons en {id} dans l'URL, Symfony est capable d'aller selectionner en BDD les données de l'article, donc l'objet $article n'est plus null 
    
    Si nous n'envoyons pas d'id dans l'URL, à ce moment là l'objet $article est bien NULL

    */




    /**
     * @route("/blog/new", name="blog_create")
     * @route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager)
    {

        // initialement méthode create()

        /*
        
        La classe request est une classe prédéfinie en Symfony qui stocke toute les données véhiculées par les superglobales ($_POST, £_GET, $_SERVER, ect..)
        La propriété 'request' représente la superglobale $_POST, les données saisies dans le formulaire sont accessible via cette propriété, ca renvoie des parameterBag (sac à parametres)
        Pour insérer un nouvelle article, nous devons instancier la classe pour avoir un article vide, toute les propriétés private ($titre, $content, $image ), ils faut donc les remplir, pour cela nous faisons appel au setter

        EntityMangerInterface est une méthode prédéfinie de Symfony qui permet de manipuler les lignes de la BDD (INSERT, UPDATE, DELETE)
        
        persit() est une méthode issue de la classe EntityManager qui permet de stocker et de préparer la requete SQL d'insertion
        flush() est une méthode issue de la classe EntityManager qui permet de libérer la requete d'insertion, c'est elle qui envoie veritablement dans la BDD

        redirectionToRoute() méthode prédéfinie de Symfony qui permet de rediriger vers une route spécifique, dans notre cas on redirige apres insertion vers la route blog_show (avec le bon dernier id inserer ) qfin de renvoyer vers le détail de l'article qui vient d'être inséré

        */ 

        dump($request);

        // if($request->request->count() > 0)
        // {
        //     $article = new Article;
        //     $article->setTitle($request->request->get('title'))
        //             ->setContent($request->request->get('content'))
        //             ->setImage($request->request->get('image'))
        //             ->setCreatedAt(new \DateTime());
            
        //     $manager->persist($article);
        //     $manager->flush();

        //     return $this->redirectToRoute('blog_show', [
        //         'id' => $article->getId()
        //     ]);
                
        // }

        //$article = new Article; // on crée un nouvelle objet article

        /*
        $article->setTitle("Titre d'article test")
                ->setContent("Contenu de l'article test");

        On observe quand remplissant l'objet $article via les setteuts, les getteurs renvoient les données de l'article directement à l'intérieur des champs du formulaire
        */

        // $form = $this->createFormBuilder($article) // on point sur la méthode createFormBuilder
        //             ->add('title', TextType::class, [
        //                     'attr' => [
        //                         'placeholder' => "Titre de l'article",
        //                         'class' => 'form-control mb-3 mx-auto'
        //                     ]
        //             ])
        //             ->add('content', TextareaType::class, [
        //                     'attr' => [
        //                         'placeholder' => "Contenu de l'article",
        //                         'class' => 'form-control mb-3 mx-auto'
        //                     ]
        //             ])
        //             ->add('image', TextType::class, [
        //                     'attr' => [
        //                         'placeholder' => "Titre de l'article",
        //                         'class' => 'form-control mb-3 mx-auto'
        //                     ]
        //             ])
                    
        //             ->add('save', SubmitType::class, [
        //                     'label'=>'Enregistrer'
        //             ])

        //             ->getForm(); // on met en forme

        // return $this->render('blog/create.html.twig', [
        //     'formArticle' => $form->createView() // convertit le formulaire et l'envoie sur le template
        //]);

        /* 
        createFormulaireBuilder() est une méthode prédéfinie de Symfony qui permet de créer un formulaire à partir d'une entité, dans notre cas de la classe Article, cela permet aussi de dire que le formulaire permettera de remplir l'objet issu de la classe Article $article

        add() est une méthode qui permet de créer les différents champs de fotmulaire 
        getForm() est une méthode qui permet de terminer et dr valider le formulaire

        hndleRequest() est une méthode qui permet de récupérer les information stockées dans $_POST et de remplir notre objet $article, plus besoin de faire appel aux setters de la classe Article
        */

        //Si l'objet $article n'est pas rempli, cela veut dire que nous n'avons pas envoyé d'id dans l'url, alors c'est une insertion, on crée un nouvel objet Article 
        if(!$article)
        {
            $article = new Article;
        }

        // On construit le formulaire
        $form = $this->createFormBuilder($article) // on pointe sur la méthode createFormBuilder

        ->add('title')
        ->add('image')
        ->add('content')
        ->getForm();

        $form->handleRequest($request); // recupere tous les données de $_POST et les transmet en BDD

        // Si l'article ne possède pas {id}, cela veut dire que ce n'est pas une modification, alors on appel le setteur de la date de creation de l"article
        //Si c'est une modification, l' article possède déjà un if, alors on ne modifie oas la date de création de l'article
        if($form->isSubmitted() && $form->isValid())
        {
            if(!$article->getId())
            {
                $article->setCreatedAt(new \DateTime());
            }
            

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView()
        ]);
    }

    /**
     * @route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article) 
    {
        /*
        Pour selectionner un article dans la BDD, nous utilisons le principe de route paramètrée
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
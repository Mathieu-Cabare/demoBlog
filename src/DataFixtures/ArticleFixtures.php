<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

/*
class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i<=10 ; $i++) // On boucle 10 fois afin de créer 10 articles 
        {
            $article = new Article; // On instantie la classe Article afin de renseigner les priopriétés private et envoyer les objects type Article
            
            On renseigne tout les setteurs de la classe Article afin d'ajouter des titres , du contenu et qui seront insérée en BDD    
            $article-> setTitle("Titre de l'article n° $i")
                    -> setContent("<p>Contenu de l'article n° $i</p>")
                    -> setImage("https://picsum.photos/250?random=$i")
                    -> setCreatedAt(new DateTime()); // objet classe DateTime

            $manager->persist($article); // persiste() est une méthode issue de la classe ObjectManager permettant de garder en mémoire le objects Articles crées, il les fait persister dans le temps 
        }

        $manager->flush(); // flush() est une méthode issue de la classe ObjctManager qui permet véritablement de générer l'insertion en BDD
    }
}*/


class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR'); 
        // On utilise la bibliothèque FAKER qui permet d'envoyer des fausses données aléatoire dans la base de données 
        // On a demandé à composer d'installer cette librairie sur notre application    
        
        //Création de trois catégories
        for ($i = 1; $i <= 3; $i++)
        {

            // On appel les setteurs de l'entité Category 
            $category = new Category;
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());
            
            $manager->persist($category); // on garde en mémoire les objets $category
            
            //Création de quatre à six articles :
            for ($j = 1; $j <= mt_rand(4,6); $j++ )
            {
                // Nous avons besoin d'un objet $article vide afin de créer et d'insérer de nouveaux articles
                $article = new Article;

                // On demande à faker de créer 5 paragraphes aléatoire pour nos nouveaux articles
                $content = '<p>' . join($faker->paragraphs(5), '<p></p>') . '</p>';

                // On rensiegne tout les settteurs de la classe Article grace aux méthodes de la librairie FAKER (phrase aléatoire (sentence), images aléatoires(imageUrl()) ect ..) 
                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months')) // Création de date d'article, d'il y a 6 mois à aujourd'hui 
                        ->setCategory($category);// On renseigne la clé étrangére qui oerlet de relier les articles aux catégories

                $manager->persist($article);

                // Création de quatre à dix commentaire

                for ($k=1; $k<=mt_rand(4,10); $k++)
                {
                    $comment = new Comment;

                    $content = '<p>' . join($faker->paragraphs(2), '<p></p>') . '</p>';

                    $now = new \DateTime(); // objet dateTime ave l'heure et la date du jour
                    $interval = $now->diff($article->getCreatedAt());// représente entre maintenant et la date de création de l'article (timestamp)
                    $days = $interval->days; // nombre de jour entre maintenent et la date de création de l'article
                    $minimum = '-' . $days . 'days'; // - 100 jours entre la date de création de l'article et maintenant

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);
                    
                    $manager->persist($comment); // on relie (clé étrangere ) nos commentaires aux articles
                }
            }
        }

        $manager->flush();
    }
}

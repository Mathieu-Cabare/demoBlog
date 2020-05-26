<?php

namespace App\DataFixtures;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i<=10 ; $i++) // On boucle 10 fois afin de créer 10 articles 
        {
            $article = new Article; // On instantie la classe Article afin de renseigner les priopriétés private et envoyer les objects type Article
            
            // On renseigne tout les setteurs de la classe Article afin d'ajouter des titres , du contenu et qui seront insérée en BDD    
            $article-> setTitle("Titre de l'article n° $i")
                    -> setContent("<p>Contenu de l'article n° $i</p>")
                    -> setImage("https://picsum.photos/250?random=$i")
                    -> setCreatedAt(new DateTime()); // objet classe DateTime

            $manager->persist($article); // persiste() est une méthode issue de la classe ObjectManager permettant de garder en mémoire le objects Articles crées, il les fait persister dans le temps 
        }

        $manager->flush(); // flush() est une méthode issue de la classe ObjctManager qui permet véritablement de générer l'insertion en BDD
    }
}

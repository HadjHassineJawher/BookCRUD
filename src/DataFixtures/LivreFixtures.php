<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Livre;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
     /*   for($i=1;$i<=10;$i++){ 
            $livre =new Livre();
            $livre->setTitre("titre $i");
            $date="06-01-2020";
            $livre->setDateEdition(new \DateTime($date));
            $livre->setEditeur("Editeur $i");
            $livre->setImage("https://via.placeholder.com/350x150");
            $livre->setResume("<p>resume $i</p>");
            $manager->persist($livre);
        }
            */
            $faker=Faker\Factory::create('fr_FR');
            for($i=1;$i<=10;$i++){
                $livre =new Livre();
                $resume=$faker->paragraph($nbSentences = 2);
                $livre->setTitre($faker->sentence())
                    ->setDateEdition(new \DateTime($faker->date()))
                    ->setEditeur($faker->company())
                    ->setImage($faker->ImageUrl($width=250,$heigh=150,'food'))
                    ->setResume("$resume");
                    $manager->persist($livre);
            }
        $manager->flush();
    }

}


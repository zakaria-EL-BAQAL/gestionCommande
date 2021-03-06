<?php

namespace AppBundle\Controller;

use DateTime;
use Faker\Factory;
use AppBundle\Entity\Client;
use AppBundle\Entity\Produit;
use AppBundle\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FakeControllerController extends Controller
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    /**
     * @Route("/add")
     */
    public function addAction()
    {
        $faker = Factory::create();

        $entityManager = $this->getDoctrine()->getManager();

        for ($i = 0; $i < 20; $i++) {
            $product = new Produit();
            $product->setTitle($faker->word)
                    ->setDescription($faker->word)
                    ->setPrice(mt_rand(10, 100))
                    ->setUrlImage($faker->imageUrl(640,480));
    
    
            $entityManager->persist($product);
        }
    
    
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('created');
    } 



    /**
     * @Route("/addCommandes")
     */
    public function addFakeAction()
    {
        $faker = Factory::create();

        $entityManager = $this->getDoctrine()->getManager();

          //gerer les produits
          $produits = [];
          for ($i = 0; $i < 10; $i++) {
            $product = new Produit();
            $product->setTitle($faker->sentence())
                    ->setDescription($faker->sentence())
                    ->setPrice(mt_rand(10, 100))
                    ->setUrlImage($faker->imageUrl(640,480));

            $produits[] = $product;
    
            $entityManager->persist($product);
        }


        //gerer les utilisateur
        $clients = [];

        $genres = ['men','female'];

        for($i = 0; $i < 10; $i++){

            $client = new Client();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';
            $picture .= ($genre == 'men' ? 'men/' : 'women/') . $pictureId;
            $hash = $this->encoder->encodePassword($client, 'pass');

            $client->setEmail($faker->email)
                   ->setPassword($hash)
                   ->setUrlAvatar($picture);

                   $clients[] = $client;

                   $entityManager->persist($client);
        }


            //gerer les commandes
            
            
            for($i = 0; $i < 10; $i++){
                $client = $clients[mt_rand(0, count($clients) - 1)];
                $commande = new Commande();
                $commande->setClient($client)
                         
                         ->setTotalPrice(mt_rand(10, 100));
                    $entityManager->persist($commande);
            }

        
      
    
    
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('created');
    } 

}

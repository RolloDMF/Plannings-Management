<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Day;
use App\Entity\Manager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        $days = [
            'Lundi',
            'Mardi',
            'Mercredi',
            'Jeudi',
            'Vendredi',
            'Samedi',
            'Dimanche'
        ];

        $user = new Manager;
        $user->setFirstName('Juriens');
        $user->setLastName('Rodrigue');
        $user->setEmail('juriens.rodrigue@gmail.com');
        $user->setPlainPassword('123456');
        $user->setSuperAdmin(true);
        $user->setEnabled(true);
        $user->setUsername('Rollo');

        $manager->persist($user);

        for ($i=0; $i < 7; $i++) { 
            $day = new Day();
            $day->setName($days[$i]);
            $day->setRepresentationNumber($i + 1);

            $manager->persist($day);
        }


        $manager->flush();
    }
}
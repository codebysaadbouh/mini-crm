<?php

namespace App\DataFixtures;

use App\Entity\Costumer;
use App\Entity\Invoice;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; -> deprecated

class AppFixtures extends Fixture
{
    /**
     * Password Encoder
     * @var UserPasswordHasherInterface
     */
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($u=0; $u < 25; $u++ ){ // 25 users
            $chrono = 1;
            $user = new User();
            $hash = $this->encoder->hashPassword($user, 'password');
            $user->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($hash);

            $manager->persist($user);

            for ($c = 0; $c < (mt_rand(5,20)); $c++) { // 5 to 20 invoices per user
                $costumer = new Costumer();
                $costumer->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setCompany($faker->company)
                    ->setEmail($faker->email)
                    ->setUser($user);

                $manager->persist($costumer);
                for ($i = 0; $i < (mt_rand(3,10)); $i++){ // 3 to 10 invoices per costumer
                    $invoice = new Invoice();
                    $invoice->setAmount($faker->randomFloat(2, 250, 2500))
                        ->setSentAt($faker->dateTimeBetween('-6 months'))
                        ->setStatus($faker->randomElement(['SENT', 'PAID', 'CANCELLED']))
                        ->setChrono($chrono)
                        ->setCostumer($costumer);
                    $manager->persist($invoice);
                    $chrono++;
                }
            }
        }



        $manager->flush();
    }
}

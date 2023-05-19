<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\UserRepository;

class ProjectFixtures extends Fixture
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $user = $this->userRepository->find(rand(1, 10));

            if(!$user) {
                $user = new User();
                $user->setFirstName($faker->firstName);
                $user->setLastName($faker->lastName);
                $user->setEmail($faker->email);
                $user->setEnabled(true);
                $user->setStatus('active');

                $manager->persist($user);
            }

            $project = new Project();
            $project->setUser($user);
            $project->setTitle($faker->title);
            $project->setDescription($faker->text);

            $manager->persist($project);
        }

        $manager->flush();
    }
}
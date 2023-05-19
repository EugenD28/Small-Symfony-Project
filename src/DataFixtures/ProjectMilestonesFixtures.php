<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Project;
use App\Entity\ProjectMilestones;
use App\Entity\User;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;

class ProjectMilestonesFixtures extends Fixture
{
    private $projectRepository;
    private $userRepository;

    public function __construct(ProjectRepository $projectRepository, UserRepository $userRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $project = $this->projectRepository->find(rand(1, 100));

            if(!$project) {
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

            $projectMilestones = new ProjectMilestones();
            $projectMilestones->setProject($project);
            $projectMilestones->setTitle($faker->title);
            $projectMilestones->setDescription($faker->text);
            $projectMilestones->setMilestoneDeadline($faker->dateTime);

            $manager->persist($projectMilestones);
        }

        $manager->flush();
    }
}
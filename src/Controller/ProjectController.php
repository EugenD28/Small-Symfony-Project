<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectController extends AbstractController
{
    #[Route('/projects/create', name: 'create_project', methods: ['POST'])]
    public function create(Request $request,ValidatorInterface $validator, ProjectRepository $projectRepository, UserRepository $userRepository): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $user = $userRepository->find($body['user']);

        $project = new Project();
        $project->setUser($user);
        $project->setTitle($body['title']);
        $project->setDescription($body['description']);

        $errors = $validator->validate($project);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $projectRepository->save($project, true);

        $data = $projectRepository->find($project->getId());

        $project = array(
            'id' => $data->getId(),
            'user' => $data->getUser()->getName(),
            'title' => $data->getTitle(),
            'description' => $data->getDescription(),
        );

        return new JsonResponse($project);
    }

    #[Route('/projects', name: 'get_projects', methods: ['GET'])]
    public function getProjects(ProjectRepository $projectRepository): JsonResponse
    {
        $projects = $projectRepository->findAll();

        if (!$projects) {
            throw $this->createNotFoundException(
                'No projects found'
            );
        }

        $projectCollection = array();

        foreach($projects as $project) {
            $milestones = array();
            $milestonesData = $project->getProjectMilestones();

            foreach($milestonesData as $milestone) {
                $milestones[] = array(
                    'title' => $milestone->getTitle(),
                    'description' => $milestone->getDescription(),
                    'deadline' => $milestone->getMilestoneDeadline()->format('Y-m-d')
                );
            }


            $projectCollection[] = array(
                'id' => $project->getId(),
                'user' => $project->getUser() ? $project->getUser()->getName() : 'No assign',
                'title' => $project->getTitle(),
                'description' => $project->getDescription(),
                'milestones' => $milestones
            );
        }

        return new JsonResponse($projectCollection);
    }

    #[Route('/projects/{id}', name: 'delete_project', methods: ['DELETE'])]
    public function deleteProject(Project $project, ProjectRepository $projectRepository): Response
    {
        if (!$project) {
            throw $this->createNotFoundException(
                'No project found for id '.$project->getId()
            );
        }
        
        $projectRepository->remove($project, true);

        return new Response('Project was deleted');
    }
}

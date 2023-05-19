<?php

namespace App\Controller;

use App\Entity\ProjectMilestones;
use App\Repository\ProjectMilestonesRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectMilestonesController extends AbstractController
{
    #[Route('/milestones/{id}', name: 'milestone')]
    public function getUserInfo($id, ProjectMilestonesRepository $projectMilestonesRepository)
    {
        $milestone = $projectMilestonesRepository->find($id);

        $milestoneData = array(
            'id' => $milestone->getId(),
            'title' => $milestone->getTitle(),
            'description' => $milestone->getDescription(),
            'milestoneDeadline' => $milestone->getMilestoneDeadline()->format('Y-m-d')
            
        );

        return $this->render('/project/milestone.html.twig', [
            'milestone' => $milestoneData,
        ]);
    }

    #[Route('/project/milestones/create', name: 'create_project_milestones', methods: ['POST'])]
    public function create(Request $request,ValidatorInterface $validator, ProjectMilestonesRepository $projectMilestonesRepository, ProjectRepository $projectRepository): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $project = $projectRepository->find($body['project']);

        $projectMilestone = new ProjectMilestones();
        $projectMilestone->setProject($project);
        $projectMilestone->setTitle($body['title']);
        $projectMilestone->setDescription($body['description']);
        $projectMilestone->setMilestoneDeadline(date_create($body['milestoneDeadline']));

        $errors = $validator->validate($projectMilestone);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $projectMilestonesRepository->save($projectMilestone, true);

        $data = $projectMilestonesRepository->find($projectMilestone->getId());

        $projectMilestone = array(
            'id' => $data->getId(),
            'user' => $data->getProject()->getTitle(),
            'title' => $data->getTitle(),
            'description' => $data->getDescription(),
            'milestoneDeadline' => $data->getMilestoneDeadline()->format('Y-m-d')
        );

        return new JsonResponse($projectMilestone);
    }

    #[Route('/project/milestones', name: 'get_projects_milestones', methods: ['GET'])]
    public function getProjectMilestones(ProjectMilestonesRepository $projectMilestonesRepository): JsonResponse
    {
        $projectMilestones = $projectMilestonesRepository->findAll();

        if (!$projectMilestones) {
            throw $this->createNotFoundException(
                'No project\'s milestones found'
            );
        }

        $projectMilestonesCollection = array();

        foreach($projectMilestones as $projectMilestone) {            
            $projectMilestonesCollection[] = array(
                'id' => $projectMilestone->getId(),
                'project' => $projectMilestone->getProject()->getTitle(),
                'title' => $projectMilestone->getTitle(),
                'description' => $projectMilestone->getDescription(),
                'milestones' => $projectMilestone->getMilestoneDeadline()->format('Y-m-d')
            );
        }

        return new JsonResponse($projectMilestonesCollection);
    }

    #[Route('/project/milestones/{id}', name: 'delete_project_milestone', methods: ['DELETE'])]
    public function deleteProjectMilestone(ProjectMilestones $projectMilestone, ProjectMilestonesRepository $projectMilestonesRepository): Response
    {
        if (!$projectMilestone) {
            throw $this->createNotFoundException(
                'No project\'s milestone found for id '.$projectMilestone->getId()
            );
        }
        
        $projectMilestonesRepository->remove($projectMilestone, true);

        return new Response('Project milestone was deleted');
    }
}

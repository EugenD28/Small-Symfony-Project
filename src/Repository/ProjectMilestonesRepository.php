<?php

namespace App\Repository;

use App\Entity\ProjectMilestones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectMilestones>
 *
 * @method ProjectMilestones|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectMilestones|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectMilestones[]    findAll()
 * @method ProjectMilestones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectMilestonesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectMilestones::class);
    }

    public function save(ProjectMilestones $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProjectMilestones $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

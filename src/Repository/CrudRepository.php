<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class CrudRepository extends EntityRepository {

    public function save($entity) {
        if ($entity->getId()) {
            $this->getEntityManager()->merge($entity);
        } else {
            $this->getEntityManager()->persist($entity);
        }
        $this->getEntityManager()->flush();
    }

    public function delete($entity) {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
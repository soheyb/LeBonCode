<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Advert>
 *
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    /**
     * Method to add data to the database
     * @param Advert $entity
     * @param bool $flush
     * @return void
     */
    public function add(Advert $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    }

    /**
     * Method to remove data from the database
     * @param Advert $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Advert $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @param $advert
     * @return void
     */
    public function disable($advert):void
    {
        $advert->setEnable(0);
        $this->getEntityManager()->persist($advert);
        $this->getEntityManager()->flush();
    }


    /**
     * @param $doctrine
     * @param $advert
     * @param $datas
     * @return bool
     */
    public function update($advert, $data) :void
    {

        empty($data['description']) ? true : $advert->setDescription($data['description']);
        empty($data['price']) ? true : $advert->setPrice($data['price']);
        empty($data['zip']) ? true : $advert->setZip($data['zip']);
        empty($data['city']) ? true : $advert->setCity($data['city']);

        $this->getEntityManager()->persist($advert);
        $this->getEntityManager()->flush();

    }
}

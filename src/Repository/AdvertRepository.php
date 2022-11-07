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

    /**
     * Method used to check if the advert exist
     * @param $id
     * @return bool
     * @throws NonUniqueResultException
     */
    public function checkExist($id):bool
    {

        $advert = $this->createQueryBuilder('a')
            ->select()
            ->where('a.id = :id')
            ->setParameter("id", $id)
            ->getQuery()
            ->getOneOrNullResult();


        $returnValue = !is_null($advert);

        return ($returnValue);

    }


    /**
     * @param array $parameters
     * @return array
     */
    public function findByCustom(array $parameters) :array
    {

        $adverts = $this->createQueryBuilder('a')
            ->select()
            ->where("a.title LIKE :title")
            ->andWhere('a.price >= :price_min')
            ->andWhere('a.price <= :price_max')
            ->setParameters(["title" => "%".$parameters["title"]."%",
                "price_min" => $parameters["price_min"],
                "price_max" => $parameters["price_max"]])
            ->getQuery()
            ->getArrayResult();


        return ($adverts);
    }

}

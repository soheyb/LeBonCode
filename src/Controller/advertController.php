<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Repository\AdvertRepository;
use App\Utils\Checker;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerBuilder;

class advertController extends AbstractController
{



    /**
     * @Route("/advert", methods={"POST"})
     */
    public function addAdvert(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        if (Checker::arrayCompare(array(
                "title",
                "description",
                "price",
                "zip",
                "city"
            ), $parameters) === false) {

            return new JsonResponse(
                array(
                    "error" => "wrong parameters",
                ),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $advert = new Advert($parameters["title"], $parameters["description"], $parameters["price"], $parameters["zip"], $parameters["city"]);
        $advertRepo = new AdvertRepository($doctrine);
        $advertRepo->add($advert);

        return new JsonResponse(
            array(
                "message" => "advert added"
            ),
            Response::HTTP_OK
        );
    }


    /**
     * @Route("/advert/{id}", methods={"DELETE"})
     * @throws NonUniqueResultException
     */
    public function deleteAdvert(ManagerRegistry $doctrine, $id): JsonResponse
    {

        $advertRepo = new AdvertRepository($doctrine);


        switch ($advertRepo->checkExist($id)) {
            case false:
                $data = ["message" => "Advert  Unknown"];
                return new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);

            default:
                $advert = $advertRepo->createQueryBuilder('a')
                    ->select()
                    ->where('a.id = :id')
                    ->setParameter("id", $id)
                    ->getQuery()
                    ->getOneOrNullResult();

                $advertRepo->disable($advert);
                return new JsonResponse(
                    array(
                        "message" => "advert disabled"
                    ),
                    Response::HTTP_OK
                );
        }


    }



}
<?php

namespace App\Controller\invoice;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        //$services = $this->getDoctrine()->getRepository(Service::class)->findAll();
        $services =$serviceRepository->findAll();
        return $this->render('service/index.html.twig', [
            'services' => $services,
        ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/service/delete/{id}', name: 'app_service_delete')]
    public function Delete(ServiceRepository $serviceRepository , $id): Response
    {

        $service = $serviceRepository->find($id);
        $serviceRepository->remove($service, true);
        $services =$serviceRepository->findAll();
        return $this->render('service/index.html.twig', [
            'services' => $services,
        ]);
    }
    #[Route('/service/update/{id}', name: 'app_service_update')]
    public function update (ServiceRepository $serviceRepository,service $services, Request $request):Response
    {

        $form = $this->createForm(ServiceType::class,$services);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $services = $form->getData();
          $serviceRepository->add($services);
            return $this->redirectToRoute( 'app_service');
        }







        return $this->render('service/edit.html.twig',[
            'form'=>$form->createView()
        ]);
    }



}

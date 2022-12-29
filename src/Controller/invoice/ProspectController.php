<?php

namespace App\Controller\invoice;

use App\Entity\Prospect;

use App\Form\ProspectForm;
use App\Repository\ProspectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class ProspectController extends AbstractController
{
    #[Route('/prospect/new', name: 'app_prospect_new', methods: ['GET', 'POST'])]
    public function AddProspect(EntityManagerInterface $em, Request $request, ProspectRepository $prospectRepository): Response
    {

        $form = $this->createForm(ProspectForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $Prospect = new Prospect();
            $Prospect->setName($data['name']);
            $Prospect->setAgent($data['agent']);
            $Prospect->setEmail($data['email']);
            $Prospect->setPhone($data['phone']);
            $prospectRepository->add($Prospect, true);


            return $this->redirectToRoute('app_prospect');
        }


        return $this->render('prospect/index.html.twig', [
            'ProspectForm' => $form->createView(),
        ]);
    }

    #[Route('/prospect', name: 'app_prospect', methods: ['GET', 'POST'])]
    public function index(ProspectRepository $prospectRepository)
    {
        $form = $this->createForm(ProspectForm::class);
        $prospects = $prospectRepository->findAll();
        return $this->render('prospect/index.html.twig', [
            'ProspectForm' => $form->createView(),
            'prospects' => $prospects,
        ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/prospect/delete/{id}', name: 'app_prospect_delete')]
    public function delete(ProspectRepository $prospectRepository,$id): Response
    {
        $prospect = $prospectRepository->find($id);
        $prospectRepository->remove($prospect);
        return $this->redirectToRoute('app_prospect');

    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/prospect/edit/{id}', name: 'app_prospect_edit')]
public function edit (ProspectRepository $prospectRepository,Prospect $prospect, Request $request): Response
{

    $form = $this->createForm(ProspectForm::class,$prospect);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()){

       $prospectRepository->add($prospect);
       return $this->redirectToRoute( 'app_prospect');
        }







    return $this->render('prospect/edit.html.twig',[
        'prospect'=>$prospect,
        'form'=>$form->createView()
    ]);



}

}
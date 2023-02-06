<?php

namespace App\Controller\invoice;

use App\Entity\PriceProposal;
use App\Entity\PriceProposalFeature;
use App\Form\PriceProposalFeatureType;
use App\Form\PriceProposalType;
use App\Repository\PriceProposalFeatureRepository;
use App\Repository\PriceProposalRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PriceProposalFeatureController extends AbstractController
{

    #[Route('/price/proposal/feature', name: 'app_price_proposal_feature')]
    public function index(PriceProposalFeatureRepository $priceProposalFeatureRepository): Response
    {
        $priceProposalFeatures = $priceProposalFeatureRepository->findAll();

        $form = $this->createForm(PriceProposalFeatureType::class);

        return $this->render('price_proposal_feature/index.html.twig', [
            'form' => $form->createView(),
            'priceProposalFeatures' => $priceProposalFeatures
        ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/price/proposal/feature/new', name: 'priceProposalFeature_new')]
    public function new (PriceProposalFeatureRepository $priceProposalFeatureRepository, Request $request,priceProposalRepository $priceProposalRepository ):Response
    {
        $form=$this->createForm(priceProposalType::class);
        $priceProposalFeatureForm =$this->createForm(priceProposalFeatureType::class);
        $form->handleRequest($request);
        $priceProposalFeatureForm->handleRequest($request);
        if ($priceProposalFeatureForm->isSubmitted()&& $priceProposalFeatureForm->isValid())
        {


            $data = $priceProposalFeatureForm->getData();



            $priceProposalFeature = new PriceProposalFeature();
            $priceProposalFeature->setPriceProposal($data["priceProposal"]);
            $priceProposalFeature->setDiscount($data["discount"]);
            $priceProposalFeature->setDescription($data["description"]);
            $priceProposalFeature->setQty($data["qty"]);
            $priceProposalFeature->setPrice($data["price"]);
            $priceProposalFeatureRepository->add($priceProposalFeature,true);
            return $this->redirectToRoute('app_price_proposal_feature');


        }
            return $this->redirectToRoute('app_price_proposal_feature');


        }



}

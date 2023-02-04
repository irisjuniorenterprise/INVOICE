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
    public function new (PriceProposalFeatureRepository $priceProposalFeatureRepository, Request $request ,PriceProposalRepository $priceProposalRepository):Response
    {

        $priceProposalForm = $this->createForm(PriceProposalType::class);
        $priceProposalFeatureForm = $this->createForm(PriceProposalFeatureType::class);

        $priceProposalForm->handleRequest($request);
        $priceProposalFeatureForm->handleRequest($request);

        if ($priceProposalFeatureForm->isSubmitted() && $priceProposalFeatureForm->isValid()) {


            $data = $priceProposalForm->getData();
            $data = $priceProposalFeatureForm->getData();

            $priceProposal = new PriceProposal();
            $priceProposalFeature = new PriceProposalFeature();
            $priceProposal->setDiscount($data["discount"]);



            $priceProposalRepository->add($priceProposal, true);
            $priceProposalFeature->setDescription($data["Description"]);
            $priceProposalFeature->setQty($data["Qty"]);
            $priceProposalFeature->setPrice($data["price"]);
            $priceProposalFeature->setDiscount($data["Discount"]);
            $priceProposalFeatureRepository->add($priceProposalFeature);


            return $this->redirectToRoute('app_price_proposal_feature');


        }
        return $this->redirectToRoute('app_price_proposal_feature');
    }
    #[Route('/price/proposal/feature/add', name: 'app_price_proposal_feature_add')]
    public function add(): Response
    {
        $priceProposalForm = $this->createForm(PriceProposalType::class);
        $priceProposalFeatureForm = $this->createForm(PriceProposalFeatureType::class);

        return $this->renderForm('price_proposal_feature/new.html.twig', [
            'priceProposalForm ' =>$priceProposalForm ,
            'priceProposalFeatureForm'=> $priceProposalFeatureForm


        ]);
    }
}

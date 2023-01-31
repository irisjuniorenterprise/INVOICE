<?php

namespace App\Controller\invoice;

use App\Entity\PriceProposal;
use App\Form\PriceProposalType;
use App\Repository\PriceProposalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PriceProposalController extends AbstractController
{
    #[Route('/priceProposal', name: 'app_price_proposal')]
    public function index(PriceProposalRepository $priceProposalRepository): Response
    {
        $priceProposals = $priceProposalRepository->findAll();

        $form = $this->createForm(PriceProposalType::class);

        return $this->render('price_proposal/index.html.twig', [
            'form' => $form->createView(),
            'priceProposals' => $priceProposals
        ]);
    }


    #[Route('/priceProposal/new', name: 'priceProposal_new', methods: "post")]
    public function newPriceProposal(PriceProposalRepository $priceProposalRepository, Request $request)
    {

        $form = $this->createForm(PriceProposalType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $data = $form->getData();

            $priceProposal = new PriceProposal();
            $priceProposal->setDiscount($data["discount"]);
            $priceProposal->setProspect($data["prospect"]);
            $priceProposal->setCreationDate(new \DateTimeImmutable());
            $priceProposal->setObject($data["object"]);
            $priceProposal->setCurrency($data["currency"]);


            $priceProposalRepository->add($priceProposal, true);

        }
        return $this->redirectToRoute('app_price_proposal');


    }


    #[Route('/priceProposal/edit/{id}', name: 'app_price_proposal_edit')]
    public function edit(PriceProposalRepository $priceProposalRepository, PriceProposal $priceProposal, Request $request): Response
    {

        $form = $this->createForm(PriceProposalType::class,$priceProposal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $priceProposalRepository->add($priceProposal, true);
            return $this->redirectToRoute('app_price_proposal');
        }


        return $this->render('price_proposal/edit.html.twig', [

            'form' => $form->createView()
        ]);


    }
}
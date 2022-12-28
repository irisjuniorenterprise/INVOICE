<?php

namespace App\Controller\invoice;

use App\Entity\Discount;
use App\Entity\Request;
use App\Form\DiscountType;
use App\Repository\DiscountRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class DiscountController extends AbstractController
{

    #[Route('/discount', name: 'app_discount')]
    public function discount(DiscountRepository $discountRepository): Response
    {

        $discounts=$discountRepository->findAll();

        $form = $this->createForm(DiscountType::class);

        return $this->render('discount/index.html.twig', [
            'form' => $form->createView(),
            'discounts'=>$discounts
        ]);
    }

    /**
     *
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/discount/new', name: 'discount_new')]
    public function new( DiscountRepository $discountRepository, \Symfony\Component\HttpFoundation\Request $request)
    {
        $form = $this->createForm(DiscountType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data =$form->getData();

            $discount=new Discount();
            $discount->setName($data->getName());
            $discount->setRate($data->getRate());
            $discountRepository->add($discount,true);

        }
        return $this->redirectToRoute('app_discount');


    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/discount/delete/{id}', name: 'discount_delete')]
    public function delete( Discount $discount,DiscountRepository $discountRepository): response
    {
        $discountRepository->remove($discount);

        return $this->redirectToRoute('app_discount');
    }



}

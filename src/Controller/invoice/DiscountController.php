<?php

namespace App\Controller\invoice;

use App\Entity\Discount;
use App\Form\DiscountType;
use App\Repository\DiscountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DiscountController extends AbstractController
{

    #[Route('/discount', name: 'app_discount')]
    public function discount(DiscountRepository $discountRepository): Response
    {

        $discounts = $discountRepository->findAll();

        $form = $this->createForm(DiscountType::class);

        return $this->render('discount/index.html.twig', [
            'form' => $form->createView(),
            'discounts' => $discounts
        ]);
    }

    /**
     *
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/discount/new', name: 'discount_new')]
    public function new(DiscountRepository $discountRepository, Request $request)
    {
        $form = $this->createForm(DiscountType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $discount = new Discount();
            $discount->setName($data->getName());
            $discount->setRate($data->getRate());
            $discountRepository->add($discount, true);

        }
        return $this->redirectToRoute('app_discount');


    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/discount/delete/{id}', name: 'discount_delete')]
    public function delete(Discount $discount, DiscountRepository $discountRepository): response
    {
        $discountRepository->remove($discount);

        return $this->redirectToRoute('app_discount');
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/discount/update/{id}', name: 'app_discount_update')]
    public function update(Request $request, Discount $discount, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DiscountType::class, $discount);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($discount);
            $entityManager->flush();
            $this->addFlash('success', 'discount updated');

            return $this->redirectToRoute('app_discount');
        }
        return $this->render('discount/edit.html.twig',
            [
                'discountForm' => $form->createView(),

            ]
        );
    }

    #[Route('/discount/edit/{id}', name: 'app_discount_edit')]
    public function edit(Discount $discount)
    {
        $form = $this->createForm(DiscountType::class, $discount);


        return $this->render('discount/edit.html.twig',
            [
                'discountForm' => $form->createView(),
                'idDiscount' => $form->getData()->getId()

            ]
        );

    }


}

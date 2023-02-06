<?php

namespace App\Form;

use App\Entity\PriceProposal;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
class PriceProposalFeatureType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('qty')
            ->add('discount')
            ->add('price')
           ->add('priceProposal',EntityType::class,["class"=>PriceProposal::class]);
    }

}
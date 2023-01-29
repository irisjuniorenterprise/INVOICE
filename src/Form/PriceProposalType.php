<?php
namespace App\Form;


use App\Entity\Discount;
use App\Entity\Prospect;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PriceProposalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('discount',EntityType::class, [
                // looks for choices from this entity
                'class' => Discount::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',])
            ->add('prospect',EntityType::class, [
                // looks for choices from this entity
                'class' => Prospect::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',])
            ->add('object')
            ->add('currency')
        ;
    }


}
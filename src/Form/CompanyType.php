<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom de l\'entreprise '))
            ->add('logo', TextType::class, array('label' => 'Logo', 'required' => false))
            ->add('address', TextType::class, array('label' => 'Adresse'))
            ->add('city', TextType::class, array('label' => 'Ville'))
            ->add('zipCode', TextType::class, array('label' => 'Code postal'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}

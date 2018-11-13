<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use PhpParser\Node\Stmt\Label;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Company;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array('label' => 'Prénom '))
            ->add('lastName', TextType::class, array('label' => 'Nom '))
            ->add('email', EmailType::class, array('label' => 'E-mail '))
            ->add('color', ColorType::class, array('label' => 'couleur associée'))
            ->add('numberOfHours', IntegerType::class, array('label' => 'Heures Hebdomadaire '))
            ->add('remainingLeave', IntegerType::class, array('label' => 'Jours de congés '))
            ->add('company');
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}

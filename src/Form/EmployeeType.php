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
use App\Repository\CompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class EmployeeType extends AbstractType
{
    protected $manager;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //we take the manager in order to select right company
        $manager = ($options['data'])->getCompany()->getManager();

        $this->manager = $manager;

        $builder
            ->add('firstName', TextType::class, array('label' => 'Prénom '))
            ->add('lastName', TextType::class, array('label' => 'Nom '))
            ->add('email', EmailType::class, array('label' => 'E-mail '))
            ->add('color', ColorType::class, array('label' => 'couleur associée'))
            ->add('numberOfHours', IntegerType::class, array('label' => 'Heures Hebdomadaire '))
            ->add('remainingLeave', IntegerType::class, array('label' => 'Jours de congés '))
            ->add('company', EntityType::class, array(
                'class' => Company::class,
                'query_builder' => function (CompanyRepository $er) {

                    $manager = $this->manager;

                    return $er->createQueryBuilder('c')
                    ->where('c.manager = :manager')
                    ->setParameter('manager', $manager);
                    
                },
                'choice_label' => 'name',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}

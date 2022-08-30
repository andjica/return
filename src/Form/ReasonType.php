<?php

namespace App\Form;

use App\Entity\Returns\ReasonSettings;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ReasonType extends AbstractType
{
    public $requestStack;
    public $doctrine;
    public $findfirst;
    public $firstreason; 

    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine)
    {
        $this->requestStack = $requestStack;
        $this->doctrine = $doctrine;


        //find for edit
        $this->findfirst = $doctrine->getRepository(ReasonSettings::class)->findOneBy(['id'=>1]);

        
        if( $this->findfirst)
        {
            $this->firstreason =  $this->findfirst->getName();
        }
        else
        {

            $this->firstreason = "Wrong item";
        }
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('first_reason', TextType::class, [
            'required' => 'First reason must be active',
            'attr' => ['class' => 'form-control'],
            'empty_data' => $this->firstreason,
            'data' => $this->firstreason,
            'label' => 'Reason 1',
        ]);
           
      
    }
    
}
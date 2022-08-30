<?php

namespace App\Form;

use App\Entity\ReasonSettings;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
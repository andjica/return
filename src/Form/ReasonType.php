<?php

namespace App\Form;

use App\Entity\ReasonSettings;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
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

    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine)
    {
        $this->requestStack = $requestStack;
        $this->doctrine = $doctrine;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

            $request = $this->requestStack->getCurrentRequest();

            $all = $request->request->all();
            // return dd($all);
            $reasons = $all['reasons']['text'];
            
            if(count($all['reasons']) == 1)
            {
                return dd("Jedan mora biti aktivan");
            }

            $active = $all['reasons']['active'];
            
           
           $reasonExist = array_filter($reasons);
           $activeExist = array_filter($active);
           
           $appended = array_merge($reasonExist,$activeExist); 

           $data = [
            'name' => $reasonExist,
            'active' => $activeExist 
           ];
           
           $act = [];
           foreach($data as $d)
           {
                $newReasons = new ReasonSettings();
                return dd($d->name);
                // $newReasons->setName($value);
                
           }
           return dd($act);
            foreach($reasonExist as $r)
            {
                $newReasons = new ReasonSettings();
               
                $newReasons->setName($r);
                
                
                foreach($activeExist as $a)
                { 
                    $newReasons->setActive(1);
                }
                $entityManager = $this->doctrine->getManager();
                $entityManager->persist($newReasons);
                $entityManager->flush();
            }
        //    return dd($reasonExist);


            
         
              
          
           
        });
      
    }
}
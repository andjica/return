<?php

namespace App\Controller;

use App\Entity\Common\Country;
use App\Entity\Returns\Status;
use App\Entity\Common\CostPrice;
use App\Form\ShippingOptionType;
use App\Entity\Reseller\Customer;
use App\Entity\Returns\PayCategory;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Common\CountryRepository;
use App\Entity\Reseller\ShippingPriceHistory;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Returns\ShippingOptionSettings;
use App\Repository\Common\CostPriceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Reseller\ShippingPriceRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShippingSettingsController extends AbstractController
{
    
    private $data = [];
    private $payments = [];
    public $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {

        $this->doctrine = $doctrine;
        //everybody will extends status for vertical sidebar -- for email template customization
        $this->data = $doctrine->getRepository(Status::class)->findAll();
        $this->payments = $doctrine->getRepository(PayCategory::class)->findAll();

  
    }
    
    /**
     * @Route("/settings/shipping", defaults={"_country": "", "_distributor": ""}, methods={"GET", "POST"}, name="settings_shipping")
     * @Route("/settings/shipping/{_country}", defaults={"_distributor": ""}, methods={"GET", "POST"}, name="settings_shipping_prices_country")
     * @Route("/settings/shipping/{_country}/{_distributor}", methods={"GET", "POST"}, name="settings_shipping_prices_country_distributor")
     */
    public function index(Request $request, ShippingPriceRepository $shipping_prices, CountryRepository $countries, string $_country, string $_distributor, CostPriceRepository $cost_prices): Response
    {


        $customer = $this->doctrine->getRepository(Customer::class)->findOneBy(['isReseller' => true]);
        $country = $countries->findOneBy(['iso_code' => $_country]);
        
        if (!($country instanceof Country)) {
            $country = $countries->findBy([], ['weight' => 'ASC'])[0];
            if (!$country instanceof Country) {
                throw new NotFoundHttpException();
            }
        }
        $countries_items = [];
        $shippingpriceHistory = $this->doctrine->getRepository(ShippingPriceHistory::class)->findOneBy(['customer'=>$customer->getId()]);

        //get countries
        foreach ($shipping_prices->countries($customer->getCurrentShippingPriceHistory()) as $s_price) {

            $c = $s_price->getCountry();

            if (!isset($countries_items[$c->getId()])) {

                $countries_items[$c->getId()] = [
                    'group' => $c->getIsEu() ? 'EU' : 'label.rest_of_the_world',
                    'code' => $c->getISOCode(),
                    'name' => $c->getName(),
                ];

            }

        }

        //get all distributors
        $distributors_items = [];
        
        foreach ($shipping_prices->distributors($customer->getCurrentShippingPriceHistory(), $country) as $distrib) {

            $distributors_items[] = [
                'id' => $distrib->getId(),
                'name' => $distrib->getName(),
                'key_name' => $distrib->getKeyName(),
            ];

        }
        
        $form = $this->createForm(ShippingOptionType::class);
        //get shipping options
        foreach ($shipping_prices->shippingOptions($customer->getCurrentShippingPriceHistory(),  $country, $distrib) as $shoption) {
           
            $shippingOption = $shoption->getShippingOption();
           
            $shippingOptionId = $shippingOption->getId();
            $currentShOptionSetting = $this->doctrine->getRepository(ShippingOptionSettings::class)->findOneBy(['shipping_option_id' => $shippingOptionId, 'country_id' => $country]);
            // return dd($currentShOptionSetting);
            // return dd($shippingOptionId);
            //add new Entity and check if exist in Entity table ShippingOptionSettings
            $form->add('enabled' . $shippingOptionId, CheckboxType::class, [
                'data' => ($currentShOptionSetting instanceof ShippingOptionSettings) ? $currentShOptionSetting->getEnabled() : false,
                'required' => false,
                'mapped' => false,
            ]);
            $distributor =  $shippingOption->getDistributor();
            $is_colli = $shippingOption->getIsColli();

          
        }
       
       
        //get selected distributors
        $distributors_selected = [];
        $options_items = [];
        foreach ($shipping_prices->distributors($customer->getCurrentShippingPriceHistory(), $country, $_distributor) as $distrib) {

            $distributors_selected[] =
            [
                'id' => $distrib->getId(),
                'name' => $distrib->getName(),
                'key_name' => $distrib->getKeyName(),
            ];
            if (!isset($options_items[$distrib->getId()])) {
                $options_items[$distrib->getId()] = [];
            }
            $options_items[$distrib->getId()][] = [
                'id' =>  $shippingOption->getId(),
                'name' =>  $shippingOption->getName()
            ];
        }

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            // return dd(5);
           foreach($shipping_prices->shippingOptions($customer->getCurrentShippingPriceHistory(),  $country, $distrib) as $sh)
           {
                $shippingOption = $sh->getShippingOption();
                $enabled = $form->get('enabled' . $shippingOption ->getId())->getData();

                $shippingOptionId = $shippingOption->getId();
                
                $currentShOptionSetting = $this->doctrine->getRepository(ShippingOptionSettings::class)->findOneBy(['shipping_option_id' => $shippingOptionId, 'country_id' => $country]);
                $em = $this->doctrine->getManager();

		        if($currentShOptionSetting instanceof ShippingOptionSettings) {
                    $currentShOptionSetting->setEnabled($enabled);
                    $currentShOptionSetting->setUpdatedAt(new \DateTime());
                }
                else {
                    $currentShOptionSetting = new ShippingOptionSettings();
                    $currentShOptionSetting->setShippingOptionId($shippingOptionId);
                    $countryId = $country->getId();
                    $currentShOptionSetting->setCountryId($countryId);
                    $currentShOptionSetting->setEnabled($enabled);
                    $currentShOptionSetting->setCreatedAt(new \DateTime());
                    $em->persist($currentShOptionSetting);

                }
                  
                    $em->flush();

           }
        }
        


        return $this->renderForm('shipping_settings/index.html.twig', [
            'status' => $this->data,
            'payments' => $this->payments,
            'countries' => $countries_items,
            'country_selected' => $country,
            'distributors' => $distributors_items,
            'distributor_selected' => $_distributor,
            'distributors_selected' => $distributors_selected,
            'options' => $options_items,
            'form' => $form
        ]);
    }

    
}

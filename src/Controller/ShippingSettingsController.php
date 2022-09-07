<?php

namespace App\Controller;

use App\Entity\Common\Country;
use App\Entity\Returns\Status;
use App\Entity\Reseller\Customer;
use App\Entity\Returns\PayCategory;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Common\CountryRepository;
use App\Entity\Reseller\ShippingPriceHistory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Reseller\ShippingPriceRepository;
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
     * @Route("/settings/shipping", defaults={"_country": "", "_distributor": ""}, methods={"GET", "POST"}, name="settings_shipping_prices")
     * @Route("/settings/shipping/{_country}", defaults={"_distributor": ""}, methods={"GET", "POST"}, name="settings_shipping_prices_country")
     * @Route("/settings/shipping/{_country}/{_distributor}", methods={"GET", "POST"}, name="settings_shipping_prices_country_distributor")
     */
    public function index(ShippingPriceRepository $shipping_prices, CountryRepository $countries, string $_country, string $_distributor): Response
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
       
        $distributors_items = [];
        
        foreach ($shipping_prices->distributors($customer->getCurrentShippingPriceHistory(), $country) as $distrib) {

            $distributors_items[] = [
                'id' => $distrib->getId(),
                'name' => $distrib->getName(),
                'key_name' => $distrib->getKeyName(),
            ];

        }
        
        $shippingOptions = [];


        foreach ($shipping_prices->shippingOptions($customer->getCurrentShippingPriceHistory(),  $country, $distrib) as $shoption) {

            return  dd( $shoption->getShippingOption());

            

        }
        
      

        
        $distributors_selected = [];
        $options_items = [];

       

        
        


        return $this->render('shipping_settings/index.html.twig', [
            'status' => $this->data,
            'payments' => $this->payments,
            'countries' => $countries_items,
            'country_selected' => $country,
        ]);
    }

    
}

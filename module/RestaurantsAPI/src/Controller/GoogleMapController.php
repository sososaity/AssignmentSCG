<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace RestaurantsAPI\Controller;

use RestaurantsAPI\Model\AddressTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Client;



class GoogleMapController extends AbstractActionController
{

    private $table;

    public function __construct(AddressTable $table)
    {
        $this->table = $table;
    }

    public function findRestaurantsAction()
    {

        // Get parameter
        $locationTarget     = $this->params()->fromRoute('location');

        // Clear data formate
        $locationTarget     = strtolower($locationTarget);

        $isHaveDataAlready  = $this->table->nearRestaurants($locationTarget);
        if($isHaveDataAlready){

            // Show data from database
            echo $isHaveDataAlready->detail;
        }else{

            // Setup parameter for Google API
            $responseType   = "json";
            $apiKey         = "AIzaSyCo00B_ODFPpZbR3LY_gueflhE8rO-_fhs";
            $apiUrl         = "https://maps.googleapis.com/maps/api/place/textsearch/$responseType?query=restaurants+in+$locationTarget&key=$apiKey";

            // Send request
            $client         = new Client($apiUrl);
            $responseString = $client->send()->getBody();
            $responseObject = json_decode($responseString, true);

            // Check status of service
            switch($responseObject["status"]){
                case "OK"   :   // New location case to save data
                                $this->table->addNewData($locationTarget, $responseString);
                                echo $responseString;
                                break;

                default     :   // Other Case
                                echo $responseString;
                                break;
            }

        }

        return $this->getResponse();
    }


}

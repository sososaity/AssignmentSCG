<?php
namespace RestaurantsAPI\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class AddressTable
{
    private $tableGateway;
    
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function nearRestaurants($location)
    {

        // Clean format
        $location = strtolower($location);

        // find detail of this location
        $dataSet = $this->tableGateway->select(["placeName" => $location]);
        $result  = $dataSet->current();

        return $result;
    }

    public function addNewData($location, $detail){

        $address = [
            'placeName' => strtolower($location),
            'detail'  => $detail,
        ];

        $this->tableGateway->insert($address);
    }

}
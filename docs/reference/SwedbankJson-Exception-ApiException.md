SwedbankJson\Exception\ApiException
===============






* Class name: ApiException
* Namespace: SwedbankJson\Exception
* Parent class: RuntimeException
* This class implements: [SwedbankJson\Exception\SwedbankJsonException](SwedbankJson-Exception-SwedbankJsonException.md)




Properties
----------


### $_response

    private mixed $_response





* Visibility: **private**


### $_errorMessages

    private mixed $_errorMessages = array()





* Visibility: **private**


Methods
-------


### __construct

    mixed SwedbankJson\Exception\ApiException::__construct($response, $code, \Exception $previous)





* Visibility: **public**


#### Arguments
* $response **mixed**
* $code **mixed**
* $previous **Exception**



### getResponse

    string SwedbankJson\Exception\ApiException::getResponse()





* Visibility: **public**




### getErrorMessages

    array SwedbankJson\Exception\ApiException::getErrorMessages()





* Visibility: **public**




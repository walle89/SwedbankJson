SwedbankJson\Exception\ApiException
===============

Class ApiException




* Class name: ApiException
* Namespace: SwedbankJson\Exception
* Parent class: RuntimeException
* This class implements: [SwedbankJson\Exception\SwedbankJsonException](SwedbankJson-Exception-SwedbankJsonException.md)




Properties
----------


### $_response

    private string $_response





* Visibility: **private**


### $_errorMessages

    private array $_errorMessages = array()





* Visibility: **private**


Methods
-------


### __construct

    mixed SwedbankJson\Exception\ApiException::__construct(string $response, integer $code, \Exception|null $previous)

ApiException constructor.



* Visibility: **public**


#### Arguments
* $response **string** - &lt;p&gt;Meddelande&lt;/p&gt;
* $code **integer** - &lt;p&gt;Felkod&lt;/p&gt;
* $previous **Exception|null** - &lt;p&gt;Exception föregående&lt;/p&gt;



### getResponse

    string SwedbankJson\Exception\ApiException::getResponse()

Hämta meddelande



* Visibility: **public**




### getErrorMessages

    array SwedbankJson\Exception\ApiException::getErrorMessages()

Hämta felmeddelanden



* Visibility: **public**




<?php
/**
 * Wrapper för Swedbanks stänga API för mobilappar
 *
 * @package SwedbankJson
 * @author  Eric Wallmander
 *          Date: 2016-01-04
 *          Time: 23:26
 */

namespace SwedbankJson\Exception;

/**
 * Class ApiException
 * @package SwedbankJson\Exception
 */
class ApiException extends \RuntimeException implements SwedbankJsonException
{
    /**
     * @var string Meddelande
     */
    private $_response;

    /**
     * @var array Felmeddelanden
     */
    private $_errorMessages = [];

    /**
     * ApiException constructor.
     *
     * @param string          $response Meddelande
     * @param int             $code     Felkod
     * @param \Exception|null $previous Exception föregående
     */
    public function __construct($response, $code = 0, \Exception $previous = null)
    {
        $this->_response = $response;

        $message = $body = $response->getBody();
        $result  = json_decode($body);

        if (json_last_error() === JSON_ERROR_NONE)
        {
            foreach ($result->errorMessages as $type => $data)
            {
                foreach($data as $ii => $error)
                {
                    $index = $ii + 1;
                    $temp  = "$type ($index): ";

                    if (isset($error->code))
                        $temp .= $error->code.' - ';

                    $temp .= $error->message;

                    $this->_errorMessages[] = $temp;
                }
            }

            $message = implode('; ', $this->_errorMessages);
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Hämta meddelande
     *
     * @return string Meddelande
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * Hämta felmeddelanden
     *
     * @return array Felmeddelanden
     */
    public function getErrorMessages()
    {
        return $this->_errorMessages;
    }
}

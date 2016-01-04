<?php

namespace SwedbankJson\Exception;

class ApiException extends \RuntimeException implements SwedbankJsonException
{
    private $_response;

    private $_errorMessages = [];

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
     * @return string
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->_errorMessages;
    }
}

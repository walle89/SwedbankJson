<?php
namespace SwedbankJson\Exception;

/**
 * Class ApiException
 * @package SwedbankJson\Exception
 */
class ApiException extends \RuntimeException implements SwedbankJsonException
{
    /** @var string Exception message */
    private $_response;

    /** @var array Error messages */
    private $_errorMessages = [];

    /**
     * ApiException constructor.
     *
     * @param string          $response The Exception message to throw.
     * @param int             $code     The Exception code.
     * @param \Exception|null $previous The previous exception used for the exception chaining.
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
                foreach ($data as $ii => $error)
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
     * Get response message
     *
     * @return string Response message
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * Get error messages
     *
     * @return array Error messages
     */
    public function getErrorMessages()
    {
        return $this->_errorMessages;
    }
}

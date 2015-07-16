<?php

/*
 * LICENSE: This source file is subject to version 4.0 of the CC BY 4.0 license
 * that is available through the world-wide-web at the following URI:
 * http://creativecommons.org/licenses/by/4.0/.  If you did not receive a copy of
 * the CC BY 4.0 License and are unable to obtain it through the web, please
 * send a note to daniel.boorn@gmail.com so we can mail you a copy immediately.
 *
 * @author Daniel Boorn <daniel.boorn@gmail.com>
 * @license http://creativecommons.org/licenses/by/4.0/ CC BY 4.0 *
 * @package SafeEFT
 */

namespace SageEFT;

/**
 * Class SoapClient
 * @package SageEFT
 */
class SoapClient extends \SoapClient
{

    /**
     * @param mixed $wsdl
     * @param array $options
     */
    public function __construct($wsdl, array $options = null)
    {
        $options = array_merge((array)$options, array('trace' => 1));
        parent::__construct($wsdl, $options);
    }

    /**
     * @param string $function_name
     * @param array $arguments
     * @param null $options
     * @param null $input_headers
     * @param null $output_headers
     * @return mixed
     * @throws Exception
     */
    public function __soapCall($function_name, $arguments, $options = null, $input_headers = null, &$output_headers = null)
    {
        try {
            return parent::__soapCall($function_name, $arguments, $options, $input_headers, $output_headers);
        } catch (\SoapFault $e) {
            $trace = array(
                $this->__getLastRequestHeaders(),
                $this->__getLastRequest(),
                $this->__getLastResponse(),
                $this->__getLastResponseHeaders()
            );
            throw new Exception("Soap: " . $e->getMessage(), $e->getCode(), $trace);
        }
    }
}
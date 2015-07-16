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
 * Class Exception
 * @package SageEFT
 */
class Exception extends \Exception
{

    /**
     * @var
     */
    protected $soapTrace;

    /**
     * @param null $message
     * @param int $code
     * @param null $soapTrace
     * @param null $previous
     */
    public function __construct($message = null, $code = 0, $soapTrace = null, $previous = null)
    {
        $this->setSoapTrace($soapTrace);
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getSoapTrace()
    {
        return $this->soapTrace;
    }

    /**
     * @param mixed $soapTrace
     */
    public function setSoapTrace($soapTrace)
    {
        $this->soapTrace = $soapTrace;
    }
}
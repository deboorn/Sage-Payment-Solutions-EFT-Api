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

require_once 'AuthGatewayHeader.php';
require_once 'Exception.php';
require_once 'Response.php';
require_once 'SoapClient.php';

/**
 * Class Api
 * @package SageEFT
 */
class Api
{
    /**
     *
     */
    const API_WSDL = 'https://demo.eftchecks.com/webservices/authgateway.asmx?WSDL';
    /**
     *
     */
    const DATA_PACKET_TRANS = 'xml/data_packet_transaction.xml';
    /**
     *
     */
    const DATA_PACKET_GET_TOKEN = 'xml/data_packet_get_token.xml';
    /**
     *
     */
    const IDENTIFIER_Authorize = 'A';
    /**
     *
     */
    const IDENTIFIER_Void = 'V';
    /**
     *
     */
    const IDENTIFIER_Override = 'O';
    /**
     *
     */
    const IDENTIFIER_Payroll = 'P';
    /**
     *
     */
    const IDENTIFIER_Reversal = 'F';

    /**
     * @var
     */
    protected static $dataPacketXml;
    /**
     * @var
     */
    protected static $tokenDataPackXml;
    /**
     * @var bool
     */
    public $debug = false;
    /**
     * @var
     */
    protected $username;
    /**
     * @var
     */
    protected $password;
    /**
     * @var
     */
    protected $terminalId;
    /**
     * @var
     */
    protected $soap;

    /**
     * @param $username
     * @param $password
     * @param $terminalId
     */
    public function __construct($username, $password, $terminalId)
    {
        $this->username = $username;
        $this->password = $password;
        $this->terminalId = $terminalId;
        $this->setSoap();
    }

    /**
     * @return mixed
     */
    public static function getDataPacketXml()
    {
        return self::$dataPacketXml;
    }

    /**
     * @param mixed $dataPacketXml
     */
    public static function setDataPacketXml($dataPacketXml)
    {
        self::$dataPacketXml = $dataPacketXml;
    }

    /**
     * @return mixed
     */
    public function getTerminalId()
    {
        return $this->terminalId;
    }

    /**
     * @param mixed $terminalId
     */
    public function setTerminalId($terminalId)
    {
        $this->terminalId = $terminalId;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getSoap()
    {
        return $this->soap;
    }

    /**
     * @return $this
     */
    public function setSoap()
    {
        $this->soap = new SoapClient(static::API_WSDL);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param boolean $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * Magic method to trigger soap endpoints
     *
     * @param $name
     * @param array $arguments
     * @return array
     */
    public function __call($name, array $arguments)
    {
        return $this->fetch(count($arguments) ? current($arguments) : array(), $name);
    }

    /**
     * Fetch soap endpoint with custom soap header with username/password/terminal id
     *
     * @param array $params
     * @param $endpoint
     * @return array
     */
    public function fetch(array $params, $endpoint)
    {
        $response = $this->setSoapHeaders()->soap->{$endpoint}($params);


        if ($this->debug) {
            var_dump( //example of how to debug soap
                $params,
                $endpoint,
                $this->soap->__getLastRequestHeaders(),
                $this->soap->__getLastRequest(),
                $this->soap->__getLastResponse(),
                $this->soap->__getLastResponseHeaders()
            );
        }

        if (isset($response->{"{$endpoint}Result"})) {
            // return xml result response
            if (isset($response->{"{$endpoint}Result"})) {
                $result = new \SimpleXMLElement($response->{"{$endpoint}Result"});
                return (array)$result;
            }
        }
        // return xml response
        return $response;
    }

    /**
     * Set AuthGatewayHeader for Soap with username, password and terminial id
     *
     * @return $this
     */
    protected function setSoapHeaders()
    {
        $namespace = 'http://tempuri.org/GETI.eMagnus.WebServices/AuthGateway';
        $data = new AuthGatewayHeader($this->username, $this->password, $this->terminalId);
        $header = new \SoapHeader($namespace, 'AuthGatewayHeader', $data);
        $this->soap->__setSoapHeaders(array($header));
        return $this;
    }

    /**
     * Return list of Soap API endpoints from WSDL
     *
     * @return mixed
     */
    public function getEndpoints()
    {
        return $this->soap->__getFunctions();
    }

    /**
     * Return SimpleXMLElement for DataPacket based on xml template in terminal settings (cached locally)
     *
     * @param bool $cacheToFile set true to fetch and cache to file (if not already cached)
     * @return \SimpleXMLElement
     */
    public function getDataPacketModel()
    {
        if (!self::$dataPacketXml) {
            $this->cacheDataPacketModel();
        }
        return new \SimpleXMLElement(self::$dataPacketXml);
    }

    /**
     * Return SimpleXMLElement for getToken DataPacket stored locally (not obtainable via terminal settings)
     *
     * @return \SimpleXMLElement|string
     */
    public function getTokenDataPackModel()
    {
        if (!self::$tokenDataPackXml) {
            $dir = dirname(__FILE__) . "/";
            self::$tokenDataPackXml = file_get_contents($dir . self::DATA_PACKET_GET_TOKEN);
        }
        return new \SimpleXMLElement(self::$tokenDataPackXml);
    }

    /**
     * Cache data packet from terminal settings if not found locally
     *
     * @return string
     */
    public function cacheDataPacketModel()
    {
        $dir = dirname(__FILE__) . "/";
        $buffer = file_get_contents($dir . static::DATA_PACKET_TRANS);
        if (!empty($buffer)) {
           return self::$dataPacketXml = $buffer;
        }
        $terminalSettings = $this->GetCertificationTerminalSettings();
        self::$dataPacketXml = file_get_contents($terminalSettings['XML_TEMPLATE_PATH']);
        file_put_contents($dir . static::DATA_PACKET_TRANS, self::$dataPacketXml);
        return self::$dataPacketXml;
    }


}
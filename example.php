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

/**
 * Example usage of SageEFT API
 *
 * Class Example
 */
class Example
{

    /**
     * How to get api endpoints
     *
     * @param \SageEFT\Api $sage
     * @return mixed
     */
    public static function getEndpoints(SageEFT\Api $sage)
    {
        return $sage->getEndpoints();
    }

    /**
     * How to get terminal settings
     *
     * @param \SageEFT\Api $sage
     * @return mixed
     */
    public static function getTerminalSettings(SageEFT\Api $sage)
    {
        return $sage->GetCertificationTerminalSettings();
    }

    /**
     * How to get transaction packet template
     *
     * @param \SageEFT\Api $sage
     * @return SimpleXMLElement
     */
    public static function getPacketTemplate(SageEFT\Api $sage)
    {
        # How to get data packet template/model
        return $sage->getDataPacketModel();
    }

    /**
     * How to create transaction packet
     *
     * @param array $testData
     * @param \SageEFT\Api $sage
     * @return SimpleXMLElement
     */
    public static function createTransactionPacket(array $testData, SageEFT\Api $sage)
    {
        # How to create data packet
        $data = $sage->getDataPacketModel();
        $data->attributes()->REQUEST_ID = uniqid();
        $data->TRANSACTION->MERCHANT->TERMINAL_ID = $sage->getTerminalId();
        $data->TRANSACTION->PACKET->IDENTIFIER = $sage::IDENTIFIER_Authorize;
        // checking account info
        $data->TRANSACTION->PACKET->ACCOUNT->ROUTING_NUMBER = $testData['routing'];
        $data->TRANSACTION->PACKET->ACCOUNT->ACCOUNT_NUMBER = $testData['account'];
        $data->TRANSACTION->PACKET->ACCOUNT->ACCOUNT_TYPE = $testData['type'];
        // consumer info
        $data->TRANSACTION->PACKET->CONSUMER->FIRST_NAME = $testData['first_name'];
        $data->TRANSACTION->PACKET->CONSUMER->LAST_NAME = $testData['last_name'];
        $data->TRANSACTION->PACKET->CONSUMER->ADDRESS1 = $testData['address'];
        $data->TRANSACTION->PACKET->CONSUMER->CITY = $testData['city'];
        $data->TRANSACTION->PACKET->CONSUMER->STATE = $testData['state'];
        $data->TRANSACTION->PACKET->CONSUMER->ZIP = $testData['zip'];
        $data->TRANSACTION->PACKET->CONSUMER->IP_ADDRESS = $testData['ip'];
        // check amount
        $data->TRANSACTION->PACKET->CHECK->CHECK_AMOUNT = 24.55;
        //var_dump($data->asXML());
        return $data;
    }


    /**
     * How to create transaction packet with token
     *
     * @param string $token
     * @param array $testData
     * @param \SageEFT\Api $sage
     * @return SimpleXMLElement
     */
    public static function createTransactionPacketWithToken($token, array $testData, SageEFT\Api $sage)
    {
        # How to create data packet
        $data = $sage->getDataPacketModel();
        $data->attributes()->REQUEST_ID = uniqid();
        $data->TRANSACTION->MERCHANT->TERMINAL_ID = $sage->getTerminalId();
        $data->TRANSACTION->PACKET->IDENTIFIER = $sage::IDENTIFIER_Authorize;
        // unset checking account info
        unset(
            $data->TRANSACTION->PACKET->ACCOUNT->ROUTING_NUMBER,
            $data->TRANSACTION->PACKET->ACCOUNT->ACCOUNT_NUMBER,
            $data->TRANSACTION->PACKET->ACCOUNT->ACCOUNT_TYPE
        );
        // add token
        $data->TRANSACTION->PACKET->ACCOUNT->TOKEN = $token;
        // consumer info
        $data->TRANSACTION->PACKET->CONSUMER->FIRST_NAME = $testData['first_name'];
        $data->TRANSACTION->PACKET->CONSUMER->LAST_NAME = $testData['last_name'];
        $data->TRANSACTION->PACKET->CONSUMER->ADDRESS1 = $testData['address'];
        $data->TRANSACTION->PACKET->CONSUMER->CITY = $testData['city'];
        $data->TRANSACTION->PACKET->CONSUMER->STATE = $testData['state'];
        $data->TRANSACTION->PACKET->CONSUMER->ZIP = $testData['zip'];
        $data->TRANSACTION->PACKET->CONSUMER->IP_ADDRESS = $testData['ip'];
        // check amount
        $data->TRANSACTION->PACKET->CHECK->CHECK_AMOUNT = 24.55;
        //var_dump($data->asXML());
        return $data;
    }

    /**
     * How to validate transaction data packet
     *
     * @param SimpleXMLElement $dataPacket
     * @param \SageEFT\Api $sage
     * @return mixed
     */
    public static function validateDataPacket(SimpleXMLElement $dataPacket, SageEFT\Api $sage)
    {
        return $sage->AuthGatewayCertification(array('DataPacket' => $dataPacket->asXML()));
    }

    /**
     * How to process single check with checking account info
     *
     * @param SimpleXMLElement $dataPacket
     * @param \SageEFT\Api $sage
     */
    public static function processSingleCheck(SimpleXMLElement $dataPacket, SageEFT\Api $sage)
    {
        return $sage->ProcessSingleCertificationCheck(array('DataPacket' => $dataPacket->asXML()));
    }

    /**
     * How to process single check with token
     *
     * @param SimpleXMLElement $dataPacket
     * @param \SageEFT\Api $sage
     */
    public static function processSingleCheckWithToken(SimpleXMLElement $dataPacket, SageEFT\Api $sage)
    {
        return $sage->ProcessSingleCertificationCheckWithToken(array('DataPacket' => $dataPacket->asXML()));
    }

    /**
     * How to obtain checking account token
     *
     * @param array $testData
     * @param \SageEFT\Api $sage
     */
    public static function getToken(array $testData, SageEFT\Api $sage)
    {
        $data = $sage->getTokenDataPackModel();
        $data->attributes()->REQUEST_ID = uniqid();
        $data->TRANSACTION->MERCHANT->TERMINAL_ID = $sage->getTerminalId();
        // checking account info
        $data->TRANSACTION->PACKET->ACCOUNT->ROUTING_NUMBER = $testData['routing'];
        $data->TRANSACTION->PACKET->ACCOUNT->ACCOUNT_NUMBER = $testData['account'];
        $data->TRANSACTION->PACKET->ACCOUNT->ACCOUNT_TYPE = $testData['type'];
        //var_dump($data->asXML());

        $result = $sage->GetCertificationToken(array('DataPacket' => $data->asXML()));
        return $result;
    }

}




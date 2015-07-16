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


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require_once 'SageEFT/Api.php';
require_once 'example.php';

$sage = new SageEFT\Api(
    '', // username
    '', // password
    '2318' // terminal id
);

// Test Data
$testData = array(
    'routing'    => '490000018',
    'account'    => '24413815',
    'type'       => 'Checking',
    'first_name' => 'John',
    'last_name'  => 'Doe',
    'address'    => '123 Someplace Ave',
    'city'       => 'Myrtle Beach',
    'state'      => 'SC',
    'zip'        => '29579',
    'ip'         => '66.210.223.130',
);

// How to list endpoints from wsdl
$endpoints = Example::getEndpoints($sage);
var_dump($endpoints);

// How to get terminal settings
$terminalSettings = Example::getTerminalSettings($sage);
var_dump($terminalSettings);

// How to get data packet template
$dataPacket = Example::getPacketTemplate($sage);
var_dump($dataPacket, $dataPacket->asXML());

// How to create transaction data packet
$transDataPacket = Example::createTransactionPacket($testData, $sage);
var_dump($transDataPacket, $transDataPacket->asXML());

// How to verify data packet for development debug purposes
$results = Example::validateDataPacket($transDataPacket, $sage);
var_dump($results);

// How to process single check with checking info
$result = Example::processSingleCheck($transDataPacket, $sage);
var_dump($result);

// How to tokenize account info
$result = Example::getToken($testData, $sage);
var_dump($result['TOKEN']);

// How to create transaction data packet with token
$transDataPacketWithToken = Example::createTransactionPacketWithToken($result['TOKEN'], $testData, $sage);
var_dump($transDataPacketWithToken, $transDataPacketWithToken->asXML());

// How to verify data packet with token for development debug purposes
$results = Example::validateDataPacket($transDataPacketWithToken, $sage);
var_dump($results);

// How to process single check with token
$result = Example::processSingleCheckWithToken($transDataPacketWithToken, $sage);
var_dump($result);


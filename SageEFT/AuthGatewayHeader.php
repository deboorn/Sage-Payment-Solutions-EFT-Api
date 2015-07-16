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
 * Class AuthGatewayHeader
 * @package SageEFT
 */
class AuthGatewayHeader
{

    /**
     * @var string $UserName
     */
    protected $UserName = null;

    /**
     * @var string $Password
     */
    protected $Password = null;

    /**
     * @var int $TerminalID
     */
    protected $TerminalID = null;

    /**
     * @param string $UserName
     * @param string $Password
     * @param int $TerminalID
     */
    public function __construct($UserName, $Password, $TerminalID)
    {
        $this->UserName = $UserName;
        $this->Password = $Password;
        $this->TerminalID = $TerminalID;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->UserName;
    }

    /**
     * @param string $UserName
     * @return RemoteAccessHeader
     */
    public function setUserName($UserName)
    {
        $this->UserName = $UserName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->Password;
    }

    /**
     * @param string $Password
     * @return RemoteAccessHeader
     */
    public function setPassword($Password)
    {
        $this->Password = $Password;
        return $this;
    }

    /**
     * @return int
     */
    public function getTerminalID()
    {
        return $this->TerminalID;
    }

    /**
     * @param int $TerminalID
     * @return RemoteAccessHeader
     */
    public function setTerminalID($TerminalID)
    {
        $this->TerminalID = $TerminalID;
        return $this;
    }

}

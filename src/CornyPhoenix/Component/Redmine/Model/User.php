<?php

namespace CornyPhoenix\Component\Redmine\Model;

use Carbon\Carbon;

class User
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $mail;

    /**
     * @var Carbon
     */
    private $created_on;

    /**
     * @var Carbon
     */
    private $last_login_on;

    /**
     * @var string
     */
    private $api_key;

    /**
     * @var array
     */
    private $memberships = array();

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return $this
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreatedOn()
    {
        return $this->created_on;
    }

    /**
     * @param Carbon $created_on
     * @return $this
     */
    public function setCreatedOn(Carbon $created_on)
    {
        $this->created_on = $created_on;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getLastLoginOn()
    {
        return $this->last_login_on;
    }

    /**
     * @param Carbon $last_login_on
     * @return $this
     */
    public function setLastLoginOn(Carbon $last_login_on)
    {
        $this->last_login_on = $last_login_on;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * @param string $api_key
     * @return $this
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
        return $this;
    }

    /**
     * @return array
     */
    public function getMemberships()
    {
        return $this->memberships;
    }

    /**
     * @param array $memberships
     * @return $this
     */
    public function setMemberships(array $memberships)
    {
        $this->memberships = $memberships;
        return $this;
    }
}
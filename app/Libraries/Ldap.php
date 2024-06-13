<?php

namespace App\Libraries;

class Ldap
{

    protected $domain, $port, $baseDn, $server;

    public function __construct()
    {
        $this->server = config('ldap.server');
        $this->domain = config('ldap.domain');
        $this->port   = config('ldap.port');
        $this->baseDn = config('ldap.baseDN');
    }

    public function connect()
    {
        $ldap = ldap_connect($this->server, (int)$this->port);
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        return $ldap;
    }

    public function auth($username, $password)
    {

        $ldap       = (new self)->connect();
        $bind       = @ldap_bind($ldap, $username, $password);

        if ($bind) {

            $username = str_replace('@' . $this->domain, '', $username);

            $filter = "(SAMAccountName=$username)";

            $data = ldap_search($ldap, $this->baseDn, $filter);

            $result = ldap_get_entries($ldap, $data);

            return $result;
        } else {
            return false;
        }
    }

    public function bindingAuth($username, $password)
    {
        $ldap       = (new self)->connect();
        $username   = $username.'@'.$this->domain;
        $bind       = @ldap_bind($ldap, $username, $password);

        if ($ldap) {
            if ($bind) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

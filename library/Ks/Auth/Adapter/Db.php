<?php

class Ks_Auth_Adapter_Db implements Zend_Auth_Adapter_Interface{

    private $username;
    private $password;
    private $options;
    /**
     *
     * @var User
     */
    public $user;
    public function __construct($username, $password, $options)
    {
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
    }
    /**
     * @var UserQuery $queryClass
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $queryClass = $this->options . 'Query';
        $this->user = UserQuery::create()
            ->condition('cond1', $this->options . '.username = ?', $this->username)
            ->condition('cond2', $this->options . '.password = ?', $this->password)
            ->where(array('cond1','cond2'), 'and')
            ->findOne();
        if($this->user) {
            $result =  $this->result(Zend_Auth_Result::SUCCESS);
        }
        else{
            $result = $this->result(Zend_Auth_Result::FAILURE);
        }
        return $this->result($result);
    }
    private function result($code, $messages = array())
    {
        if (!is_array($messages)) {
            $messages = array($messages);
        }
        return new Zend_Auth_Result(
            $code,
            $this->user,
            $messages
        );
    }
}

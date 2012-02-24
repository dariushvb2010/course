<?php
class jLazyLoader
{
    private $__classname;
    private $__args;
    private $__loaded=false;
    private $__object;
    function __construct($Classname,$ConstructorArgs=null)
    {
        $args=func_get_args();
        array_shift($args);
        $this->__classname=$Classname;
        $this->__args=$args;
    }
    function __call($name,$args)
    {
         if (!$this->__loaded)
         {
             $this->__loaded=true;
             $this->__object=new $this->__classname;
             if (method_exists($this->__object,"__construct"))
                 call_user_func_array(array($this->__object,"__construct"), $this->__args);
             unset($this->__args);
             unset($this->__classname);
         }
         return call_user_func_array(array($this->__object,$name),$args);
    }
}
?>
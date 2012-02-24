<?php
abstract class BaseApplicationClass 
{
    /**
     * Application
     *
     * @var ApplicationController
     */
    protected $App;
    
    /**
     * Database Access Layer
     *
     * @var DBAL
     */
    protected $DB;
    function __construct($App=null)
    {
    	if ($App===null)
    		$App=j::$App;
        $this->App=$App;
        $this->DB=$App->DB;
    }
}

abstract class BaseModelClass extends BaseApplicationClass {
	
}
abstract class JModel extends BaseModelClass {}

?>
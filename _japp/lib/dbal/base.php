<?php

abstract class DBAL_Base {
	public $QueryCount;
    public $QueryTime;
    private $tQueryTime; //for QueryTimeIn()
    private function GetMicroTime()
    {
        $match=explode(" ",microtime());
        return  ($match[1] + $match[0])*1000000;
        
    }
    
    function QueryTimeIn()
    {
        $this->tQueryTime=$this->GetMicroTime();
    }
    function QueryTimeOut()
    {
        $this->QueryTime+=($this->GetMicroTime()-$this->tQueryTime)/1000000.0;
    }
    
    function QueryCount ()
    {
        return $this->QueryCount;
    }

    
    
    function AutoQuery ($QueryString)
    {
        $this->Query($QueryString);
        return $this->AllResult();
    }
    
	
}
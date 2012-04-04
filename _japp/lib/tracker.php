<?php

class WebTracker extends BaseApplicationClass 
{
    function __construct ()
    {
        $this->Load();
    }
    function __destruct ()
    {
        $this->LoadTime=$this->Calculate($this->LoadStart);
        $this->Save();
    }
    public $LoadStart;
    public $LoadEnd;
    public $LoadTimeMicroseconds;
    public $LoadTime;
    
    function GetTime ($ReturnMicroseconds = true)
    {
        $match=array();
        preg_match("/^(.*?) (.*?)$/", microtime(), $match);
        $utime = $match[2] + $match[1];
        if ($ReturnMicroseconds) {
            $utime *= 1000000;
        }
        return $utime;
    }
    function Save ()
    {
        //TODO: log this somewhere!
    }
    function Load ()
    {
        $this->LoadStart = $this->GetTime();
    }
    function Calculate ($Start=null,$End=null)
    {
        if ($Start===null) $Start=$this->LoadStart;
        if ($End===null) $End=$this->LoadEnd= $this->GetTime();
        $this->LoadTimeMicroseconds = $End - $Start;
        return $this->LoadTime = $this->LoadTimeMicroseconds / 1000000.0;
    }
    //Queries
    public function PageLoadTime ()
    {

        return $this->Calculate();
    }
}
?>
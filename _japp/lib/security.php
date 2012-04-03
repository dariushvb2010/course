<?php
class SecurityInterface extends BaseApplicationClass
{
    /**
     * Enter description here...
     *
     * @var HTMLawed
     */
    protected $HTMLawed = null;
    private function isInternaljPathValidCharacter ($c)
    {
        return ($c >= "a" and $c <= "z") or ($c >= "A" and $c <= "Z") or ($c >= "0" and $c <= "9") or ($c == "_") or ($c == "-");
    }
    private function isExternaljPathValidCharacter ($c)
    {
        return ($c >= "a" and $c <= "z") or ($c >= "A" and $c <= "Z") or ($c >= "0" and $c <= "9");
    }
    function InternaljPath_Safe ($Path)
    {
        $Result = "";
        $State = 0; //Starting point, Valid
        $l = strlen($Path);
        for ($i = 0; $i < $l; ++ $i)
        {
            $c = $Path[$i];
            if (($c >= "a" and $c <= "z") or ($c >= "A" and $c <= "Z") or ($c >= "0" and $c <= "9") or ($c == "_") or ($c == "-"))
            {
                $State = 1;
                $Result .= $Path[$i];
            }
            elseif ($Path[$i] == "." and $State == 1)
            {
                $State = 2;
                $Result .= ".";
            }
        }
        if ($State == 2)
            $Result = substr($Result, 0, strlen($Result) - 1);
        return $Result;
    }
    function ExternaljPath_Safe ($Path)
    {
        $Result = "";
        $State = 0; //Starting point, Valid
        for ($i = 0; $i < strlen($Path); ++ $i)
        {
            if ($this->isExternaljPathValidCharacter($Path[$i]))
            {
                $State = 1;
                $Result .= $Path[$i];
            }
            elseif ($Path[$i] == "." and $State == 1)
            {
                $State = 2;
                $Result .= ".";
            }
        }
        if ($State == 2)
            $Result = substr($Result, 0, strlen($Result) - 1);
        return $Result;
    }
    function __construct (ApplicationController $App)
    {
        parent::__construct($App);
        $x=new jpCustom2Module ("plugin.htmlawed",".");
        $App->LoadSystemModule($x->__toString());
    }
    function AntiXSS (&$String)
    {
        if (! $this->HTMLawed)
            $this->HTMLawed = new HTMLawedClass();
        $String = $this->HTMLawed->htmLawed($String, array("safe" => 1)#,"deny_attribute"=>"style"
);
        return $String;
    }
    function NoHTML(&$Variable)
    {
    	if (is_array($Variable))
    	{
    		foreach ($Variable as &$v)
    		{
    			$this->NoHTML($v);
    		}
    	}
    	else
    	{
    		$Variable=htmlspecialchars($Variable);	
    	}
    	return $Variable;
    }
}
?>
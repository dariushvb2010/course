<?php


class b
{
    
    public static $CotagLength=7;
    public static $CommentMinLength=6;
    /**
     * 
     * save the last ClassNum used
     * @var integer
     */
    protected static $ClassNum528;
    protected static $ClassNum248;
    protected static $ClassNum109;
    
    /**
     * 
     * 
     * @param integer $Num 528,248,109
     */
    public static function GenerateClassNum($Num)
    {
    	if(!$Num)
    		return;
    	$Num=$Num."";
    	$C="ClassNum{$Num}";
    	if(!isset(self::$$C))
    	{
    		$ClassNum=ORM::Query("ConfigMain")->GetValue("ClassNum{$Num}");
    		if($ClassNum)
    		{
    			self::$$C=$ClassNum;
    		}
    		else
    		{
    			ConfigMain::Add("ClassNum{$Num}",1,false);
    			self::$$C=1;
    		}
    	}
    	self::$$C++;
    	$CM=ORM::Query("ConfigMain")->GetObject("ClassNum{$Num}");
    	$CM->SetValue(self::$$C);
    	ORM::Persist($CM);
    	return (self::$$C);
    }
    public static function CotagValidation($Cotag)
    {
    	$res=true;
    	if(!is_numeric($Cotag))
    		$res=false;
    	$Cotag*=1;
    	if($Cotag<pow(10,self::$CotagLength-1) OR $Cotag>=pow(10,self::$CotagLength))
    		$res=false;
    	return $res;
    }
    static public function __Initialize ()
    {
    	self::$CotagLength=7;
    }

}
?>
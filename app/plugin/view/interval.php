<?php
/**
 * از تاریخ
 * ------------------------------------------
 *   CYear / CMonth / CDay       CHour : CMin
 * ------------------------------------------
 * تا تاریخ
 * ------------------------------------------
 * 	 FYear / FMonth / FDay       FHour : FMin
 * ------------------------------------------
 * @author dariush
 * @example  $ii = new ViewIntervalPlugin();
 * $ii->FormAttr['method']='get';
 * $ii->ElemAttrs['CDay']['name'] = 'DDay';
 * $ii->ElemAttrs['FDay']['value'] = 23;
 * $ii->DefaultDate['FDay'] = 24;
 * $ii->PresentCSS();
 * $ii->PresentHTML();
 */
class ViewIntervalPlugin extends JPlugin
{
	public static  $keys=array('CYear','CMonth', 'CDay', 'CHour', 'CMin',
								 'FYear','FMonth', 'FDay', 'FHour', 'FMin');
	public $FormAttr;
	/**
	 * array for setting form elements attributes, ...
	 * $ElemAttrs[<elem>][<attrName>] = '<attrValue>';
	 * @example $ElemAttrs['CDay']['disable'] = 'disable';
	 * 			 $ElemAttrs['CYear']['class'] = 'year';
	 * 			 $ElemAttrs['Submit']['style'] = 'width:150px;' ;
	 * @var array
	 */
	public $ElemAttrs;
	
	/**
	 * default date displayed in the form
	 * @example $DefaultDate['CYear'], $DefaultDate['CMonth'], ...
	 * @var array
	 */
	public $DefaultDate;
	
	function __construct()
	{
		//---------------------Attributes---------------------------
		$this->FormAttr["method"]="post";
		$this->FormAttr["id"] = "calendar_".FPlugin::RandomString(5);
		foreach (self::$keys as $v)
			$this->ElemAttrs[$v]['name']=$v;
		foreach (self::$keys as $v)
			$this->ElemAttrs[$v]['class']="date";
		$this->ElemAttrs['CYear']['class']=$this->ElemAttrs['FYear']['class']="year";
		$this->ElemAttrs['Submit']['type'] = "submit";
		$this->ElemAttrs['Submit']['name'] = "Date";
		$this->ElemAttrs['Submit']['value'] = "مشاهده";
		//-----------------------------------------------------------
		//----------------------Default Date----------------------
		$cal = new CalendarPlugin();
		$F = explode("/", $cal->JalaliFromTimestamp(time()));
		$C = explode("/", $cal->JalaliFromTimestamp(time()-30*24*3600));
		
		$this->DefaultDate['CYear'] = $C[0];
		$this->DefaultDate['CMonth'] = $C[1];
		$this->DefaultDate['CDay'] = $C[2];
		
		$this->DefaultDate['FYear'] = $F[0];
		$this->DefaultDate['FMonth'] = $F[1];
		$this->DefaultDate['FDay'] = $F[2];
		//---------------------------------------------------------
		//---------------------values-------------------------------
		if( strtolower( $this->FormAttr["method"] ) == "post" )
			$_REQ = $_POST;
		else
			$_REQ = $_GET;
		foreach (self::$keys as $elem)
		{
			$this->ElemAttrs[$elem]['value'] = (isset($_REQ[$elem])) ?  $_REQ[$elem] : $this->DefaultDate[$elem] ;
		}//----------------------------------------------------------
		
	}
	/**
	 * @example $elem like 'CDay', 'CYear', ...
	 * @param string $elem
	 */
	private function EchoElem($elem)
	{// <input name='' vlaue='' class=''/>
		echo "<input ";
		foreach ($this->ElemAttrs[$elem] as $attr=>$val)
		{
			echo $attr."='".$val."' ";
		}
		echo " />";
	}
	function PresentHTML()
	{
		
		
	?>
	
	<form 
		<?php foreach ($this->FormAttr as $k=>$v) echo $k."='".$v."'";?> 
	>
		<div>
			<label>از تاریخ</label>
			<?php $this->EchoElem("CMin");  ?> :
			<?php $this->EchoElem("CHour"); ?> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php $this->EchoElem("CDay");  ?> /
			<?php $this->EchoElem("CMonth");?> /
			<?php $this->EchoElem("CYear"); ?> 
		</div>
		<div>
			<label>تا تاریخ</label>
			<?php $this->EchoElem("FMin");  ?> :
			<?php $this->EchoElem("FHour"); ?> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php $this->EchoElem("FDay");  ?> /
			<?php $this->EchoElem("FMonth");?> /
			<?php $this->EchoElem("FYear"); ?> 
		</div>
	
		<div align="center"><?php $this->EchoElem("Submit"); ?></div>
	</form>
	<?php 
	}
	function PresentCSS()
	{
	?>
		form#<?php echo $this->FormAttr["id"];?> input{text-align:center;}
		form#<?php echo $this->FormAttr["id"];?>{margin:8px; padding:5px; border:1px solid #ddd; width: auto; display:inline-block;}
		form#<?php echo $this->FormAttr["id"];?> input[type='submit'] { width:200px; margin:5px; }
		form#<?php echo $this->FormAttr["id"];?> .date{ width:30px; margin :3px; }
		form#<?php echo $this->FormAttr["id"];?> .year{width:46px;margin :3px;text-align:left;}
		
	<?php 
	}
		
	static function PresentSimple()
	{
		$ii = new ViewIntervalPlugin();
		echo "<style>\n";
		$ii->PresentCSS();
		echo "</style>\n";
		$ii->PresentHTML();
	}
	/**
	 * gets the post or get request and returns an array
	 * @return array $res[<key>] ;
	 * @example $res['CDay'] : 24;
	 * 	         $res['CHour'] : 10;
	 */
	function GetRequest()
	{
		if(strtolower($this->FormAttr['method']) == "post")
			$REQ = $_POST;
		else
			$REQ = $_GET;
		
		foreach (self::$keys as $v)
		{
			$res[$v] = $REQ[$this->ElemAttrs[$v]['name']]*1;
		}
		return $res;
	}
	
}
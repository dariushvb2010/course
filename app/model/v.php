<?php
class v
{
	/**
	 * Persian Cotag
	 * @var string
	 */
	const PCOT = "کوتاژ";
	/**
	 * Persian File
	 * @var string
	 */
	const PFL = "اظهارنامه";
	/**
	 * bold tag
	 */
	public static function b($str)
	{
		return "<b>".$str."</b>";
	}
	
	/**
	 * Green Cotag
	 * @param unknown_type $cot
	 */
	public static function gc($cot)
	{
		return "<span class='v-cotag v-green'>".$cot."</span>";
	}
	/**
	 * Big Green Cotag
	 * @param unknown_type $cot
	 */
	public static function bgc($cot)
	{
		return "<span class='v-cotag v-green v-big'>".$cot."</span>";
	}
	/**
	 * Red Cotag
	 * @param unknown_type $cot
	 */
	public static function rc($cot)
	{
		return "<span class='v-cotag v-red'>".$cot."</span>";
	}
	/**
	 * Big Red Cotag
	 * @param unknown_type $cot
	 */
	public static function brc($cot)
	{
		return "<span class='v-cotag v-red v-big'>".$cot."</span>";
	}
	/**
	 * Blue Cotag
	 * @param unknown_type $cot
	 */
	public static function bc($cot)
	{
		return "<span class='v-cotag v-blue'>".$cot."</span>";
	}
	/**
	 * Big Blue Cotag
	 * @param unknown_type $cot
	 */
	public static function bbc($cot)
	{
		return "<span class='v-cotag v-blue v-big'>".$cot."</span>";
	}

	/**
	 * Custom Cotag Show
	 * @param unknown_type $cot
	 */
	public static function cuc($cot,$attr='')
	{
		$attr=explode(',',$attr);
		$attrClasses=array(
				'Cb'=>'v-blue',
				'Cg'=>'v-green',
				'Sb'=>'v-big',
				);
		
		$link=false;
		$classes=array('v-cotag');
		foreach ($attr as $a){
			if(array_key_exists($a, $attrClasses)){
				$classes[]=$attrClasses[$a];
			}else{
				switch ($a) {
					case 'link':
						$link=true;
					break;
				}
			}
		}
		
		$ClassStr=implode(' ',$classes);

		if($link){
			$cot=self::CotagLink($cot,$cot);
		}
		return "<span class='$ClassStr'>$cot</span>";
	}

	/**
	 * Custom Cotag Show
	 * @param ReviewFile $File
	 */
	public static function Filecuc($File,$attr='')
	{
		$attr=explode(',',$attr);
		$attrClasses=array(
				'Cb'=>'v-blue',
				'Cg'=>'v-green',
				'Sb'=>'v-big',
				);
		
		$link=false;
		$full=false;
		
		$classes=array('v-cotag');
		foreach ($attr as $a){
			if(array_key_exists($a, $attrClasses)){
				$classes[]=$attrClasses[$a];
			}else{
				switch ($a) {
					case 'link':
						$link=true;
					break;
					case 'full':
						$full=true;
					break;
				}
			}
		}
		
		$ClassStr=implode(' ',$classes);

		if($full){
			$cot=$File->Gatecode().'-'.$File->Cotag();
		}else{
			$cot=$File->Cotag();
		}
		
		if($link){
			$cot=self::CotagLink($cot,$File->Gatecode().'-'.$File->Cotag());
		}
		if($File instanceof ReviewFile)
			$stateDesc = FsmGraph::$StateFeatures[$File->State()]['Desc'];
		return "<span title='$stateDesc' class='$ClassStr'>$cot</span>";
	}
	
	////////////////////////////////////////////////////////
	/**
	 * ERROR: cotag not found
	 *  کوتاژ یافت نشد!
	 * @param integer|string|ReviewFile $Cot
	 */
	static function Ecnf($Cot=null)
	{
		if($Cot==null){ 
			return "اظهارنامه با این کوتاژ یافت نشد!";
		}else{
			if($Cot instanceof ReviewFile)
				$myCot=$Cot->Cotag();
			else
				$myCot=strval($Cot);
			
			return "اظهارنامه ".self::rc($myCot)." یافت نشد!";
		}
	}
	/**
	 * ERROR:contag not valid
	 *  کوتاژ ناصحیح است!
	 * @param integer_string $Cot
	 */
	static function Ecnv($Cot=null)
	{
		return ( $Cot==null ?
				"کوتاژ صحیح نیست."
				:
				"کوتاژ ".self::rc($Cot)." ناصحیح است."
		);
	}
	static function Edb(){
		return 'امکان ذخیره کردن در پایگاه داده وجود ندارد!';
	}
	/**
	 * کارشناس یافت نشد
	 * ERROR: reviewer not found
	 */
	static function Ernf(){
		return 'کارشناس یافت نشد!';
	}
	/**
	 * کارشناس غیرفعال است
	 * ERROR: reviewer is disabled(retired or vacation)
	 */
	static function Erd(){
		return 'کارشناس غیرفعال است!';
	}
	/**
	 * 'a' tag
	 * link with href and label
	 * @param integer_string $Cot
	 */
	static function link($text='',$attr)
	{
		$ret='<a ';
		foreach ($attr as $k=>$v){
			$ret.=$k.'="'.$v.'" ';
		}
		$ret.='>'.$text.'</a>';
		return $ret;
	}

	/**
	 * 'a' tag
	 * link with href and label
	 * @param integer_string $Cot
	 */
	static function CotagLink($label='',$Cotag='')
	{
		$t=self::link($label,array('href'=>SiteRoot."/report/progresslist/file?Cotag=".$Cotag));
		return $t;
	}
	
}
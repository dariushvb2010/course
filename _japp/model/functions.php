<?php
/**
 * Outputs the translated phrase in language
 * @param $Phrase
 * @param [optional] format string and its parameters
 * @version 1.05
 * 
 */
function tr($Phrase)
{
	if (func_num_args()==1)
		echo trr($Phrase);
	else
	{
		$args=func_get_args();
		$p=trr($Phrase);
		array_shift($args);
		array_unshift($args,$p);
		call_user_func_array("printf",$args);
	}
}
/**
 * Returns the translated phrase in desired language
 * @param $Phrase
 * @param $Lang
 * @return String
 * @version 1.06
 */
function trr($Phrase,$Lang=null)
{
	if (j::$i18n->Disabled) return $Phrase;
	if ($Lang===null)
	{
		$t=j::Registry("jf/i18n/langs");
		$Lang=$t['current'];
	}
	return j::$i18n->Translate($Phrase,$Lang);
	
}
/**
 * same as tr but strips tags
 * @param ProbablyLargeString $Phrase
 */
function trt($Phrase)
{
	$str=strip_tags($Phrase);
	$args=func_get_args();
	array_shift($args);
	array_unshift($args, $str);
	return call_user_func_array(tr, $args);
}
/**
 * Starts translation buffering
 */
function tr_start()
{
	$content=ob_get_clean();
	reg("jf/i18n/tr_outputbuffer",$content);
	ob_start();
}
/**
 * ends translation buffering and outputs translated data
 * @param Boolean $StripTags to remove tags from translation or not
 */
function tr_end($StripTags=true)
{
	$data=ob_get_clean();
	echo reg("jf/i18n/tr_outputbuffer");
	if ($StripTags)
		trt($data);
	else
		tr($data);
}	

/**
 * Retrives or sets a registry value
 * @param $Path the registry path
 * @param $Value only if setting
 * @param $Readonly optional
 * @return value on retrive, true on success set, false on failure set
 * @version 1.05
 */
function reg($Path=null,$Value=null,$Readonly=false)
{
	if ($Path==null)
	{
		return j::$Registry;
	}
	elseif ($Value!==null) //set
	{
		return j::Register($Path,$Value,$Readonly);
	}
	//get
	else return j::Registry($Path);
}

?>

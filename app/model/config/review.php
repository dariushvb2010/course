<?php
/**
 * 
 * @author dariush
 * a class for saving main properties of Review unit
 */
class ConfigReview
{
	
	static $gate_code = 50100;
	static $customs_code = 165;
	static $review_code = 18;
	static $file_initial_state = 0;
	static $file_initial_class = 0;
	static $upload_folder_root="../../../upload/";
	static function upload_folder_relative_from_japp()
	{
		$jl=new CalendarPlugin();
		$jArr=$jl->TodayJalaliArray();
		
		return self::$upload_folder_root.self::$gate_code.'/'.$jArr[0].'/'.$jArr[1].'/';
	}
}
<?php
class CotagBarController extends JControl
{
	function Start()
	{
		$this->PersianCotag=$this->gNumberCallBack_faIR($_GET['cotag']);
		if(!isset($_GET['chk']))
		{
			$this->BarePresent();	
		}
		else 
		{
			$File=ORM::Query("ReviewFile")->GetRecentFile($_GET['cotag']);
			if($File==null)
				$this->BarePresentString(v::Ecnf());
			else 
				$this->BarePresent();
				
		}
			
		return true;
	}
	static	function gNumberConversion_faIR($matches)
	{
		$number_array['en-GB'] = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".");
		$number_array['fa-IR'] = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "/");

		if (isset($matches[1]))	{
			return str_replace($number_array['en-GB'], $number_array['fa-IR'], $matches[1]);	
		}
		
		return $matches[0];
	}
	function gNumberCallBack_faIR($num)
	{
		return preg_replace_callback('/(?:&#\d{2,4};)|(\d+[\.\d]*)|(?:[a-z](?:[\x00-\x3B\x3D-\x7F]|<\s*[^>]+>)*)|<\s*[^>]+>/i', "CotagBarController::gNumberConversion_faIR", $num);
	}	
	function PersianNumber($Number)
	{
		
		for ($i = 0; $i < strlen($Number); $i++) 
		{
			switch ($Number[$i])
			{
				case '1':
					$Number[$i]= "۱";
				break;
				case '2':
					$Number[$i]="۲";
				break;
				case '3':
					$Number[$i]="۳";
				break;
				case '4':
					$Number[$i]="۴";
				break;
				case '5':
					$Number[$i]="۵";
				break;
				case '6':
					$Number[$i]="۶";
				break;
				case '7':
					$Number[$i]="۷";
				break;
				case '8':
					$Number[$i]="۸";
				break;
				case '9':
					$Number[$i]="۹";
				break;
				case '0':
					$Number[$i]="۰";
				break;

				default:
					;
				break;
			}			
		}
		return $Number;
	}
}
?>

<?php
class ProgressValidator extends JModel
{
	protected $Base="ReviewProgress";


	protected function ProgressArray2String($ProgressArray)
	{
		$out="";
		if (is_array($ProgressArray))
		foreach ($ProgressArray as $p)
		{
			$out.=substr(get_class($p),strlen($this->Base))."-";
		}
		return $out;
	}
	protected function ValidationString2Regexp($ValidationString)
	{
		$ValidationString=str_replace(" ", "", $ValidationString);
		$out="/^";
		$s=$ValidationString;
		do
		{
			$pos=strpos($s, "[");
			if ($pos===false) break;
			$token=substr($s,0,$pos);
			if ($token=="*")
				$token="[^-]*";
//			echo $token.BR;
			$out.="(";
			$out.=$token;
			$s=substr($s,$pos+1);
			$out.="-";
			$out.=")";
			
			$num=$s*1;
			$out.="{".$num;
			$s=substr($s,strlen($num));
			if ($s[0]=='-')
			{
				$out.=",";
				$s=substr($s,1);

				$num=$s*1;
				if ($num>0)
				{
					$out.=$num;
					$s=substr($s,strlen($num));
				}
			}
			$s=substr($s,1);
			$out.="}";
				
				
		} while ($pos!==false);
		$out.=$s."$/";
		return $out;
	}

	function ValidateProgressArray($ProgressArray,$ValidationString)
	{
		$prstr=$this->ProgressArray2String($ProgressArray);
		$regexp=$this->ValidationString2Regexp($ValidationString);
		$r=preg_match_all($regexp, $prstr,$m);
		return $r==1;
	}

	static function Validate($File,$ValidationString)
	{
		$v=new ProgressValidator();
		if (is_numeric($File))
		$File=ORM::Find(new ReviewFile, $File);
		$ProgressArray=j::ODQL("SELECT P FROM ReviewProgress AS P WHERE P.File=?",$File);
		$prstr=$v->ProgressArray2String($ProgressArray);
		$regexp=$v->ValidationString2Regexp($ValidationString);
//		echo $prstr.BR;
//		echo($regexp).BR;
		$r=preg_match_all($regexp, $prstr,$m);
		return $r==1;
	}




}
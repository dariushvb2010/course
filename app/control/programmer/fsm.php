<?php
class ProgrammerFsmController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
		print_r($_POST);
		if (count($_POST))
		{
			$Num=$_POST['Num'];
			$Str=$_POST['Str'];
			$Summary=$_POST['Summary'];
			$Place=$_POST['Place'] ? $_POST['Place'] : 'بیرون';
			
			$Res=ORM::Query("FsmState")->Add($Num, $Str, $Summary, $Place);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else
			{
				
				$this->Result=true;
				$this->Result="وضعیت با شماره   ";
				$this->Result.=" <span style='font-size:20px; color:black; font-weight:bold;'>";
				$this->Result.=$Num."</span> "."با موفقیت ایجاد  گردید.";
			}
		}
		
		$States=ORM::Query("FsmState")->GetAllStates();
		ORM::Dump($States);
		$al=new AutolistPlugin($States);
		$al->SetHeader('Num',"شماره");
		$al->SetHeader('Str',"عنوان انگلیسی");
		$al->SetHeader('Place',"مکان");
		$al->SetHeader('Summary',"توضیحات");
		$al->ObjectAccess=true;
		$al->Width="auto";
		//$al->SetFilter(array($this,'myfilter'));
		$this->List=$al;
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
			
		return $this->Present();
	}
}
?>

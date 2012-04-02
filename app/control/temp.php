<?php
class TempController extends JControl
{
	
	function Start()
	{
		
		$Ps=j::DQL("SELECT P.Requester FROM ReviewProgressSendfile P GROUP BY P.Requester");
		$i=1;
		foreach($Ps as $ps2)
		{
			echo $i.": ".$ps2['Requester']. " <br/>";
			$i++;
		}
		echo count($Ps)." ";
		echo "<br/>----------------------------------------------------<hr/>";
		foreach ($Ps as $p)
		{
			
			$PP=j::ODQL("SELECT P FROM ReviewProgressSendfile P WHERE P.Requester=?",$p['Requester']);
			$Requester=$PP[0]->Requester();
			$n=count($PP);
			echo "---".$n." ".$Requester." : ";
			$ttt=0;
			switch($Requester)
			{
				case "دفتر ارزش گمرک ایران":
					$ttt=12;
					break;
					
				case "دفتر صادرات گمرک ایران":
					$ttt=53;
					break;
					case "دفتر واردات گمرک ایران":
						$ttt=50;	
						break;
					case "بازبینی گمرک ایران":
						$ttt=11;
					break;
						
					case "بازرسي كل كشور":
						$ttt=42;
					break;
					case "بم":
							$ttt=48;
						break;
					case "بندر امام خميني(ره)":
						$ttt=51;
					break;
						
					case "حراست گمرک شهید رجایی":
						$ttt=6;
					break;
					case "احراز هویت گمرک شهید رجایی":
						$ttt=9;
						break;
					case "اردبيل":
						$ttt=52;
					break;
						
					case "ارزیابی عملکرد گمرک ایران":
						$ttt=14;
					break;
					case "استان مركزي":
						$ttt=21;
						break;
					case "اصفهان":
						$ttt=27;
					break;
					
					case "امورمالي گمرک شهید رجایی":
						$ttt=47;
					break;
					case "اهواز":
						$ttt=32;
						break;
					case "آذربايجان غربي":
						$ttt=41;
					break;
					
					case "سرویس ارزیابی گمرک شهید رجایی":
						$ttt=10;
					break;
					case "سمنان":
							$ttt=54;
						break;
					case "سيرجان":
						$ttt=22;
					break;
						
					case "تهران":
						$ttt=24;
					break;
					case "صادرات گمرک شهید رجایی":
						$ttt=7;
					break;
						
					case "گمرک آستارا":
						$ttt=18;
					break;
					case "گمرک کرمان":
						$ttt=16;
						break;
					case "مکاتبات ارزش گمرک شهید رجایی":
						$ttt=3;
					break;
						
					case "مکاتبات سرویس گمرک شهید رجایی":
						$ttt=2;
					break;
					case "قضایی گمرک شهید رجایی":
						$ttt=5; 
					break;
						
					case "قزوين":
						$ttt=49;
					break;
					case "لرستان":
						$ttt=33;
						break;
					case "پته و پروانه گمرک شهید رجایی":
						$ttt=4;
					break;
						
					case "كردستان":
						$ttt=55;
					break;
					case "يزد":
					$ttt=29;
					break;
					default:
						$ttt=51;
			}
			$Topic=ORM::Find("ReviewTopic", $ttt);
			if(!$Topic) echo "hooooooooooo";
			$Archive=ORM::Find1("MyGroup","Title", "Archive");
			if(!$Archive)echo "hooooooooooo";
 			$Mail=ORM::Query("MailSend")->Add(1, "ارسال", $Archive, $Topic, "");
 			$Mail->SetState(9);
 			if(is_string($Mail))echo "hoooooo";
 			$Files=null;
			foreach($PP as $P)
			{
				echo ";".$P->User()->ID().";";
				{
					$P->File()->SetState(11);
					$File=$P->File();
				}
				$New=ORM::Query("ReviewProgressSend")->AddToFile($File,$Mail, true, $P->User());//persist
				if(is_string($New))
					echo $New;
				else 
					$New->SetCreateTimestamp($P->CreateTimestamp());
			}
			
			echo $Topic->Topic()." ";
			echo "<br/>";
			
		}
		echo "done1";
		return $this->Present();
	}
	
}

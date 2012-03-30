<?php
class Temp2Controller extends JControl
{
	
	function Start()
	{
		$Archive=ORM::Find1("MyGroup" , "Title", "Archive");
		//$Ps=j::DQL("SELECT P.CreateTimestamp FROM ReviewProgressSendfile P GROUP BY P.CreateTimestamp");
		$Ps=j::ODQL("SELECT P FROM ReviewProgressReceivefile P ");
		$i=1;
		echo count($Ps)." ";
		echo "<br/>----------------------------------------------------<hr/>";
		foreach ($Ps as $p)
		{
			$LLP=$p->File()->LLP("Send");
			$as=1;
			if($LLP)
			{
				$Topic=$LLP->MailSend()->ReceiverTopic();
				$tid=$Topic->ID();
			}
			else
			{
				$Topic=ORM::Find("ReviewTopic", 2);
				$tid=999999999;
			}
			
			$as=array_search($tid, $Topics);
			echo "as:"; var_dump($as);
			//$as=array_search(1000000, $Topics);
			if($as===null or $as===false)
			{
				echo "null";
				$Topics[]=$tid;
				$Mails[$tid]=ORM::Query("MailReceive")->Add(1,"دریافت",$Archive, $Topic, "");
			}
			
			$p->File()->SetState(12);
			$New=ORM::Query("ReviewProgressReceive")->AddToFile($p->File(),$Mails[$tid],true, $p->User());
			if(is_string($New))
				echo $New."<br>";
			else 
				$New->SetCreateTimestamp($p->CreateTimestamp());
		}
		foreach ($Mails as $M)
			$M->SetState(9);
		return $this->Present();
	}
	
}

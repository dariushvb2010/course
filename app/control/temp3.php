<?php
class Temp3Controller extends JControl
{
	
	function Start()
	{
		$Archive=ORM::Find1("MyGroup" , "Title", "Archive");
		$Raked=ORM::Find1("MyGroup" , "Title", "Raked");
		//$Ps=j::DQL("SELECT P.CreateTimestamp FROM ReviewProgressSendfile P GROUP BY P.CreateTimestamp");
		$MS=j::ODQL("SELECT M FROM ReviewMail M ");
		//ORM::Dump($MS[0]->Post()); die();
		echo count($MS);
		foreach ($MS as $M)
		{
			$Mail=ORM::Query("MailGive")->Add($M->Num(), "تحویل به بایگانی راکد", $Archive, $Raked);
			if(is_string($Mail))
				echo $Mail;
			foreach ($M->Post() as $P)
			{
				$P->File()->SetState(11);
				$G=ORM::Query("ReviewProgressGive")->AddToFile($P->File(), $Mail,true, $P->User());
				if(is_string($G))
					echo $G;
				else
					$G->SetCreateTimestamp($P->CreateTimestamp());
			}
			$Mail->SetState(Mail::STATE_INWAY);
		}
		
		return $this->Present();
	}
	
}

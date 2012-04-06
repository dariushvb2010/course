<?php
class TempController extends JControl
{
	
	function Start()
	{
		
		$Files=j::ODQL("SELECT F FROM ReviewFile F JOIN F.Progress P WHERE F.State=3 AND P.ID=
				(SELECT MAX(P2.ID) FROM ReviewProgress P2 WHERE P.File=F AND P INSTANCE OF ReviewProgressRegisterarchive)
				");
		echo "hi";
		ORM::Dump($Files);
		return $this->Present();
	}
	
}
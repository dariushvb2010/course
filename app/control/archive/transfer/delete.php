<?php
class ArchiveTransferDeleteController extends JControl
{
	function Start(){
		//ORM::Dump($_POST);
		$ID=$_GET['MailID'];
		$Mail=ORM::Find("MailGive", $ID);
		//ORM::Dump($Mail);
		$Mail->Clear();
		//ORM::Flush();
		$this->Redirect(SiteRoot."/archive/transfer/toraked?MailID=".$ID);
	}
}
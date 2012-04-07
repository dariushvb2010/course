<?php
class ViewMailPlugin extends JPlugin
{
	static function SingleShow($M,$Style="",$Action)
	{
		if(!$M)
			return;
		$Title="از ".$M->PersianSource()."  به ".$M->PersianDest();
		$href=self::GetHref($M, $Action);
		$Count=count($M->Box());
		?>
		<div class="mail_container" style="<?php echo $Style; ?>">
			<a href="<?php echo $href; ?>">
				<div class="mail_head">
					<img src="/img/mail/mail-<?php echo $M->State();?>-25.png" title="<?php echo $M->PersianState();?>"/>
					<span class="MailNum"><?php echo $M->Num();?></span><span style="float:left; font-size:small; color:#aff;"><?php echo $Count;?></span>
				</div>
			</a>
			<div class="mail_body noprint" title="<?php echo $Title ?>">
						<span>شناسه: </span><b><?php echo $M->ID();?></b><br/>
						<span>عنوان: </span><b><?php echo $M->Subject();?></b><br/>
					<?php if($M instanceof MailSend )
							echo "<span> ارسال به: </span><b>".$M->ReceiverTopic()->Topic()."</b><br/>";		
						elseif($M instanceof MailReceive)
							echo "<span> دریافت از: </span><b>".$M->SenderTopic()->Topic()."</b><br/>";		
					?>
						<span>توضیحات: </span><b><?php echo $M->Description();?></b>
			</div>
		</div>
	<?php 
	}

	/**
	 * 
	 * @param Mail $M
	 * @param string $Action
	 * @param boolean $HomePage whether the link for home page(TransferPublic) ralated to this mail or not(TransferSingle) 
	 * @return string
	 */
	static public function GetHref($M, $Action)
	{
		$href=self::HomeLink($Action, $M->Source(), $M->Dest());
		$href.="MailID=".$M->ID();
		return $href;
	}
	public static function HomeLink($Action, $Source, $Dest)
	{
		if($Action=="Give")
		{
			if($Source=="Archive" AND $Dest=="Raked")
				$href="/archive/transfer/toraked?";
			elseif($Source=="CotagBook" AND $Dest=="Archive")
				$href="/cotag/transfer/toarchive?";
			elseif($Source=="Raked" AND $Dest=="Archive")
				$href="/raked/transfer/toarchive?";
		}
		elseif($Action=="Get")
		{
			if($Source=="Archive" AND $Dest=="Raked")
				$href="/raked/transfer/fromarchive?";
			elseif($Source=="CotagBook" AND $Dest=="Archive")
				$href="/archive/transfer/fromcotag?";
			elseif($Source=="Raked" AND $Dest=="Archive")
				$href="/archive/transfer/fromraked?";
		}
		elseif($Action=="Send")
		{
			$Taraf=$Dest;
			if($Source=="Archive")
				$href="/archive/transfer/toout?Taraf=".$Taraf."&";
			elseif($Source=="Raked")
				$href="/raked/transfer/toout?Taraf=".$Taraf."&";
		}
		elseif($Action=="Receive")
		{
			$Taraf=$Source;
			if($Dest=="Archive")
				$href="/archive/transfer/fromout?Taraf=".$Taraf."&";
			elseif($Dest=="Raked")
				$href="/raked/transfer/fromout?Taraf=".$Taraf."&";
		}
		return $href;
	}
	static private function SingleShow2($M)
	{
		if(!$M)
		return;
		$Num=$M->Num();
		$ID=$M->ID();
		$Subject=$M->Subject();
		$State=$M->State();
		$Description=$M->Description();
		?>
		<a href="<?php echo $href;?>">
			<div class="mail_container">
				<div class="mail_head" >
					<img src="/img/mail/mail-<?php echo $State;?>-25.png" title="<?php echo $M->PersianState();?>"/>
					<span title="
					شناسه: <?php echo $ID;?>
					عنوان: <?php echo $Subject;?>
					توضیحات: <?php echo $Description;?>
					"><?php echo $Num;?></span>
				</div>
			</div>
		</a>
		<?php 
	}
	static function GroupShow($Mails, $Action)
	{
		if($Mails)
		foreach ($Mails as $M)
		{
			self::SingleShow($M, "",$Action);	
		}
	}
	static function EchoCSS()
	{
		echo "<link rel='stylesheet' href='/style/mail.css' />";	
	}
}
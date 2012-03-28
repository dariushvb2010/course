<?php
class ViewMailPlugin extends JPlugin
{
	static function SingleShow($M,$Style="",$Action)
	{
		if(!$M)
			return;
		$Num=$M->Num();
		$ID=$M->ID();
		$Subject=$M->Subject();
		$State=$M->State();
		$Description=$M->Description();
		$href=self::GetHref($M, $Action);
		?>
		<div class="mail_container" style="<?php echo $Style; ?>">
			<a href="<?php echo $href; ?>"><div class="mail_head">
				<img src="/img/mail/mail-<?php echo $State;?>-25.png" title="<?php echo $M->PersianState();?>"/>
				<span><?php echo $Num;?></span>
			</div></a><div class="mail_body">
						<span>شناسه: </span><b><?php echo $ID;?></b><br/>
					
						<span>عنوان: </span><b><?php echo $Subject;?></b><br/>
					<?php if($M instanceof MailSend )
							echo "<span> ارسال به: </span><b>".$M->ReceiverTopic()->Topic()."</b><br/>";		
						elseif($M instanceof MailReceive)
							echo "<span> دریافت از: </span><b>".$M->SenderTopic()->Topic()."</b><br/>";		
					?>
						<span>توضیحات: </span><b><?php echo $Description;?></b>
			</div>
		</div>
	<?php 
	}

	static public function GetHref($M, $Action)
	{
		if($M instanceof MailGive)
		{
			if($M->GiverGroup()->Title()=="Archive" AND $M->GetterGroup()->Title()=="Raked" AND $Action=="Give")
				$href="/archive/transfer/toraked?MailID=".$M->ID();
			elseif($M->GiverGroup()->Title()=="Archive" AND $M->GetterGroup()->Title()=="Raked" AND $Action=="Get")
				$href="/raked/transfer/fromarchive?MailID=".$M->ID();
			
		}
		elseif($M instanceof MailSend)
		{
			$Dest=$_GET['Dest'];
			$href="/archive/transfer/toout?Dest=".$Dest."&MailID=".$M->ID();
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
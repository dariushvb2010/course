<?php
class ViewMailPlugin extends JPlugin
{
	static function SingleShow($M,$Style="",$Action)
	{
		if(!$M)
			return;
		$Title="از ".$M->PersianSource()."  به ".$M->PersianDest();
		$href=self::GetHref($M, $Action);
		?>
		<div class="mail_container" style="<?php echo $Style; ?>">
			<a href="<?php echo $href; ?>">
				<div class="mail_head">
					<img src="/img/mail/mail-<?php echo $M->State();?>-25.png" title="<?php echo $M->PersianState();?>"/>
					<span><?php echo $M->Num();?></span>
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

	static public function GetHref($M, $Action)
	{
		if($M instanceof MailGive)
		{
			if($Action=="Give")
			{
				if($M->GiverGroup()->Title()=="Archive" AND $M->GetterGroup()->Title()=="Raked")
					$href="/archive/transfer/toraked?MailID=".$M->ID();
				elseif($M->GiverGroup()->Title()=="CotagBook" AND $M->GetterGroup()->Title()=="Archive")
					$href="/cotag/transfer/toarchive?MailID=".$M->ID();
			}
			elseif($Action=="Get")
			{
				if($M->GiverGroup()->Title()=="Archive" AND $M->GetterGroup()->Title()=="Raked")
					$href="/raked/transfer/fromarchive?MailID=".$M->ID();
				elseif($M->GiverGroup()->Title()=="CotagBook" AND $M->GetterGroup()->Title()=="Archive")
					$href="/archive/transfer/fromcotag?MailID=".$M->ID();
			}
			
		}
		elseif($M instanceof MailSend)
		{
			$Dest=$_GET['Taraf'];
			$href="/archive/transfer/toout?Taraf=".$Dest."&MailID=".$M->ID();
		}
		elseif($M instanceof MailReceive)
		{
			$Source=$_GET['Taraf'];
			$href="/archive/transfer/fromout?Taraf=".$Source."&MailID=".$M->ID();
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
<?php
class ViewMailPlugin extends JPlugin
{
	static function SingleShow($M,$Style="")
	{
		if(!$M)
			return;
		$Num=$M->Num();
		$ID=$M->ID();
		$Subject=$M->Subject();
		$State=$M->State();
		$Description=$M->Description();
		$href="/archive/transfer/toraked?MailID=".$ID;
		?>
		<div class="mail_container" style="<?php echo $Style; ?>">
			<a href="<?php echo $href; ?>"><div class="mail_head">
				<img src="/img/mail/mail-<?php echo $State;?>-25.png" title="<?php echo $M->PersianState();?>"/>
				<span><?php echo $Num;?></span>
			</div></a><div class="mail_body">
						<span>شناسه: </span><?php echo $ID;?><br/>
					
						<span>عنوان: </span><?php echo $Subject;?><br/>
					
						<span>توضیحات: </span><?php echo $Description;?>
			</div>
		</div>
	<?php 
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
		$href="/archive/transfer/toraked?MailID=".$ID;
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
	static function GroupShow($Mails)
	{
		if($Mails)
		foreach ($Mails as $M)
		{
			self::SingleShow($M);	
		}
	}
	static function EchoCSS()
	{
		echo "<link rel='stylesheet' href='/style/mail.css' />";	
	}
}
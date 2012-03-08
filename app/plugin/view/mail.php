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
		?>
		<div class="mail_container" style="<?php echo $Style; ?>">
			<div class="mail_head">
				<img src="/img/mail/mail-<?php echo $State;?>-25.png" title="<?php echo $M->PersianState();?>"/><span><?php echo $Num;?></span>
			</div><div class="mail_body">
						<span>شناسه: </span><?php echo $ID;?><br/>
					
						<span>عنوان: </span><?php echo $Subject;?><br/>
					
						<span>توضیحات: </span><?php echo $Description;?>
			</div>
		</div>
	<?php 
	}
	static function GroupShow()
	{
		
	}
	static function EchoCSS()
	{
		echo "<link rel='stylesheet' href='/style/mail.css' />";	
	}
}
<?php
class ViewAlarmPlugin extends JPlugin
{
	/**
	 * 
	 * @param Alarm, ConfigAlarm $A
	 * @param integer $Style
	 * @return string
	 */
	static function SingleShow( $A)
	{
		$IsAlarm=!($A instanceof ConfigAlarm);
		if($IsAlarm)
		if(!$A->MotherUser())
		$Error[]="کاربر ایجاد کننده ندارد";
		if(count($Error))
		var_dump( $Error);
		
		$ID=$A->ID();
		$Killers= $A->Killer();//count($A->Killer()) ? $A->Killer() : ($A->ConfigAlarm() ? $A->ConfigAlarm()->Killer() : null);
		$Title=$A->Title();///$A->Title() ? $A->Title() : ($A->ConfigAlarm() ? $A->ConfigAlarm()->Title() : null);
		$Moratorium=$A->Moratorium();//$A->ConfigAlarm() ? $A->ConfigAlarm()->Moratorium() : $A->Moratorium();
		$Context=$A->Context();//$A->ConfigAlarm() ? $A->ConfigAlarm()->Comment() : $A->Comment();
		$Users=$A->User();
		$Group=$A->Group();
		if($IsAlarm)
		if($A->MotherEvent())
		$MotherEvent=$A->MotherEvent()->PersianName();
			
		?>
		<div class="alarm <?php echo ($HasKiller ? "dead" : "live"); ?>" >
			<?php if(!count($Killers) AND $IsAlarm):?>
			<div class="delete"><a href="?DeleteAlarmID=<?php echo $A->ID();?>">
				<img src="/img/alarm/delete-square-blue-15.png" title="حذف"/></a>
			</div>
			<?php endif;?>
			<?php if($IsAlarm):?>
			<p class="cotag"><span><?php echo $A->File() ? $A->File()->Cotag() : " - ";?></span></p>
			<?php endif;?>
			<div class="title"> <?php echo $Title; ?> </div>
			
			<div class="comment"> <?php echo $Context; ?> <span class="moredetails"> جزئیات... </span></div>
			<div class="more" >
				<div class="partition">
					<p class="label"><span class="plus" >+</span><span>گزارش کوتاژ</span></p>
					<div class="info">
						<p>در دفتر کوتاژ</p>
					</div>
				</div>
				
				<?php if($MotherEvent):?>
				<div class="partition">
					<p class="label"><span class="plus">+</span><span>رویداد ایجاد کننده</span></p>
					<div class="info"><p> <?php echo $MotherEvent;?></p></div>
				</div>
				<?php endif;?>
				<?php if($IsAlarm):?>
				<div class="partition">
					<p class="label"><span class="plus">+</span><span>کاربر ایجادکننده</span></p>
					<div class="info"><p> <?php echo $A->MotherUser() ? $A->MotherUser()->getFullName() : " - ";?></p></div>
				</div>
				<?php endif;?>
				<?php if($IsAlarm):?>
				<div class="partition">
					<p class="label"><span class="plus">+</span><span title="تاریخ ایجاد هشدار">
						تاریخ ایجاد</span></p>
					<div class="info"><p> <?php echo $A->CreateTime();?></p></div>
				</div>
				<?php endif; ?>
				<?php if(count($Killer)):?>
				<div class="partition">
					<p class="label"><span class="plus" >+</span><span>رویدادهای غیرفعال کننده</span></p>
					<div class="info">
					<?php 
						foreach($Killer as $K)
						{
							echo "<p>".$K->PersianName()."</p>";	
						}
					?>
					</div>
				</div>
				<?php endif;?>
				<?php if(count($Users)):?>
				<div class="partition">
					<p class="label"><span class="plus">+</span><span title="کاربرانی که این هشدار برای آنها قابل مشاهده است.">
						کاربران شاهد</span></p>
					<div class="info">
					<?php 
						foreach($Users as $User)
						{
							echo "<p>".$User->GetFullName()."</p>";	
						}
					?>
					</div>
				</div>
				<?php endif;?>
				<?php if(count($Group)):?>
				<div class="partition">
					<p class="label"><span class="plus">+</span><span title="نام واحدی که همه کاربران آن قادر به مشاهده این هشدار هستند.">
						 نام واحد شاهد</span></p>
					<div class="info">
					<?php 
						foreach ($Group as $G)
						{
							echo "<p>".($G->PersianTitle() ? $G->PersianTitle() : $G->Title())."</p>";
						}   
					?>
					</div>
				</div>
				<?php endif;?>
				<div class="partition">
					<p class="label"><span class="plus">+</span><span>مهلت</span></p>
					<div class="info"><p> <?php echo $Moratorium; ?> روز</p></div>
				</div>
			</div><!-- <div class='more'> -->
		</div><!-- <div class='alarm' -->
		<?php 	
	}
	public static function GroupShow($As,$Label="هشدارها", $Style=1)
	{
		echo "<div class='alarm-groupshow'>";
		$n=count($As);
		//ORM::Dump($As);
		echo "<div class='alarmtoggle'>".$Label." ( ".$n.")</div>";
		echo "<table width='100%' class='alarm' ";
		echo "<tr><td>";
		for( $k=0; $k<$n; $k++)
		{
			if($As[$k])
			self::SingleShow($As[$k]);
		}
		/*
		echo "</td><td>";
		for($k=($n/2); $k<$n; $k++)
		{
			//if($As[$k])
			self::SingleShow($As[$k]);
		} */
		echo "</td></tr></table>";
		echo "</div>";
	}
	public static function PresentScritp()
	{ ?>
		$("span.moredetails").click(function(){
			$(this).parents("div.comment").siblings("div.more").toggle("slow");
		});
		$("p.label").click(function(){
			$(this).siblings("div.info").toggle("slow");
			t=$(this).children("span.plus").text();
			if(t=="+")
			$(this).children("span.plus").text("-");
			else
			$(this).children("span.plus").text("+");
		});
		$("div.alarmtoggle").click(function(){
			$(this).siblings("table.alarm").toggle("slow");
		});
	<?php 	
	}
	public static function EchoCSS()
	{
		?> <link href="/style/alarm.css" rel="stylesheet" /><?php 	
	}
}
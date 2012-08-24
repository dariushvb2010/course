
<div id="logout_container" class='ui-state-highlight' style="margin:auto;text-align:Center;">
<?php
if ($this->Username)
{
    ?>
<strong><?php
    echo $this->Username?></strong>
	با موفقیت از سیستم خارج شد.
    <?php
}
else
{
    ?>
	برای خروج از سیستم ابتدا باید وارد سیستم شده باشید.
    <?php
}
?>
<br />

شما در <span id="redirect_timer">5</span> ثانیه به صفحه مربوطه منتقل خواهید شد ...
</div>
<script>
function countDown()
{
	
	x=$("#redirect_timer").text();
	$("#redirect_timer").text(x*1-1);
	if (x==1)
		document.location="<?php echo $this->Return?>";
	else 
		setTimeout(countDown,1000);
}

setTimeout(countDown,1000);

</script>
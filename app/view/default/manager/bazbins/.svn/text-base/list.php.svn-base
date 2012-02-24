<?php
?>

<style>
form.autoform{
	margin:auto;
	width:500px;
	border:double;
	padding:20px
}
<?php $this->DisableForm->PresentCSS();?>
</style>
<h1><img valign="middle" style="vertical-align:middle;" src="/img/h/h1-users-50.png" />
لیست کارشناسان بازبینی</h1>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
<table><tr>
<td>
	<?php if (count($this->ReviewersList)) {?>
		<p align='center'><input type="checkbox" id="SelectAll" />انتخاب همه</p>
		<form method='post' action="<?php echo SiteRoot; ?>/manager/bazbins/list" >
			<?php $this->ReviewersList->Present();?>
			<p>کارشناسان انتخاب شده
				<input type="submit" name ="Work" value="مشغول کار"/>
				<input type="submit" name ="Vacation" value="مرخصی"/>
			</p>
		</form>
	<?php }	?>
</td><td style="text-align:right; padding-right:15px;">
 
	<table>
		<tr>
			<td><img src='/img/light-green-32.png' title="مشغول کار" /></td>
			<td>مشغول کار:
				<?php echo $this->WorkCount;?>
			</td>
		</tr><tr>
			<td><img src='/img/light-orange-32.png' title="مرخصی" /></td>
			<td> مرخصی:
				<?php echo $this->VacationCount ? $this->VacationCount : 0;?>
			</td>
		</tr><tr>
			<td><img src='/img/light-gray-32.png' title="غیرفعال" /></td>
			<td> غیرفعال:
				<?php echo $this->RetiredCount ? $this->RetiredCount : 0;?>
			</td>
		</tr><tr >
			<td></td>
			<td style="border-top:1px solid black;">کل:
				<?php echo count($this->Reviewers);?>
			</td>
		</tr>
	</table>

</td>
</tr></table>

<hr/>
<h3>سایر کاربران </h3>
<?php $this->UserList->Present(); ?>
<hr/>
<h3>غیرفعال کردن کارشناس</h3>
<?php if($this->DisableForm)$this->DisableForm->PresentHTML();?>
<script type="text/javascript">
$("#SelectAll").click(function()
			{
				$(".item").attr("checked",$("#SelectAll").attr("checked"));
			});
</script>
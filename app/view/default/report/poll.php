<style>
	td{padding:5px;}
</style>
<?php $co = j::DQL('select count(U) as co from MyUser U')?>
<div>تعداد ثبت نام کنندگان: <?php echo $co[0]['co'] -1?></div>
<table style="border:1px solid #999; margin:30px;" border="1">
<tr><th>تاریخ کلاس</th><th>امتیاز</th></tr>
<?php
foreach ($this->ChoiceArray as $ch){
	echo '<tr><td>'.$ch['title'].'</td><td align="center">'.$ch['score'].'</td></tr>';
}
?>
</table>
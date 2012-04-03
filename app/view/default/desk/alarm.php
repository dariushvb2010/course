<style>

</style>

<?php ViewAlarmPlugin::EchoCSS();?>
<h1>
مشاهده پیام ها</h1>

<?php 
ViewAlarmPlugin::GroupShow($this->Alarm_Personal,"پیام های فردی");
ViewAlarmPlugin::GroupShow($this->Alarm_Group,"پیام های مربوط به واحد".$this->GroupTitle);
?>

<script>
<?php ViewAlarmPlugin::PresentScritp();?>
</script>
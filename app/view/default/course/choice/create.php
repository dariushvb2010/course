<?php
$this->Form->PresentHTML();

?>
<table>
<?php
foreach ($this->ChoiceArray as $ch){
	echo '<tr><td>'.$ch['title'].'</td><td>'.$ch['score'].'</td></tr>';
}
?>
</table>
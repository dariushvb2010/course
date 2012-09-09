<?php
//var_dump($this->Asy->getPersianTitles());
$AutoList = new AutolistPlugin($this->Asy->getAll(), null, "Select");
$AutoList->SetHeader('Value', 'مشخصات کالا');
$AutoList->HasLeftData = true;
$AutoList->LeftData = $this->Asy->getPersianTitles();
$AutoList->LeftDataLabel = "اطلاعات";
$AutoList->InputValues['ColsCount'] = count(1);
$AutoList->InputValues['RowsCount'] = count($this->Asy->getPersianTitles());
/*$AutoList->SetFilter(array($this, function($k, $v, $D) {

        if (!$v || $v == ' ')
            return '-';
        else {
            return $v;
        }
    }));*/
    
    $AutoList->PresentForPrint();
?>
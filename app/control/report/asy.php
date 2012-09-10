<?php

class ReportAsyController extends JControl {

    function Start() {
        //j::Enforce("");

        if (count($_POST)) {
            $Cotag=$_POST['Cotag'];
            $File=b::GetFile(B::CotagFilter($Cotag));
            $Asy=ConnectionAsy::GetAsyByFile($File);
            $this->Asy=$Asy;
        }

        $this->Error = $Error;
        if (count($Error))
            $this->Result = false;
        return $this->Present();
    }

    function myfilter($k, $v, $D) {

        if (!$v || $v == ' ')
            return '-';
        else {
            return $v;
        }
    }

}
<?php
class ReportCotaginfoController extends JControl
{
	function Start()
	{
		try {
			$c=new SoapClient("http://192.168.0.20/customs/vehicle_permit.asmx?WSDL");
		} catch (Exception $e) {
			$Error[]=  "ارتباط با سرور قطع است.";
			$this->Error=$Error;
			if (count($Error)) $this->Result=false;
			unset($_POST);
			return $this->Present();
		}
		if (count($_POST))
		{

			$Cotag=b::CotagFilter($_POST['Cotag']);
			//			$r=j::CallService("https://10.32.0.19/server/service/review/info", "ReviewInfo",array("Cotag"=>$Cotag));
			//			$r=j::CallService("http://192.168.0.20/customs/vehicle_permit.asmx", "SearchBy_Kootaj",array("kootaj"=>($Cotag."")));
			try {
				$c=new SoapClient("http://192.168.0.20/customs/vehicle_permit.asmx?WSDL");
				$extx=new SoapClient("http://192.168.0.20/customs/extrataxes.asmx?WSDL");
				$r=$c->SearchBy_Kootaj(array("kootaj"=>($Cotag)));
				$r2=$extx->PreExtraTaxes_ByKootaj(array("kootaj"=>($Cotag)));
				$r3=$extx->ExtraTaxes_ByKootaj(array("kootaj"=>($Cotag)));
				
				
			} catch (Exception $e) {
				$Error[]=  "ارتباط با سرور قطع است.";
				$this->Error=$Error;
				if (count($Error)) $this->Result=false;
				unset($_POST);
				return $this->Present();
			}
			$cal=new CalendarPlugin();
			
			$permitObj=is_array($r->SearchBy_KootajResult->VP_obj->Vehicle_PermitObj)?$r->SearchBy_KootajResult->VP_obj->Vehicle_PermitObj[0]->permitObj:$r->SearchBy_KootajResult->VP_obj->Vehicle_PermitObj->permitObj;
			$permitResult=$r->SearchBy_KootajResult->resObj->result;
			if(!$permitResult)
			{
				//$Error[]=$r;
				$Error[]=  $r->SearchBy_KootajResult->resObj->Msg->string;
			}
			else if($r)
			{

				$arr=array(
				array(
				"Key"=>v::PCOT,
					"Value"=>$permitObj->Kootaj),
				array(
									"Key"=>"شماره پروانه",
									"Value"=>$permitObj->Number),
				array(
									"Key"=>"نام اظهارکننده",
									"Value"=>$permitObj->ezharkonnande->FName." ".$permitObj->ezharkonnande->LName),
				array(
									"Key"=>"کد ملی اظهارکننده",
									"Value"=>$permitObj->ezharkonnande->NationalNumber),
				array(
									"Key"=>"نام صاحب کالا",
									"Value"=>$permitObj->malek->FName." ".$permitObj->malek->LName),
				array(
									"Key"=>"کد ملی اظهارکننده",
									"Value"=>$permitObj->malek->NationalNumber),
				array(
									"Key"=>"شرکت",
									"Value"=>$permitObj->sherkat->CompanyName),
				array(
									"Key"=>"شماره ثبت",
									"Value"=>$permitObj->sherkat->RegisterNumber),
				array(
									"Key"=>"تاریخ کوتاژ",
									"Value"=>$cal->JalaliFromTimestamp(strtotime($permitObj->KootajDate))),	
				array(
									"Key"=>"وزن اظهارشده",
									"Value"=>$permitObj->ExpressWeight),
				array(
									"Key"=>"تعداد کالای اظهار شده",
									"Value"=>$permitObj->ExpressNumber),	
					
				array(
									"Key"=>"ارزش اظهارشده ارزی",
									"Value"=>$permitObj->TotalPrice),
				array(
									"Key"=>"تعداد وسیله نقلیه",
									"Value"=>$permitObj->TrunkNumber),
				array(
									"Key"=>"توزین کل کالا",
									"Value"=>$permitObj->TotalWeight),

				array(
									"Key"=>"نوع ارز",
									"Value"=>$permitObj->PriceType->PriceType),
				array(
									"Key"=>"عوارض گمرکی",
									"Value"=>$permitObj->Duty),
				array(
									"Key"=>"ارزش اظهاری (ریال)",
									"Value"=>$permitObj->PersianPrice),
				//				array(
				//									"Key"=>"نام کالا",
				//									"Value"=>$permitObj->commodity_Permit->Commodity_PermitObj->Commodity->Name),
				//				array(
				//									"Key"=>"کد کالا",
				//									"Value"=>$permitObj->commodity_Permit->Commodity_PermitObj->Commodity->HSCode),
				//				array(
				//									"Key"=>"توضیحات کالا",
				//									"Value"=>$permitObj->commodity_Permit->Commodity_PermitObj->Commodity->Description),
				//
				//
				//				array(
				//									"Key"=>"واحد کالا",
				//									"Value"=>$permitObj->commodity_Permit->Commodity_PermitObj->Commodity->CommodityUnit->CommodityUnit),
					

				);
				$al=new AutolistPlugin($arr,null,"Select");
				$al->SetHeader('Key', 'اطلاعات');
				$al->SetHeader('Value', 'نتیجه');
				$al->SetFilter(array($this,"myfilter"));
				$this->AutoList=$al;
				//****************************************************************************************************************************************
				
				$Commodity=$permitObj->commodity_Permit->Commodity_PermitObj;
				$arrL = array("نام کالا","کد کالا","واحد کالا","وزن","تعداد","قیمت","توضیحات");
				if(is_array($Commodity))
				$ComArr=$Commodity;
				else
				$ComArr[]=$Commodity;
				foreach ($ComArr as $O) {

					$arr2[]=array(
									"Value"=>$O->Commodity->Name);
					$arr2[]=array(
									"Value"=>$O->Commodity->HSCode);
					$arr2[]=array(
									"Value"=>$O->Commodity->CommodityUnit->CommodityUnit);
					$arr2[]=array(
									"Value"=>$O->CommodityWeight);
					$arr2[]=array(
									"Value"=>$O->CommodityNumber);
					$arr2[]=array(
									"Value"=>$O->Price);
					$arr2[]=array(
									"Value"=>$O->Commodity->Description);


				}

				$alCom=new AutolistPlugin($arr2,null,"Select");
				$alCom->SetHeader('Value', 'مشخصات کالا');
				$alCom->HasLeftData=true;
				$alCom->LeftData=$arrL;
				$alCom->LeftDataLabel="اطلاعات";
				$alCom->InputValues['ColsCount']=count($ComArr)	;
				$alCom->InputValues['RowsCount']=7;
				$alCom->SetFilter(array($this,"myfilter"));
				$this->AutoListCom=$alCom;



			}
			else
			{
				$Error[]="برای کوتاژ داده شده اطلاعاتی موجود نیست.";
			}
				

			//	***************************************************************************************************************************************
			
			$exterResult=$r2->PreExtraTaxes_ByKootajResult->resObj->result;
			$preExtraObj=$r2->PreExtraTaxes_ByKootajResult->preExtra_obj->PreExtraTaxesObjects;
			if(!$exterResult)
			{
				//$Error[]=$r;
				$Error[]=  $r2->PreExtraTaxes_ByKootajResult->resObj->Msg->string;
			}
			else if($r2)
			{
				$expertObj=$O->expertObj;
				$ShowZeros=true;
					
				$arrL = array("نام کارشناس","دیماند","جریمه","تفاوت تعرفه-مالیات","تفاوت تعرفه-عوارض ","تفاوت تعرفه-جریمه","تفاوت تعرفه-نیم درصد","تفاوت تعرفه-سایر","تفاوت وزن-مالیات","تفاوت وزن-عوارض ","تفاوت وزن-جریمه",
									"تفاوت وزن-نیم درصد","تفاوت وزن-سایر","تفاوت ارزش-مالیات","تفاوت ارزش-عوارض ","تفاوت ارزش-جریمه","تفاوت ارزش-نیم درصد","تفاوت ارزش-سایر","مجموع تفاوت ها","تاریخ کارشناسی","توضیحات");
					
					
				if(is_array($preExtraObj))
				$preExtraArr=$preExtraObj;
				else
				$preExtraArr[]=$preExtraObj;
					
				foreach ($preExtraArr as $O)
				{
					$expertObj=$O->expertObj;

					$arr1[]= array(
									"Value"=>$expertObj->FName." ".$expertObj->LName);
					$arr1[]= array(
									"Value"=>$O->Dimand);
					$arr1[]= array(
									"Value"=>$O->Penalty);
					$ShowZeros=true;

					if($ShowZeros || $O->DiffDuty_Tax)
					$arr1[]= array(
									"Value"=>$O->DiffDuty_Tax);
					if($ShowZeros ||$O->DiffDuty_Toll)
					$arr1[]= array(
									"Value"=>$O->DiffDuty_Toll);
					if($ShowZeros ||$O->DiffDuty_Penalty)
					$arr1[]= array(
									"Value"=>$O->DiffDuty_Penalty);
					if($ShowZeros ||$O->DiffDuty_HalfPercent)
					$arr1[]= array(
									"Value"=>$O->DiffDuty_HalfPercent);
					if($ShowZeros ||$O->DiffDuty_Other)
					$arr1[]= array(
									"Value"=>$O->DiffDuty_Other);
					//					/-------
					if($ShowZeros ||$O->DiffWeight_Tax)
					$arr1[]= array(
									"Value"=>$O->DiffWeight_Tax);
					if($ShowZeros ||$O->DiffWeight_Toll)
					$arr1[]= array(
									"Value"=>$O->DiffWeight_Toll);
					if($ShowZeros ||$O->DiffWeight_Penalty)
					$arr1[]= array(
									"Value"=>$O->DiffWeight_Penalty);
					if($ShowZeros ||$O->DiffWeight_HalfPercent)
					$arr1[]= array(
									"Value"=>$O->DiffWeight_HalfPercent);
					if($ShowZeros ||$O->DiffWeight_Other)
					$arr1[]= array(
									"Value"=>$O->DiffWeight_Other);
					//		-------------------------------------
					if($ShowZeros ||$O->DiffCost_Tax)
					$arr1[]= array(
									"Value"=>$O->DiffCost_Tax);
					if($ShowZeros ||$O->DiffCost_Toll)
					$arr1[]= array(
									"Value"=>$O->DiffCost_Toll);
					if($ShowZeros ||$O->DiffCost_Penalty)
					$arr1[]= array(
									"Value"=>$O->DiffCost_Penalty);
					if($ShowZeros ||$O->DiffCost_HalfPercent)
					$arr1[]= array(
									"Value"=>$O->DiffCost_HalfPercent);
					if($ShowZeros ||$O->DiffCost_Other)
					$arr1[]= array(
									"Value"=>$O->DiffCost_Other);
					//			-------------------------------------
					$arr1[]= array(
									"Value"=>$O->SumAll);
					$arr1[]= array(
									"Value"=>$cal->JalaliFromTimestamp(strtotime($O->RecordDate))." " .date("H:i",strtotime($O->RecordDate)));
					$arr1[]= array(
									"Value"=>$O->Description);






				}
				$al1=new AutolistPlugin($arr1,null,"Select");
				//				$al1->SetHeader('Key', 'اطلاعات');
				$al1->SetHeader('Value', 'مشخصات کارشناسی');
				$al1->HasLeftData=true;
				$al1->LeftData=$arrL;
				$al1->LeftDataLabel="اطلاعات";
				$al1->InputValues['ColsCount']=count($preExtraObj)	;
				$al1->InputValues['RowsCount']=21;
				$al1->SetFilter(array($this,"myfilter"));
				$this->AutoList1=$al1;
			}
			else
			{
				$Error[]="برای کوتاژ داده شده اطلاعاتی موجود نیست.";
			}

			//	*******************************************************************************************************************

			
			$exterResult=$r3->ExtraTaxes_ByKootajResult->resObj->result;
			$ExtraObj=$r3->ExtraTaxes_ByKootajResult->extra_obj->ExtraTaxesObjects;

			if(!$exterResult)
			{
				//$Error[]=$r;
				$Error[]=  $r3->ExtraTaxes_ByKootajResult->resObj->Msg->string;
			}
			else if($r3)
			{
					
				$arrL=array("شماره قبض درآمد","مبلغ ","زمان پرداخت");
				if(is_array($ExtraObj))
				$ExtraArr=$ExtraObj;
				else
				$ExtraArr[]=$ExtraObj;
				foreach ($ExtraArr as $O)
				{

					$arr3[]= array(
									"Value"=>$O->FishNo);
					$arr3[]= array(
									"Value"=>$O->posPaymentObj->Amount);
					$arr3[]= array(
									"Value"=>$cal->JalaliFromTimestamp(strtotime($O->RecordDate))." " .date("H:i",strtotime($O->RecordDate)));

				}
				$al2=new AutolistPlugin($arr3,null,"Select");
				$al2->SetHeader('Value', 'مشخصات قبض درآمد');
				$al2->HasLeftData=true;
				$al2->LeftData=$arrL;
				$al2->LeftDataLabel="اطلاعات";
				$al2->InputValues['ColsCount']=count($ExtraObj)	;
				$al2->InputValues['RowsCount']=3;
				$al2->SetFilter(array($this,"myfilter"));
				$this->AutoList2=$al2;
			}
			else
			{
				$Error[]="برای کوتاژ داده شده اطلاعاتی موجود نیست.";
			}
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();

	}
	function myfilter($k,$v,$D)
	{

		if(!$v || $v==' ')
		return '-';
		else
		{
			return $v;
		}
	}

}
<?php
class ReportTypisthelpController extends JControl
{
	function Start()
	{
		j::Enforce("TypistHelp");

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
				"Key"=>"کوتاژ",
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
									"Key"=>"قیمت اظهارشده",
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
									"Key"=>"قیمت (ریال)",
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
				$al->SetHeader('Value', 'جوابش');
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
					//				var_dump($O);
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
<?php
class BarcodeModel extends JModel
{
	public $code=array(
	"Start"=>110,
	0=>100100100100,
	1=>100100100110,
	2=>100100110100,
	3=>100100110110,
	4=>100110100100,
	5=>100110100110,
	6=>100110110100,
	7=>100110110110,
	8=>110100100100,
	9=>110100100110,
	"Stop"=>1001,
	
	);
	function add_after($code) {
        if(!$code) return;
        $sum = $this->calculate($code."0");
        if(($sum%10) == 0) { return 0; } else { return (10-($sum%10)); }
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    // add_before = Digit to add before. Return the digit
    function add_before($code) {
        if(!$code) return;
        for($n=0; $n<=9; $n++) {
            $sum = $this->calculate($n.$code);
            if(($sum%10) == 0) return $n;
        }
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    // Compute the checksum according to the Luhn's algorithm
    function calculate($str) {
        $this->numstr = $str;
        $this->wrkstr = str_replace($this->chr2rm, '', $str); // remove separators
        $nbdigits = strlen($this->wrkstr);
        $this->wrklen = $nbdigits;
        $parity = $nbdigits & 1;
        $sum = 0;

        for($n=$nbdigits-1; $n>=0; $n--) {
            $digit = substr($this->wrkstr, $n, 1)*1;
            if(($n & 1) == $parity) $digit *= 2;
            if($digit > 9) $digit -= 9;
            $sum += $digit;
        }
        return $sum;
    }

	
	function Number2Barcode($Number,$height=50,$barWidth=2,$annotation=true,$fontSize=10)
	{
		$Number.="";
		$len=strlen($this->code['Start']);
		$data=$this->code['Start'];
		for ($i=0;$i<strlen($Number);++$i)
		{
			$len+=strlen($this->code[$Number[$i]]);

			
			$data.=$this->code[$Number[$i]];
			
		}
		$checksum=$this->add_after($Number);
		$len+=strlen($this->code[$checksum]);
		$data.=$this->code[$checksum];
		$len+=strlen($this->code['Stop']);
		$data.=$this->code['Stop'];	
		$c=new CalendarPlugin();
		
//		$height=50;
//		$barWidth=2;
		$activeHeight=$height;
		if ($annotation)
			$height+=$fontSize+4;
		$width=$len*$barWidth;
		$img=imagecreate($width+60, $height);
		$black=imagecolorallocate($img,0,0,0);
		$white=imagecolorallocate($img,255,255,255);
		imagefilledrectangle($img,0,0,$width+60,$height+20,$white);
//		imagerectangle($img,0,0,$width-1,$height-1,$black);
		for ($i=0;$i<strlen($data);++$i)
			imagefilledrectangle($img,$i*$barWidth,0,($i+1)*$barWidth-1, $activeHeight, $data[$i]?$black:$white);
		//($width-strlen($Number)*$fontSize)/2
		$Numbers=explode('-',$Number);
		if(count($Numbers)>1){
			imagettftext($img, $fontSize, 90, $width+35, $height, $black, dirname(__FILE__)."/../../files/font/XBRoya.ttf", $Numbers[1]);
		}
		imagettftext($img, $fontSize, 90, $width+20, $height, $black, dirname(__FILE__)."/../../files/font/XBRoya.ttf", $Numbers[0]);
		imagettftext($img, $fontSize-2, 90, $width+50, $height, $black, dirname(__FILE__)."/../../files/font/XBRoya.ttf", $c->JalaliFromTimestamp(Time()));
//		imagettftext($image, $size, $angle, $x, $y, $color, $fontfile, $text)
		imagejpeg($img);
	}
	
}
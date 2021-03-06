<?php
/**
 * 
 * 
 * @Entity @Table(name="app_templates")
 * @entity(repositoryClass="TemplateRepository")
 * @author Morteza Kavakebi
 * */
class Template
{
    /**
     * @GeneratedValue @Id @Column(type="integer")
     * @var string
     */
    private $ID;
	public function ID()
	{
		return $this->ID;
	}
    /**
     * @Column(type="string",unique="true")
     * @var string
     */
    private $Title;
	public function Title()
	{
		return $this->Title;
	}
	public function SetTitle($value)
	{
		$this->Title=$value;
	}
	
    /**
     * @Column(type="text")
     * @var string
     */
    private $Html;
    
    public function Html()
    {
    	return $this->Html;
    }
    public function SetHtml($value)
    {
    	$this->Html=$value;
    }
    
    /**
     * 
     * @var Array of string=>string
     * for UnknownParameters
     */
    private $UParray;
    public function SetUParray($value){
    	$this->UParray=$value;
    }
    public function UParray()
    {
    	return $this->UParray;
    }
    
    
    function __construct($Title=null,$Html=null)
    {
    	$this->SetTitle($Title);
    	$this->SetHtml($Html);
    }
    
    function GetUnknownFields(){
    	$preg = '#\[((?>[^\[\]]+)|(?R))*\]#x';
    	preg_match_all($preg, $this->Html(), $matches, PREG_PATTERN_ORDER);
    	if (!is_array($matches[1])) return null;
    	return array_diff($matches[1], static::GetFields());
    }
    
    function GetFinalDocument(){
    	$array=$this->UParray();
    	$Document=$this->Html();
    	foreach ($this->GetUnknownFields() as $v){
    		if(!isset($array['UF_'.$v])){
    			return false;
    		}else{
    			$Document=str_replace('['.$v.']', $array['UF_'.$v], $Document);
    		}
    	}
    	//TODO: replace Valid PArameters
    	return $Document;
    }

    function GetFinalDocumentPDF(){
    	$Document=$this->GetFinalDocument();
    	
		$pdf = new TcpdfTcpdfPlugin(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    	    	
    	//set margins
    	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    	//set auto page breaks
    	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    	// set some language dependent data:
    	$lg = Array();
    	$lg['a_meta_charset'] = 'UTF-8';
    	$lg['a_meta_dir'] = 'rtl';
    	$lg['a_meta_language'] = 'fa';
    	$lg['w_page'] = 'page';
    
    	//set some language-dependent strings
    	$pdf->setLanguageArray($lg);
    	 
    	// ---------------------------------------------------------
    	 
    	// set font
    	$pdf->SetFont('dejavusans', '', 12);
    	 
    	// add a page
    	$pdf->AddPage();
    	 
    	// Persian and English content
    	$pdf->WriteHTML($this->GetFinalDocument(), true, 0, true, 0);
    	
    	//Close and output PDF document
    	$pdf->Output('example_018.pdf', 'I');
    }
    
    function pdftest(){
    	$pdf = new TcpdfTcpdfPlugin(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    	
    	// set document information
    	$pdf->SetCreator(PDF_CREATOR);
    	$pdf->SetAuthor('Nicola Asuni');
    	$pdf->SetTitle('TCPDF Example 018');
    	$pdf->SetSubject('TCPDF Tutorial');
    	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    	
    	// set default header data
    	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 018', PDF_HEADER_STRING);
    	
    	// set header and footer fonts
    	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    	
    	// set default monospaced font
    	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    	
    	//set margins
    	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    	
    	//set auto page breaks
    	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    	
    	//set image scale factor
    	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    	
    	// set some language dependent data:
    	$lg = Array();
    	$lg['a_meta_charset'] = 'UTF-8';
    	$lg['a_meta_dir'] = 'rtl';
    	$lg['a_meta_language'] = 'fa';
    	$lg['w_page'] = 'page';
    	
    	//set some language-dependent strings
    	$pdf->setLanguageArray($lg);
    	
    	// ---------------------------------------------------------
    	
    	// set font
    	$pdf->SetFont('dejavusans', '', 12);
    	
    	// add a page
    	$pdf->AddPage();
    	
    	// Persian and English content
    	$htmlpersian = '<span color="#660000">Persian example:</span><br />سلام بالاخره مشکل PDF فارسی به طور کامل حل شد. اینم یک نمونش.<br />مشکل حرف \"ژ\" در بعضی کلمات مانند کلمه ویژه نیز بر طرف شد.<br />نگارش حروف لام و الف پشت سر هم نیز تصحیح شد.<br />با تشکر از  "Asuni Nicola" و محمد علی گل کار برای پشتیبانی زبان فارسی.';
    	$pdf->WriteHTML($htmlpersian, true, 0, true, 0);
    	
    	// set LTR direction for english translation
    	$pdf->setRTL(false);
    	
    	$pdf->SetFontSize(10);
    	
    	// print newline
    	$pdf->Ln();
    	
    	// Persian and English content
    	$htmlpersiantranslation = '<span color="#0000ff">Hi, At last Problem of Persian PDF Solved completely. This is a example for it.<br />Problem of "jeh" letter in some word like "ویژه" (=special) fix too.<br />The joining of laa and alf letter fix now.<br />Special thanks to "Nicola Asuni" and "Mohamad Ali Golkar" for Persian support.</span>';
    	$pdf->WriteHTML($htmlpersiantranslation, true, 0, true, 0);
    	
    	// Restore RTL direction
    	$pdf->setRTL(true);
    	
    	// set font
    	$pdf->SetFont('aefurat', '', 18);
    	
    	// print newline
    	$pdf->Ln();
    	
    	// Arabic and English content
    	$pdf->Cell(0, 12, 'بِسْمِ اللهِ الرَّحْمنِ الرَّحِيمِ',0,1,'C');
    	$htmlcontent = 'تمَّ بِحمد الله حلّ مشكلة الكتابة باللغة العربية في ملفات الـ<span color="#FF0000">PDF</span> مع دعم الكتابة <span color="#0000FF">من اليمين إلى اليسار</span> و<span color="#009900">الحركَات</span> .<br />تم الحل بواسطة <span color="#993399">صالح المطرفي و Asuni Nicola</span>  . ';
    	$pdf->WriteHTML($htmlcontent, true, 0, true, 0);
    	
    	// set LTR direction for english translation
    	$pdf->setRTL(false);
    	
    	// print newline
    	$pdf->Ln();
    	
    	$pdf->SetFont('aealarabiya', '', 18);
    	
    	// Arabic and English content
    	$htmlcontent2 = '<span color="#0000ff">This is Arabic "العربية" Example With TCPDF.</span>';
    	$pdf->WriteHTML($htmlcontent2, true, 0, true, 0);
    	
    	// ---------------------------------------------------------
    	
    	//Close and output PDF document
    	$pdf->Output('example_018.pdf', 'I');
    	 
    }
    
    Static function GetFields(){
    	return array(
    		'نام صاحب کالا'=>'OwnerName',
    		'مسثول بازبینی'=>'BazbiniBoss',
    		'تاریخ'=>'Date',
    		'شماره کوتاژ'=>'Cotag',
    		'آدرس صاحب کالا'=>'OwnerAddress',
    		'نام گمرگ'=>'CustomName',
    		'شماره گمرک'=>'CustomID',
    		'کارشناس مربوطه'=>'Reviewer',
    		'مبلغ کسر دریافتی'=>'KasrDaryafti',
    		'شماره کلاسه'=>'Classe',
    		'علت تفاوت'=>'ElatDiff',
    		'نوع کالا'=>'Kala',
    		'کارشناس سالن'=>'SaloonKarshenas',
    		'ارزیاب'=>'arzyab',
    		'رنگ مسیر'=>'Masir',
    	);
    }
    
    Static function TemplateByFile($File){
    	return ORM::Find("Template", 1);
    }
    
}


use \Doctrine\ORM\EntityRepository;
class TemplateRepository extends EntityRepository
{
	public function getAll()
	{
		return j::ODQL('SELECT u FROM Template u');
	}
}
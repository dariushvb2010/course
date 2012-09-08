<?php
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * 109 -> state(36)
 * 248, 528 -> state(18)
 * @Entity
 * @entity(repositoryClass="ReviewProcessRegisterRepository")
 * */
class ReviewProcessRegister extends ReviewProgress
{
	/// category will be stored in the 'SubManner' field of this class
	///--------------------///
	///protected SubManner = '109', '248', '528'
	///-------------------///
	/**
	 * Category 109
	 * @var string
	 */
	const Cat_109 = '109';
	/**
	 * Category 248
	 * @var string
	 */
	const Cat_248 = '248';
	/**
	 * Category 528
	 * @var string
	 */
	const Cat_528 = '528';
	
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected  $Classe;
	function Classe()
	{
		return $this->Classe;
	}
	function setClasse($value)
	{
		$this->Classe=$value;
	}
	/**
	 * 
	 * @param ReviewFile $File
	 * @param string $Cat like '109', '248', '528'
	 * @param string $Classe
	 * @param MyUser $User
	 */
	function __construct(ReviewFile $File=null, $Cat, MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->SetSubManner($Cat);
	}
	/**
	 *
	 *
	 * @param integer $Num 528,248,109
	 */
	public static function GenerateClasse($Num)
	{
		if(!$Num)
			return;
			
		$Num=$Num."";
		$C="ClassNum{$Num}";
		$CM=ORM::Query("ConfigMain")->GetObject($C);
		if(!$CM)
		{
			ConfigMain::Add($C,1,false);
			return 1;
		}
		else
		{
			$CM->SetValue($CM->Value()*1+1);
			ORM::Persist($CM);
			return $CM->Value();
		}
	}
	function  Summary()
	{
		$str="پرونده به شماره کوتاژ ".$this->File()->Cotag()." در مکاتبات با شماره کلاسه ".$this->File()->GetClass().'ثبت گردید .';
		return $str;
	}
	function Title()
	{
		return "ثبت کلاسه";
	}
	function Manner()
	{
		return 'ProcessRegister_'.$this->SubManner();
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessRegisterRepository extends EntityRepository
{
	/**
	 * 
	 * @param ReviewFile $File
	 * @param String $cat like '109','248', '528'
	 * @param String $classe like 23423
	 * @return string on error object on sucsess
	 */
	public function AddToFile($File, $cat, $classe=null)
	{
		if(!($File instanceof ReviewFile))
			return v::Ecnf();
		$vr = b::CatValidation($cat); 
		if(!$vr){
			return p::Cat.' یا شماره کلاسه اشتباه است.';
		}
		$lastReview = $File->LLP('Review');
		if($lastReview)
			$reviewCat = $lastReview->Provision(); // the category of last review like 109, 248 , 528
		if(!empty($reviewCat))
			if(trim($reviewCat)!=$cat) // review Category and input Category do not match
				return p::Cat.' اشتباه وارد شده است.';
		
		$R=new ReviewProcessRegister($File, $cat);
		$err=$R->Check();
		if(is_string($err))
			return $err;
		//------------setclasse------
		if(empty($classe))
			$classe = ReviewProcessRegister::GenerateClasse($cat);
		$R->setClasse($classe);
		//===================
		$R->Apply();
		ORM::Persist($R);
		return $R;
	}
}
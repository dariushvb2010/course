<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessClearanceRepository")
 * */
class ReviewProcessClearance extends ReviewProgress
{
	/**
	 * کد اظهارکننده
	 * @Column(type="string", length="30")
	 * @var string
	 */
	protected $some;
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->some="dd";
	}
	
	function  Summary()
	{
		return 'پرونده اظهارنامه مختومه شد و به بایگانی تحویل داده شد.';
	}
	function Title()
	{
		return 'مختومه شد';
	}
	function Event()
	{
		return 'ProcessClearance';
	}

}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessClearanceRepository extends EntityRepository
{
	/**
	 *
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null,$Comment=null)
	{
		$CurrentUser=MyUser::CurrentUser();

		if ($File==null)
		{
			$Res['Error']=v::Ecnf();
		}
		else{
			$R=new ReviewProcessClearance($File,$CurrentUser);
			$R->setComment(($Comment==null?"":$Comment));
			$er=$R->Check();
			if(!is_string($er)){
				$R->Apply();
				echo 'F';	
				//ORM::Write($R);
				echo 'G';	
				$Res['Class']=$R;
			}else{
				$Res['Error']=$er;
			}
		}
		
		return $Res;

	}
}
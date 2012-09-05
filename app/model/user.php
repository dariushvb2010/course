<?php
/**
 * 
 * 
 * @Entity @Table(name="jf_users")
 * @entity(repositoryClass="UserRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discriminator", type="string")
 * @DiscriminatorMap({"User" = "User", "Xuser" = "Xuser", "MyUser"="MyUser"})
 * */
class User
{
    /**
     * @GeneratedValue @Id @Column(type="integer")
     * @var string
     */
    public $ID;
	public function ID()
	{
		return $this->ID;
	}
    /**
     * @Column(type="string",unique="true")
     * @var string
     */
    public $Username;
	public function Username()
	{
		return $this->Username;
	}
    /**
     * @Column(type="string")
     * @var string
     */
    public $Password;
    
    /**
     * 
     * Password hash of a user
     * @return string
     */
    public function Password()
    {
    	return $this->Password;
    }
    
    public function SetUsernamePassword($Username=null,$Password=null){
    	if ($Username)
    	{
    		$this->Username=$Username;
    		$this->Password=j::$Session->PasswordHashString($Username, $Password);
    	}
    }

    function __construct($Username=null,$Password=null)
    {
    	$this->SetUsernamePassword($Username,$Password);	
    }
    
}


use \Doctrine\ORM\EntityRepository;
class UserRepository extends EntityRepository
{
}
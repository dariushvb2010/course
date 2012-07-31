<?php

class data{
		public $host;
        public  $database;
        public  $username;
        public  $password;
        public static $files = array("create.sql", "rolepermissions.sql", "users.sql", "configevent.sql");
        function __construct()
        {
        	$this->host = $_POST['host'];
             $this->database = $_POST['database'];
             
                $this->username = $_POST['username'];
                $this->password = $_POST['password'];  
                $con = mysql_connect($this->host,$this->username,$this->password) or die("cannot connect to database!");
                mysql_set_charset('utf8',$con);
	        $db = mysql_select_db($this->database);
        }

        public function execute($file)
        {
               $res=true;
                $contents = file_get_contents($this->fullpath($file));
//                 var_dump( $contents );
                $queries = preg_split("/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/", $contents);
                foreach ($queries as $query){
                	if (strlen(trim($query)) > 0) 
                		$res &= mysql_query($query) or die(mysql_error());
                }
                return $res;
        }
        public function echofile($file)
        {
        	if(file_exists($this->fullpath($file)))
        		echo "<li class='ok'>";
        	else 
        		echo "<li class='nok'>";
        	echo $file."</li>";
        }
        
        public function fullpath($fname){
        	return dirname(__FILE__)."/".$fname;
        }
        
        public function lockinstallation()
        {
        	$a='dd';
        	var_dump(file_exists($this->fullpath("../.htaccess2")));
        	try{
        		rename($this->fullpath("users2.sql"), $this->fullpath("abcd.sql"));
        		$a= copy($this->fullpath("users.sql"), $this->fullpath("abcd"));
        		//copy($source, $dest)
        	}catch(Exception $e)
        	{
        		var_dump($e."0");
        	}
        	return $a;
        }
}
?>


<html><head>
<style>
        body{margin:5px; padding:6px; }
        div#body{ margin:20px; }
        div.box{ display:inline-block; vertical-align: top; padding:5px;}
        div#sidebar{ width:20%; overflow:auto; border: 1px solid #333; min-height: 300px; }
        div#content{ width:75%; border:2px dotted teal;  }
        
        p{margin:0; padding:5px}
        .ok{ color:green;}
        .nok{ color:red;}
        span.ok{margin-right: 20px;}
        span.nok{margin-right:20px;}
        
        label{}
        input{float:right; margin-right:20px;}
        input[type=submit]{ float:none; margin:0;}
        form{}
</style>
</head><body>
<div id="body">
        <div id="header">
                <h1>installation</h1>
        </div>
        <div id="sidebar" class="box">
        
        this is sidebar of the implementation
        
        </div>
        
        <div id="content" class="box">
        		<div>
        		<?php $da = new data();
        		echo "<ul>"; 
        			foreach ($da::$files as $file)
        			{
        				$da->echofile($file);
        			}
        		echo "</ul>";
		        ?>
        		</div>
                <form method="post" >
                		<p><label>host: </label><input name="host" value="<?php echo isset($da->host) ? $da->host : "localhost"; ?>"/></p>
                        <p><label>database name: </label><input name="database" value="<?php echo $da->database; ?>"/></p>
                        <p><label>username: </label><input name="username" value="<?php echo $da->username; ?>"/></p>
                        <p><label>password: </label><input name="password" value="<?php echo $da->password; ?>"/></p>
                        <p align="center">
                                <input type="submit" name="deploy" value="deploy" />
                                <input type="submit" name="develop" value="develop"/>
                                <input type="submit" name="rolepermissions" value="rolepermissions" />
                                <input type="submit" name="users" value="users" />
                                <input type="submit" name="configevent" value="configevent" />
                                <input type="submit" name="lockinstallation" value="lock installation" />
                        </p>
                </form>
                
                <div id="result">
                    <?php
                    if( isset($_POST['deploy']) or isset($_POST['develop']) )
                    {
                        var_dump( $da->execute("create.sql") );  
                    }
                    elseif( isset($_POST['rolepermissions']) )
                    {
                        echo $da->execute("rolepermissions.sql");
                    }
                    elseif( isset($_POST['users']) )
                    {
                        $res = mysql_query("select * from app_MyUser");
                        if( $res)
                                echo $da->execute("users.sql");
                        else
                            echo "please update database schema!";
                    }
                    elseif( isset($_POST['configevent']) )
                    {
                        echo $da->execute("configevent.sql");
                    }
                    elseif( isset($_POST['lockinstallation']) )
                    {
                        echo $da->lockinstallation();
                    }
                        
                        
                    ?>
                </div>
                <form>
                        
                </form>
        </div>
        
</div>
</body></html>

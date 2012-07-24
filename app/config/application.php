<?php
#####################################################################
### this is the application configuration file, please configure  ###
### and customize your application before starting to use it      ###
#####################################################################



/**
 * Siteroot
 * 
 * jFramework requires to know where your site root is, e.g http://jframework.info
 * or http://tld.com/myfolder/myjf/deploy
 * jFramework automatically determines this, so change it and define it by hand only when 
 * using jFramework in non default way (e.g integration mode) 
 */
define ( "SiteRoot", jURL::Root () );
//define ( "SiteRoot", "http://localhost/jf" );
//if (defined("jfasalib") && constant("jfasalib") && constant("SiteRoot")==jURL::Root())
//	trigger_error("When using jFramework as a library, you should set SiteRoot manually.");



if (jURL::HTTPHost()=="localhost")
	reg("app/state","develop");
elseif (
strpos(jURL::HTTPHost(),".ir")!==false
or strpos(jURL::HTTPHost(),".com")!==false
or jURL::HTTPHost()=="10.32.0.19"
or jURL::HTTPHost()=="10.2.16.139"
or strpos(jURL::HTTPHost(),"192.168")!==false
) #replace this with your site
	reg("app/state","deploy");
elseif (php_sapi_name()=="cli")
	reg("app/state","develop");
else 
	reg("app/state","develop");

/**
 * Database Setup
 * 
 * jFramework requires at least a database for its core functionality. 
 * You should have a functional database with jFramework tables [and your own tables] to use them.
 * You can also use "no database-setup" if you do not need jFramework libraries and want a semi-static 
 * web application, In this case, comment or remove the database username definition
 */
if (reg("app/state")=="develop")
{
	reg("app/db/default/name","test2");
	reg("app/db/default/user","root"); #comment this for no-DB jFramework
	reg("app/db/default/pass","132465798");
	reg("app/db/default/host","127.0.0.1");

	reg("app/db/source/name","Customs");
	reg("app/db/source/user","Cadmin"); #comment this for no-DB jFramework
	reg("app/db/source/pass","1");
	reg("app/db/source/host","10.2.16.179:1433");
	
}
elseif (reg("app/state")=="deploy")
{
	reg("app/db/default/name","bazbini");
	reg("app/db/default/user","bazbini"); #comment this for no-DB jFramework
	reg("app/db/default/pass","TDmrheErbra79RNb"); #replace it with your own password
	reg("app/db/default/host","localhost");
}
/**
 * Adapter Setup
 * 
 * jFramework can use a SQLite database (which is not recommended for a medim+ traffic website,
 * it also supports a variety of database drivers via jFramework DBAL adapters.
 * 
 * Set an adapter here, or leave it commented, which defaults to "mysqli" adapter. Keep in mind
 * to set a folder with 777 rights if you want to use SQLite, or ReadOnly SQLite access.
 */
//reg("app/db/default/adapter","pdo_sqlite");
$x=new jpRoot ( );
$x=$x->__toString();
//reg("app/db/default/sqlite/folder",$x . "/install/db/");
#reg("app/db/default/sqlite/readonly",false); # only for SQLite when no write access is available


/**
 * Error Handling
 * 
 * jFramework has an advanced error handler built-in. You can disbale or enable it in setting.php
 * in either case, The errors should not be presented to the end user on a release environment,
 * So keep in mind to set this to false when releasing your software.
 * You can view errors in logs anytime.
 */
if (reg("app/state")=="develop")
	reg("jf/setting/PresentErrors",true);
else
	reg("jf/setting/PresentErrors",false);

reg("jf/setting/ErrorHandler",false); //Enables jFramework's built-in error handler
	
	
/**
 * Bandwidth Management
 * 
 * jFileManager has the ability to limit download speed of files larger than a specific size.
 * Set both the size and the limit here.
 */
//reg("jf/fileman/BandwidthLimit",100*1024); #comment this to disable limit
reg("jf/fileman/BandwidthLimitOn",1024*1024);

/**
 * Iterative Templates
 * 
 * If this is set, jFramework viewer would look into the view folder and all its ancestor folders
 * to find a template to run, So it would consume resources if depths are high and
 * templates are not available for subfolders. Recommended to be true.
 */
reg("jf/view/templates/iterative",true);
reg("jf/view/templates/iterations",1000); //maximum iterations, this prevents infinite loops

/**
 * Default Controller
 *
 * Use this to handle a lot of request on a single contorller.
 */
reg("jf/control/DefaultController/enabled",true); //enable default controller
reg("jf/control/DefaultController/iterative",true); //check in outer folders for the default controller
reg("jf/control/DefaultController/iterations",1000); //check in outer folders for the default controller
reg("jf/controller/ForceObjectOriented",true); //false this to use procedural controllers
/*
 * Enables ViewParserPlugin
 * 
 * The view parser plugin is a heavy plugin that parses the view and does a lot of things
 * (which can be configured inside itself) like extracting Meta and Title tags from a view
 * and attaching it to the header, prepending SiteRoot to absolute view paths so that
 * if your website lies in http://somesite.com/folder/jf and you have a link like
 * "/main" the link would change to "http://somesite.com/folder/jf/main" to prevent errors,
 * which would be by default "http://somesite.com/main"
 * 
 * This is recommended when using AutoController and strongly recommended when using
 * DefaultInterface and "/" Request Delimiter, but consumes O(n) memory for views and O(n)
 * processor time, so not recommended on huge views.
 */
reg("jf/view/parser",true);

/**
 * Default Interface
 * 
 * jFramework has a few interfaces, app, sys, service, file, etc.
 * If a request of no known interface is received, jFramework pops an error.
 * Set this to true to handle unkown requests as application requests instead of erroring
 * (recommended true) this usually only prepends "app/" to unknown requests.
 * 
 * The default application interface names makes you able to use http://site.com/jf/folder/
 * instead of http://site.com/jf/folder/main . you can set the default name here. its like Apache
 * index.
 */
reg("jf/controller/DefaultApplicationInterface","main"); # set to false to disable


/**
 * Controller Options
 * 
 * AutoController would allow a view without a controller to be presented automatically. This is
 * usually good with sites with few control and model logic, So that you can simply develop views
 * and they'll work without the need for a controller (suggested with ViewParser)
 * 
 * AutoPresent would automatically present a controller, if the developer forgets to do so. 
 * This is a good practice on forgetful and lazy programmers, and is not recommended.
 */
reg("jf/Controller/AutoController",true);
reg("jf/Controller/AutoPresent",false);



/**
 * UserAgent based views
 * 
 * Enabling this allows your jFramework setup to detect mobile browsers and cell-phone connectivity
 * and provide another view (instead of "default") for them. This would increase jFramework's
 * loading time slightly (1 hundredth of a second), but is enabled by default.
 */
reg("jf/view/detector/enabled",true);



/**
 * Session Timeout
 * a session would be automatically expired after NoAccess timeout if the visitor doesn't access
 * the application. Even if visitor accesses the application, session would be expired after
 * General timeout (security reasons). 
 * SweepRatio defines the percentage where expired session cleaner should be triggered.
 */
reg("jf/session/timeout/NoAccess",30*60); # 30 mins
reg("jf/session/timeout/General",7*24*60*60); # a week
reg("jf/session/SweepRatio",.1);

/**
 * Internationalization
 * Here you define languages for your application. jFramework i18n module will automatically
 * generate strings for you to translate your application. 
 */
reg("jf/i18n/langs",array(
	"current"=>"en",
	"default"=>"en", # this should not be changed at all, after starting the i18n. This is the base lang others are translated from
	"en"=>"English",
	"fa"=>"����������",
));
#####################################################################
#####################################################################
#####################################################################
/**
 * Automation
 * 
 * leave this section of configuration file be.
 */

$this->LoadCustomModule ( "config.my", "." ); # your application specific configurations

if (!reg("app/db/default/name"))	reg("app/db/default/name","");
if (!reg("app/db/default/user"))	reg("app/db/default/user","");
if (!reg("app/db/default/pass"))	reg("app/db/default/pass","");
if (!reg("app/db/default/host"))	reg("app/db/default/host","localhost");
?>

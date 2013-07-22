<?php
	
class PageError
{
	public static function Error404($header = null)
	{
		header("HTTP/1.0 404 Not Found");
		
		// get our render object
		$render = new Render();
		$render->setHeaderLine("Uh Oh, Something Went Wrong!");
		$render->getBreadCrumbs()->setText("Error:  404 Not Found");

		$render->setPageTitle("Page Not Found");
				
		$smarty = new SmartyFlashbang();
		$smarty->assign("page", $_SERVER["REQUEST_URI"]);
		
		if($header)
			$smarty->assign("header", $header);
		
		$render->addContent($smarty->fetch("error_404.tpl"));
		
		$render->display();
		
		die();		
	}
	
	/**
	* @desc Bad password for login
	*/
	public static function SecurityFail()
	{
		header('WWW-Authenticate: Basic realm="Asset Server Browser"');
		header('HTTP/1.0 401 Unauthorized');
		
		print "<h1>Security Failed</h1><br />";
		print "You must enter the proper security credentials to access this system.";

		die();
	}
}

?>
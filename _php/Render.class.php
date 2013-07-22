<?php
class Render
{
	protected $gamesList;
	protected $pageTitle = "Asset Server Browser";
	protected $pageContent = "";
	protected $pageHeaderLine = "Welcome!";
	protected $pageHeaderLink = "";
	protected $pageHeaderLine2 = "";
	protected $metaDescription = "";
	protected $metaKeywords = "";
	protected $pageAdditionalHead = "";
	protected $pageAdditionalFooter = "";
	protected $pageOnLoad = "";
	protected $lastBody = "";
	protected $breadCrumbs;
	protected $page = "";
	protected $jquery = "";

	public function __construct($page)
	{
		$this->breadCrumbs = new BreadCrumbs();
		$this->page = $page;

		AServer::$render = $this;
	}

	/**
	* @desc Add something to our document ready jquery
	*/
	public function addJQuery($script)
	{
		$this->jquery .= $script . "\n";
	}

	/**
	* @desc Get our breadcrumbs object
	*/
	public function getBreadCrumbs()
	{
		return $this->breadCrumbs;
	}

	/**
	* @desc Set our H1 tag
	*/
	public function setHeaderLine($headerLine, $headerLink = "")
	{
		$this->pageHeaderLine = $headerLine;
		$this->pageHeaderLink = $headerLink;
	}

	/**
	* @desc Set our secondary header
	*/
	public function setHeaderLine2($headerLine2)
	{
		$this->pageHeaderLine2 = $headerLine2;
	}

	/**
	* @desc Set the content variable
	*/
	public function setContent($content)
	{
		$this->pageContent = $content;
	}

	/**
	* @desc Add some content to our page
	*/
	public function addContent($content)
	{
		$this->pageContent .= $content;
	}

	/**
	* @desc Override the title with something else
	*/
	public function setPageTitle($title)
	{
		$this->pageTitle = $title;
	}

	/**
	* @desc Add info between our <head> tags
	*/
	public function addHead($head)
	{
		$this->pageAdditionalHead .= $head;
	}

	/**
	* @desc Add info to our footer
	*/
	public function addFooter($footer)
	{
		$this->pageAdditionalFooter .= $footer;
	}

	/**
	* @desc Add html just before the closing body tag
	*/
	public function addLastBody($html)
	{
		$this->lastBody .= $html;
	}

	/**
	* @desc Set a function for onload
	*/
	public function setOnLoad($function)
	{
		$this->pageOnLoad = "onLoad=\"$function\"";
	}

	/**
	* @desc Override the meta description
	*/
	public function setMetaDescription($description)
	{
		$this->metaDescription = $description;
	}

	/**
	* @desc Override the meta description
	*/
	public function setMetaKeywords($keywords)
	{
		$this->metaKeywords = $keywords;
	}

	public function display()
	{
		$smarty = new SmartyFlashbang();


		// Database Select
		$databases = AServer::GetDatabases();
		$projects = AServer::GetDatabaseProjectNames();
		$select = "";
		$found = false;

		for ( $x = 0; $x < count($databases); $x++) {
			$select .= "<option value=\"" . $databases[$x] . "\"";
			if ( addslashes($_GET['db']) == $databases[$x] ) {
				$select .= " selected";
				$found = true;
			}
			$select .= ">" . $projects[$x] . "</option>";
		}

		if ( !$found ) {
			$select = '<option value="Select A Database" selected>Select A Database</option>' . $select;
		}

		$smarty->assign("selectdatabase", $select);

		$smarty->assign("title", $this->pageTitle);
		$smarty->assign("metaDescription", $this->metaDescription);
		$smarty->assign("metaKeywords", $this->metaKeywords);

		$smarty->assign("breadcrumbs", $this->breadCrumbs->fetch());
		$smarty->assign("additionalHead", $this->pageAdditionalHead);
		$smarty->assign("additionalFooter", $this->pageAdditionalFooter);
		$smarty->assign("bodyonload", $this->pageOnLoad);

		$smarty->assign("headerLine", $this->pageHeaderLine);
		$smarty->assign("headerLink", $this->pageHeaderLink);

		$smarty->assign("headerLine2", $this->pageHeaderLine2);
		$smarty->assign("content", $this->pageContent);
		$smarty->assign("lastbody", $this->lastBody);

		$smarty->assign("jquery", $this->jquery);

		$smarty->assign("page", $this->page);


		if(Debug::isDebug())
			$smarty->assign("debug", Debug::footer());

		if ( $_SESSION['databases']) {
			$smarty->assign("logged", 1);
		}

		$smarty->display("layout.tpl");
	}
}
?>
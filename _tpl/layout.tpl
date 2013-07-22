<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>UASB - {$title}</title>
<script src="/_js/jquery.js"></script>
<meta name="description" content="{$metaDescription}" />
<meta name="keywords" content="{$metaKeywords}" />
<link rel="stylesheet" href="/_css/global.css" type="text/css" />
{$additionalHead}
<script type="text/javascript">
{literal}$(document).ready(function() { {/literal}
{$jquery}
{literal}});{/literal}
</script>
</head>
<body {$bodyonload}>
<div class="maincont">
<div id="content">
<div id="header">
<h1><a class="title" href="/">Unity</a> &raquo; {if $headerLink}<a class="title" href="{$headerLink}">{/if}{$headerLine}{if $headerLink}</a>{/if}</h1>{if $logged}<table class="right"><tr><td><select name="database" onChange="location = 'database.php?db=' + this.options[this.selectedIndex].value;">{$selectdatabase}</select></td></tr></table>{/if}<br /><br />
</div>
{$content}
{$analytics}
{$lastbody}
<br />
<div id="footer">
<center>&copy; Flashbang Studios, LLC<br />&copy; dotBunny Inc.</center>
</div>

</div></div>
</body>
</html>

<h3>{$projectname}:</h3>
<div class="box"><a href="rss.php?db={$database}&i=15" style="float: right"><img border="0" src="_images/rss.jpg"></a>
<table>
<tr><td align="right">More:</td><td><a href="database.php?db={$database}">View detailed report</a></td></tr>
<tr><td align="right" width="150">Versions:</td><td>{$versions}</td></tr>
<tr><td align="right">Users:</td><td>{$users}</td></tr>
<tr><td align="right">Unique Assets:</td><td>{$unique}</td></tr>
<tr><td align="right">Asset Versions:</td><td>{$assetversions}</td></tr>

<tr><td align="right" valign="top">Updates:</td><td><strong>{$time}</strong>, <a href="person.php?db={$database}&serial={$creator}">{$name}</a> updated to <a href="changeset.php?db={$database}&serial={$serial}">{$serial}</a><br /><em>{$description}</em><br />

<a id="show_{$database}" href="">More Updates &raquo;</a><br /><br />

<div id="more_{$database}">

{section name=i loop=$more}
<strong>{$more[i].time}</strong>, <a href="person.php?db={$database}&serial={$more[i].creator}">{$more[i].name}</a> updated to <a href="database.php?db={$database}#{$more[i].serial}">{$more[i].serial}</a><br /><em>{$more[i].description}</em><br /><br />
{/section}

</div>

</td></tr>
</table>
</div>
<br />
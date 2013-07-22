<h3>{$name} in {$projectname}:</h3>
<div class="box">
<table>
<tr><td align="right" width="150">Changesets:</td><td>{$changesets}</td></tr>
<tr><td align="right" width="150">Asset Versions:</td><td>{$assetversions}</td></tr>
<tr><td align="right" width="150">Scripts >50%:</td><td>
{section name=i loop=$scripts}
{$scripts[i].percent}% <a href="asset.php?db={$database}&serial={$scripts[i].serial}">{$scripts[i].name}</a><br />
{/section}

</td></tr>
</td></tr>
</table>
</div>
<br />
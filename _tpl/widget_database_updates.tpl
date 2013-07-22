<h3>Commits to {$projectname}:</h3>
<div class="box">

<table class="allupdates">

{section name=i loop=$updates}
<tr bgcolor="{cycle values="#eeeeee,#fafafa"}">
<td class="padded" valign="top"><a name="{$updates[i].serial}" href="changeset.php?db={$database}&serial={$updates[i].serial}" class="serial">{$updates[i].serial}</a>
<br /><a href="person.php?db={$database}&serial={$updates[i].creator}" class="person">{$updates[i].username}</a><br />
{$updates[i].time}
</td>
<td valign="top" class="padded" width="350"><p class="description">{$updates[i].description|nl2br}</p></td>
<td valign="top" class="padded">

<ol>
{section name=j loop=$updates[i].assets}
{if $updates[i].assets[j].asset != $asset}
<li><a href="asset.php?db={$database}&serial={$updates[i].assets[j].asset}">{$updates[i].assets[j].name}</a> ({$updates[i].assets[j].revision})
{* we're tracking this asset, highlight it but good! *}
{else}
<li class="highlight"><a name="revision_{$updates[i].assets[j].revision}" />{$updates[i].assets[j].name} ({$updates[i].assets[j].revision})
{/if}


</li>
{/section}
</ol>

</td>
</tr>
{/section}

</table>
</div>
<br />
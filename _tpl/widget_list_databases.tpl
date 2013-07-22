<h3>Active Databases:</h3>
<div class="box">
<ul>
{section name=i loop=$databases}
<li><a href="database.php?db={$databases[i]}">{$projectnames[i]}</a></li>
{/section}
</ul>
</div>
<br />
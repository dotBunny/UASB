<h3>Users in {$projectname}</h3>
<div class="box">
<ul>
{section name=i loop=$users}
<li><a href="person.php?db={$database}&serial={$users[i].serial}">{$users[i].name}</a></li>
{/section}
</ul>
</div>
<br />
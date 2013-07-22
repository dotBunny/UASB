<h3>TODO:</h3>
<div class="box">
{section name=i loop=$todos}
<a href="asset.php?db={$database}&serial={$todos[i].asset}#line_{$todos[i].number}">{$todos[i].name}, line {$todos[i].number}</a>: {$todos[i].line}<br />
{/section}
</div>
<br />
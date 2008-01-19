<div class="content">
    <h3>Trasa dla {$line}</h3>
    <ol class="lines">
{foreach from=$route item=foo}
	<li><a href="./?b={$foo.przystanki_id}">{$foo.nazwa}</a></li>
{/foreach}
    </ul>
</div> 

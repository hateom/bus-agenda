<div class="content">
    <h3>Trasa dla {$line}</h3>
    <ul class="lines">
{foreach from=$route item=foo}
	<li>{$foo.numer_kolejny} : <a href="./?b={$foo.przystanki_id}">{$foo.nazwa}</a></li>
{/foreach}
    </ul>
</div> 

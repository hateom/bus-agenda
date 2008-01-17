<div class="content">
    <p>
        Linie
    </p>
    <ul class="lines">
{foreach from=$route item=foo}
	<li>{$foo.numer_kolejny} : <a href="./?b={$foo.przystanki_id}">{$foo.nazwa}</a></li>
{/foreach}
    </ul>
</div> 

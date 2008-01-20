<div class="content">
	<h3>Edytuj rozkład jazdy dla linii: {$line}</h3>
    
    {if $rev == 0}
    	<p>{$first} - {$last} (<a href="?a=line&ett={$line}&reversed">odwróć</a>)</p>
    {else}
    	<p>{$last} - {$first} (<a href="?a=line&ett={$line}">odwróć</a>)</p>
    {/if}
    
    <form>
    <div id="route">
    <p>Godziny odjazdów z pętli:</p>
		<ol id="hours">
	        {foreach from=$table item=foo}
	        	<li class="time"><input type="text" value="{$foo.godzina}" name="hour[]" /></li>
			{/foreach}
		</ol>
    	<input id="add_hour" type="button" value="Dodaj godzinę" />
    </div>
    <div id="timetable">
    	<p>Przesunięcia czasowe: </p>
    	<ol>
        	{foreach from=$offset item=foo}
	        	<li><input type="text" name="{$foo.przystanek_id}" value="{$foo.offset}" /> <em>{$foo.nazwa}</em></li>
            {/foreach}
        </ol>
    </div>
    <div class="clear"></div>
    <input type="submit" value="Zatwierdź"/>
    </form>
</div>
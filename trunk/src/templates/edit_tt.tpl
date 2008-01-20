<div class="content">
	<h3>Edytuj rozkład jazdy dla linii: {$line}</h3>
    
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
    </div>
    </form>
    <div class="clear"></div>
</div>
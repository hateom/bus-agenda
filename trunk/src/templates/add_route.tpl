<div class="content">
	<h3>Tworzenie lini {$line}</h3>
    
    <form id="form_add_route" method="post" action="./?a=line">
    	<label for="desc">Opis: </label>
        <input type="text" name="desc" />
        
        <p>Trasa:</p>
    	<ol id="form_add_line">
			<li><select name="route[]">
				{foreach from=$bs item=foo}
                    <option value="{$foo.id}">{$foo.nazwa}</option>
				{/foreach}
			</select></li>
        </ol>
        <input type="hidden" name="store" value="1"  />
        <input type="hidden" name="line" value="{$line}"  />
        <input id="add_bs" type="button" value="Dodaj przystanek" />
    	<input type="submit" value="Zatwierdź linię" />
    </form>
    
</div>
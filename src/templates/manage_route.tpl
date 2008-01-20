<div class="content">
	<h3>Edytuj trasę: <em>{$line}</em></h3>
    
    <form id="form_add_route" method="post" action="./?a=line">
    	<label for="name">Linia: </label>
        <input type="text" name="name" value="{$line}" />

    	<label for="desc">Opis: </label>
        <input type="text" name="desc" value="{$desc}" />
        
        <p>Trasa:</p>
    	<ol id="form_add_line">
        	{foreach from=$route item=bar}
				<li><select name="route[]">
					{foreach from=$bs item=foo}
                    	{if $bar.nazwa == $foo.nazwa}
    	    	            <option selected value="{$foo.id}">{$foo.nazwa}</option>
                        {else}
	        	            <option value="{$foo.id}">{$foo.nazwa}</option>
                        {/if}
					{/foreach}
				</select></li>
            {/foreach}
        </ol>
        <input type="hidden" name="update" value="1"  />
        <input type="hidden" name="line" value="{$line}"  />
        <input id="add_bs" type="button" value="Dodaj przystanek" />
    	<input type="submit" value="Zapisz zmiany" />
    </form>
</div>
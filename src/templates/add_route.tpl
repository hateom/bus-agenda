<div class="content">
	<h3>Tworzenie lini {$line}</h3>
    
    <form>
    	<div id="form_add_line">
			<select name="from">
				{foreach from=$bs item=foo}
                    <option value="{$foo.id}">{$foo.nazwa}</option>
				{/foreach}
			</select>
        </div>
    	<input type="submit" value="Zatwierdź linię" />
    </form>
    
</div>
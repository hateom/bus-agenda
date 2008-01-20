<div class="content">
	<h3>Nowy przystanek: <em>{$bs}</em></h3>
    
    <form id="form_add_bs" method="post" action="./?a=bs">
    	<label for="street1">Ulica 1:</label>
        <select name="street1">
        {foreach from=$streets item=foo}
	        <option value="{$foo.id}">{$foo.nazwa}</option>
        {/foreach}
        </select>
        <label for="street2">Ulica 2:</label>
        <select name="street2">
        {foreach from=$streets item=foo}
	        <option value="{$foo.id}">{$foo.nazwa}</option>
        {/foreach}
        </select>
        <input type="hidden" name="name" value="{$bs}" />
        <input type="submit" value="Zatwierdź przystanek" />
    </form>
</div>
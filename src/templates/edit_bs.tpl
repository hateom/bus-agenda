<div class="content">
	<h3>Edytuj przystanek: <em>{$bs}</em></h3>
    
    <form id="form_add_bs" method="post" action="./?a=bs&u">
		<label for="street1">Nazwa:</label>
        <input type="text" name="name" value="{$bs}" />
    	<label for="street1">Ulica 1:</label>
        <select name="street1">
        {foreach from=$streets item=foo}
        	{if $foo.id == $street1_id}
		        <option selected value="{$foo.id}">{$foo.nazwa}</option>
            {else}
		        <option value="{$foo.id}">{$foo.nazwa}</option>
            {/if}
        {/foreach}
        </select>
        <label for="street2">Ulica 2:</label>
        <select name="street2">
        {foreach from=$streets item=foo}
        	{if $foo.id == $street2_id}
		        <option selected value="{$foo.id}">{$foo.nazwa}</option>
            {else}
		        <option value="{$foo.id}">{$foo.nazwa}</option>
            {/if}
        {/foreach}
        </select>
        <input type="hidden" name="bs_id" value="{$bs_id}" />
        <input type="submit" value="Zatwierdź przystanek" />
    </form>
</div>
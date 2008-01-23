<div class="content">
    <h3>Zarządzaj ulicami</h3>
    <form action="?a=streets" method="post">
        <label for="add_bs">Dodaj ulicę:</label>
        <input type="text" name="add" />
        <input type="submit" value="OK" />
    </form>

    <ul>
{foreach from=$streets item=foo}
        <li><form class="delete" method="post" action="?a=streets">
        	<input type="text" name="name" value="{$foo.nazwa}" />
            <input type="hidden" name="id" value="{$foo.id}" />
            <input type="submit" value="OK" />
        </form>
        <form class="delete" method="get" action="./">
        	<input type="hidden" name="street" value="{$foo.id}" />
            <input type="hidden" name="delete" value="street" />
            <input type="submit" value="Usuń" />
        </form></li>
{/foreach}
    </ul>
</div>

<div class="content">
    <h3>Zarządzaj przystankami</h3>
    <form action="?a=bs" method="post">
        <label for="add_bs">Dodaj przystanek:</label>
        <input type="text" name="add_bs" />
        <input type="submit" value="OK" />
        <input type="hidden" name="add" value="1" />
    </form>

    <ul>
{foreach from=$bs item=foo}
        <li>{$foo.nazwa} <em><a href="?a=bs&e={$foo.id}">zmień</a>, <a href="?a=bs&d={$foo.id}">usuń</a></em></li>
{/foreach}
    </ul>
</div>

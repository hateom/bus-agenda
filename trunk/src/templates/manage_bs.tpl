<div class="content">
    <h3>Zarządzaj przystankami</h3>
    <form action="?a=bs" method="post">
        <label for="add_bs">Dodaj przystanek:</label>
        <input type="text" name="add" />
        <input type="submit" value="OK" />
    </form>

    <ul>
{foreach from=$bs item=foo}
        <li>{$foo.nazwa} <em><a href="?a=bs&amp;e={$foo.id}">zmień</a>, <a href="?delete=bs&amp;bs={$foo.id}">usuń</a></em></li>
{/foreach}
    </ul>
</div>

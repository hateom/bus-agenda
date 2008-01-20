<div class="content">
    <h3>Zarządzaj liniami</h3>
    <form action="?a=line" method="get">
        <label for="add">Dodaj linię:</label>
        <input type="text" name="add" />
        <input type="submit" value="OK" />
        <input type="hidden" name="a" value="line" />
    </form>

    <ul>
{foreach from=$lines item=foo}
        <li>{$foo.numer} <em><a href="?a=line&e={$foo.numer}">zmień</a><!--, <a href="?a=line&d={$foo.numer}">usuń</a>--></em></li>
{/foreach}
    </ul>
</div>

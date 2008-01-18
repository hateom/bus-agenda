<div class="content">
    <p>
        Znajdź połączenie:
        <form id="form_conn" method="post" action="./?i=conn">
            <ul id="find_conn">
                <li><label for="from">Z przystanku: </label>
                <select name="from">
{foreach from=$bs item=foo}
                    <option value="{$foo.id}">{$foo.nazwa}</option>
{/foreach}
                </select>
                </li>
                <li><label for="to">Do przystanku: </label>
                <select name="to">
{foreach from=$bs item=foo}
                    <option value="{$foo.id}">{$foo.nazwa}</option>
{/foreach}
                </select>
                </li>
                <li><label for="time">O godzinie: </label><input type="text" name="time" value="{$time}"/></li>
                <li><input id="search_btn" type="submit" value="Search" /></li>
            </ul>
            <input type="hidden" name="search" value="1" />
        </form>
    </p>
</div>

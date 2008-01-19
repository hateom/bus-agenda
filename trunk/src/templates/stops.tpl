<div class="content">
    <p>
        Wybierz przystanek:
    </p>
    <ul class="lines">
{foreach from=$bs item=foo}
        <li><a href="./?b={$foo.id}">{$foo.nazwa}</a> {$foo.ulica1}, {$foo.ulica2}</li>
{/foreach}
    </ul>
</div>

<div class="content">
    <p>
        Wybierz przystanek:
    </p>
    <ul class="lines">
{foreach from=$bs item=foo}
        <li><a href="./?b={$foo.id}">{$foo.nazwa}</a></li>
{/foreach}
    </ul>
</div>

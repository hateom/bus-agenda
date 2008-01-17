<div class="content">
    <p>
        Wybierz linię:
    </p>
    <ul class="lines">
{foreach from=$lines item=foo}
        <li><a href="./?l={$foo.numer}">{$foo.numer}</a></li>
{/foreach}
    </ul>
</div>

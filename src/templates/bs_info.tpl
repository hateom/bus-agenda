<div class="content">
    <h3>Informacje o przystanku <em>{$bs}</em></h3>
    <p>Przez dany przystanek przejeżdżają linie:</p>
    <ul class="lines">
{foreach from=$bs_info item=foo}
        <li><a href="?l={$foo.numer}">{$foo.numer}</a></li>
{/foreach}
    </ul>
</div>

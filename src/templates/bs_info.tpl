<div class="content">
    <h3>Informacje o przystanku <em>{$bs}</em></h3>

    <ul class="lines">
{foreach from=$bs_info item=foo}
        <li><a href="">{$foo.numer}</a></li>
{/foreach}
    </ul>
</div>

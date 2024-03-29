<div class="content">
    <div id="route">
        <h3>Trasa dla {$line}</h3>
        {if $rev == 0}
            <p>{$first} - {$last} (<a href="?l={$line}&amp;reverse">odwróć</a>)</p>
        {else}
            <p>{$last} - {$first} (<a href="?l={$line}">odwróć</a>)</p>
        {/if}
        <ol class="lines">
        {foreach from=$route item=foo}
            {if $rev == 0}
                <li><a href="./?l={$line}&amp;tt={$foo.przystanki_id}">{$foo.nazwa}</a> <a href="./?b={$foo.przystanki_id}">*</a></li>
            {else}
                <li><a href="./?l={$line}&amp;reverse&tt={$foo.przystanki_id}">{$foo.nazwa}</a> <a href="./?b={$foo.przystanki_id}">*</a></li>
            {/if}
        {/foreach}
        </ul>
    </div>
    {if $tt==1}
    <div id="timetable">
        <h3>Rozkład jazdy na przystanku {$bs}</h3>
        <ul>
            {foreach from=$ttable item=foo}
                <li class="time">{$foo.odj}</li>
            {/foreach}
        </ul>
    </div>
    {/if}
    <div class="clear"></div>
</div> 

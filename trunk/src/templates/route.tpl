<div class="content">
    <div id="route">
        <h3>Trasa dla {$line}</h3>
        <ol class="lines">
        {foreach from=$route item=foo}
	        <li><a href="./?l={$line}&amp;tt={$foo.przystanki_id}">{$foo.nazwa}</a> <a href="./?b={$foo.przystanki_id}">*</a></li>
        {/foreach}
        </ul>
    </div>
    <div id="timetable">
        <h3>Rozkład jazdy</h3>
        <ol>
            <li></li>
        </ol>
    </div>
    <div class="clear"></div>
</div> 
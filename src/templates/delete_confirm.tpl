<div class="content">
	<div class="error">
    	<strong>Uwaga!</strong>
        <p>{$msg}</p>
        <form action="./" method="get" class="confirm">
        	<input type="hidden" name="discard" value="" />
        	<input type="submit" value="Nie" />
            <input type="hidden" name="a" value="{$redir}" />
        </form>
        <form action="./?a={$redir}" method="post" class="confirm">
        	<input type="hidden" name="d" value="{$action}" />
        	<input type="hidden" name="id" value="{$id}" />
        	<input type="submit" value="Tak" />
        </form>
    </div>
</div>

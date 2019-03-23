<p class="alert alert-danger">
	WordpressIntegration module error
	{if isset($wpi_errors) and !empty($wpi_errors)} :
		{foreach from=$wpi_errors item=wpi_error}
			<br>{$wpi_error}
		{/foreach}
	{/if}
</p>
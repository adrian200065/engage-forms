<style>
	.engage-editor-header-nav {
		height: 48px;
	}

	li.engage-forms-toolbar-item {
		padding: 0;
	}


	.panel-footer {
		position: absolute;
		bottom: 0px;
		padding: 6px;
		background: none repeat scroll 0 0 rgba(0, 0, 0, 0.03);
		left: 0px;
		right: 0px;
		border-top: 1px solid rgba(0, 0, 0, 0.06);
	}
	a.button {
		text-align: center;
		width: 100%;
		background-color: #ff7e30 !important;
		color: #fff !important;

	}
	a.button:hover {
		background-color: #a3bf61 !important;
		color: #fff !important;

	}

	.addon-panel {
		margin: 10px;
		width: 220px;
		float: left;
		height: 250px;
		position: relative;
		padding: 0;
		border: 1px solid #a3bf61;
		border-radius: 2px;
		background: #fff;
	}
</style>

<div class="engage-editor-header">
	<ul class="engage-editor-header-nav">
		<li class="engage-editor-logo">
			<span class="engage-forms-name">Engage Forms</span>
		</li>
		<li class="engage-forms-toolbar-item active">
			<a href="https://engageforms.com/engage-forms-add-ons?utm_source=dashboard&utm_medium=extend-submenu&utm_campaign=engage-forms" title="<?php esc_attr_e( 'View Engage Forms Add-ons', 'engage-forms' ); ?>" target="_blank">
				<?php esc_html_e( 'Add-ons', 'engage-forms' ); ?>
			</a>
		</li>
	</ul>
</div>

<div class="form-extend-page-wrap" id="form-extend-viewer" style="visibility:visible;">
	<div id="ef-addons"></div>
</div>

<script type="text/javascript">
	jQuery( document ).ready(function($){
		<?php
		$data = Engage_Forms_Admin_Feed::get_ef_addons();
		$addons[ 'extensions' ] = $data;

		echo "var add_ons = " . json_encode($addons).";";
		?>
		var source   = $('#tmpl-addons').html();

		var template = Handlebars.compile(source);
		var html    = template(add_ons);
		$( '#ef-addons' ).html( html );
	});




</script>
<!-- Template for Addons-->
<script type="text/html" id="tmpl-addons">

	{{#if extensions}}
		{{#each extensions}}
			<div class="addon-panel" >
			{{#if image_src}}
				<img src="{{image_src}}" style="width:100%;vertical-align: top;">
			{{/if}}
			{{#if name}}
				<h2>{{name}}</h2>
			{{/if}}
			{{#if tagline}}
				<div style="margin: 0px; padding: 6px 7px;">{{{tagline}}}</div>
			{{/if}}

			<div class="panel-footer">

				<a class="button" href="{{link}}" target="_blank" rel="nofollow">
					<?php esc_html_e( 'Learn More', 'engage-forms' ); ?>
				</a>

			</div>

			</div>
		{{/each}}

	{{/if}}


</script>
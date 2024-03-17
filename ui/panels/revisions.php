<?php
if( Engage_Forms_Admin::is_revision_edit() ){
	printf( '<div class="notice"><p>%s</p></div>', esc_html__( 'Currently Viewing A Revision', 'engage-forms' ) );
}

?>
<div id="engage-forms-revisions"></div>
<span id="engage-forms-revisions-spinner" class="spinner"></span>
<script type="text/html" id="tmpl--revisions">
	<div id="engage-forms-revisions-list">
		{{#if revisions}}
		<fieldset>
			<legend>
				<?php esc_html_e( 'Choose Revision To Edit', 'engage-forms' ); ?>
			</legend>

		{{#each revisions}}

				<div class="engage-config-group">
					<label for="restore-{{id}}">
						<?php esc_html_e( 'Edit Revision:', 'engage-forms' ); ?> {{id}}
					</label>
					<input type="radio" name="engage-forms-revision" value="{{id}}" id="restore-{{id}}" data-edit="{{edit}}" />
				</div>

		{{/each}}
		</fieldset>

		<a href="#" id="engage-forms-revision-go" class="button" class="notice notice-error" style="display: none;" aria-hidden="true" role="button">
			<?php esc_html_e( 'View Selected Revision', 'engage-forms' ); ?>
		</a>
		{{else}}
		<?php esc_html_e( 'No Saved Revisions', 'engage-forms' ); ?>
		{{/if}}
	</div>

</script>









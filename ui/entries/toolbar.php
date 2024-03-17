		<?php
			$entry_perpage = get_option( '_engage_forms_entry_perpage', 20 );
		?>
		<div class="engage-entry-exporter" style="display:none;">
			<?php wp_nonce_field( 'ef_toolbar', 'ef_toolbar_actions' ); ?>
			<span class="ef-tools-row">
				<?php if( empty( $is_pinned ) ){ ?>
				<button id="ef_forms_toggle" type="button" class="button hide-forms" title="<?php esc_attr_e( 'Click to close entry viewer', 'engage-forms' ); ?>" style="padding: 3px; margin-top: 1px; margin-right: 18px; color: rgb(143, 143, 143);">				
					<span class="dashicons dashicons-admin-collapse"></span>
					<span class="screen-reader-text">
						<?php esc_html_e( 'Close', 'engage-forms' ); ?>
					</span>
				</button>
				<?php } ?>				
				<span class="toggle_option_preview">

					<button type="button" class="status_toggles button button-primary ajax-trigger" style="margin-top: 1px;"
						data-before="ef_set_limits"
						data-action="browse_entries"
						data-target="#form-entries-viewer"
						data-form=""
						data-template="#forms-list-alt-tmpl"
						data-load-class="spinner"
						data-active-class="button-primary"
						data-group="status_nav"
						data-callback="setup_pagination"
						data-page="1"
					    data-nonce="<?php echo esc_attr( wp_create_nonce( 'view_entries' ) ); ?>"
						data-status="active"
					    data-perpage="<?php echo esc_attr( Engage_Forms_Entry_Viewer::entries_per_page() ); ?>"
					><?php esc_html_e('Active', 'engage-forms'); ?> <span class="current-status-count"></span></button>
					<button type="button" class="status_toggles button ajax-trigger" style="margin-top: 1px; margin-right: 10px;"
						data-before="ef_set_limits"
						data-action="browse_entries"
						data-target="#form-entries-viewer"
						data-form=""
						data-template="#forms-list-alt-tmpl"
						data-load-class="spinner"
						data-active-class="button-primary"
						data-group="status_nav"
						data-callback="setup_pagination"
						data-page="1"
						data-perpage="<?php echo esc_attr( Engage_Forms_Entry_Viewer::entries_per_page() ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'view_entries' ) ); ?>"
				        data-status="trash"
					><?php echo esc_html_x( 'Trash', 'Status: In trash', 'engage-forms' ); ?> <span class="current-status-count"></span></button>
				</span>
				<a href="" role="button" class="button engage-forms-entry-exporter">
					<?php esc_html_e( 'Export All', 'engage-forms' ); ?>
				</a>
				
			</span>
			<span class="ef-tools-row ef-tools-row-second">
				<label for="ef_bulk_action" class="screen-reader-text">
					<?php esc_html_e( 'Bulk Action For Entries', 'engage-forms' ); ?>
				</label>
				<select id="ef_bulk_action" name="action" style="vertical-align: top;">
					<option selected="selected" value="">
						<?php esc_html_e( 'Bulk Actions', 'engage-forms' ); ?>
					</option>
					<option value="export">
						<?php esc_html_e( 'Export Selected', 'engage-forms' ); ?>
					</option>
					<option value="trash">
						<?php esc_html_e( 'Move to Trash', 'engage-forms' ); ?>
					</option>
				</select>
				<button type="button" class="button ef-bulk-action">
					<?php esc_html_e( 'Apply', 'engage-forms' ); ?>
				</button>
			</span>

		</div>

		<?php
		/**
		 * Runs at the end of the entries toolbar
		 *
		 * @since unknown
		 */
		do_action('engage_forms_entries_toolbar');
		?>


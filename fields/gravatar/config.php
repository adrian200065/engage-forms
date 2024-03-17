<div class="engage-config-group">
	<label>
        <?php esc_html_e('Email Field', 'engage-forms'); ?>
    </label>
	<div class="engage-config-field">
		{{{_field slug="email" type="email"}}}
	</div>
</div>

<?php
$avatar_defaults = array(
	'mystery' => __('Mystery Person', 'engage-forms'),
	'blank' => __('Blank', 'engage-forms'),
	'gravatar_default' => __('Gravatar Logo', 'engage-forms'),
	'identicon' => __('Identicon (Generated)', 'engage-forms'),
	'wavatar' => __('Wavatar (Generated)', 'engage-forms'),
	'monsterid' => __('MonsterID (Generated)', 'engage-forms'),
	'retro' => __('Retro (Generated)', 'engage-forms')
);

?>
<div class="engage-config-group">
	<label for="{{_id}}_fallback">
        <?php esc_html_e('Fallback', 'engage-forms'); ?>
    </label>
	<div class="engage-config-field">

		<select class="field-config block-input" name="{{_name}}[generator]" id="{{_id}}_fallback">
		<?php foreach($avatar_defaults as $av_type=>$av_name){
			echo "<option value=\"".$av_type."\" {{#is generator value=\"".$av_type."\"}}selected=\"selected\"{{/is}}>".$av_name."</option>\r\n";
		}
		?>
		</select> 
	</div>
</div>


<div class="engage-config-group">
	<label for="{{_id}}_size">
        <?php esc_html_e('Size', 'engage-forms'); ?>
    </label>
	<div class="engage-config-field">
		<input id="{{_id}}_size" type="number" class="field-config" name="{{_name}}[size]" value="{{#if size}}{{size}}{{else}}100{{/if}}" style="width:70px;"> px
	</div>
</div>

<div class="engage-config-group">
	<label for="{{_id}}_border_color">
		<?php esc_html_e('Border Color'); ?>
	</label>
	<div class="engage-config-field">
		<input type="text" class="color-field field-config" id="{{_id}}_border_color" name="{{_name}}[border_color]" value="{{#if config/border_color}}{{config/border_color}}{{else}}#efefef{{/if}}">
	</div>
</div>

<div class="engage-config-group">
	<label for="{{_id}}_border_size">
		<?php esc_html_e('Border Size', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input type="number" class="field-config" name="{{_name}}[border_size]" id="{{_id}}_border_size" value="{{#if border_size}}{{border_size}}{{else}}3{{/if}}" style="width:70px;"> px
	</div>
</div>

<div class="engage-config-group">
	<label for="{{_id}}_border_radius">
		<?php esc_html_e('Border Radius', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input type="number" class="field-config" id="{{_id}}_border_radius" name="{{_name}}[border_radius]" value="{{#if border_radius}}{{border_radius}}{{else}}3{{/if}}" style="width:70px;"> px
	</div>
</div>
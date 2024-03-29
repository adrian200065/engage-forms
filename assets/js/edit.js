/**
 * Pre compile all Handelbars templates
 */
function pre_compile_templates() {
	var pretemplates = jQuery(".ef-editor-template");
	for (var t = 0; t < pretemplates.length; t++) {
		compiled_templates[pretemplates[t].id] = Handlebars.compile(
			pretemplates[t].innerHTML
		);
	}
}

var efAdminAJAX;
if ("object" == typeof EF_ADMIN) {
	efAdminAJAX = EF_ADMIN.adminAjax;
} else {
	//yolo
	efAdminAJAX = ajaxurl;
}

/**
 * Get a compiled Handlebars template or the fallback template
 * @param template
 * @returns {*}
 */
function get_compiled_template(template) {
	if ("object" != typeof compiled_templates) {
		pre_compile_templates();
	}
	return compiled_templates[template + "_tmpl"]
		? compiled_templates[template + "_tmpl"]
		: compiled_templates.noconfig_field_templ;
}

jQuery( function ($) {
	var $spinner = jQuery("#save_indicator");

	jQuery("#engage-forms-restore-revision").on("click", function (e) {
		e.preventDefault();
		var $el = jQuery(this);
		$spinner.addClass("loading");

		jQuery.post({
			url: "admin.php?page=engage-forms",
			data: {
				ef_edit_nonce: jQuery("#ef_edit_nonce").val(),
				form: $el.data("form"),
				ef_revision: jQuery("#form_db_id_field").val(),
				restore: true,
			},
			success: function () {
				window.location = $el.data("edit-link");
			},
		});
	});

	/*
	 *	Build the fieltypes config
	 *	configs are stored in the .engage-config-field-setup field within the parent wrapper
	 *
	 */
	function build_fieldtype_config(el) {
		var select = jQuery(el);
		var val = select.val();
		var parent = select.closest(".engage-editor-field-config-wrapper"),
			target = parent.find(".engage-config-field-setup"),
			template = get_compiled_template(val),
			config = parent.find(".field_config_string").val(),
			current_type = select.data("type");
		parent.find(".engage-config-group").show();

		select.addClass("field-initialized");

		// Be sure to load the fields preset when switching back to the initial field type.
		if (config.length && current_type === select.val()) {
			config = JSON.parse(config);
		} else {
			// default config
			config = fieldtype_defaults[select.val() + "_efg"];
		}

		// build template
		if (!config) {
			config = {};
		}

		config._id = select.data("field");
		config._name = "config[fields][" + select.data("field") + "][config]";
		template = jQuery("<div>").html(template(config));

		// send to target
		target.html(template.html());

		// check for init function
		if (typeof window[select.val() + "_init"] === "function") {
			window[select.val() + "_init"](select.data("field"), target);
		}

		// remove not supported stuff
		if (fieldtype_defaults[select.val() + "_nosupport"]) {
			if (
				fieldtype_defaults[select.val() + "_nosupport"].indexOf("hide_label") >=
				0
			) {
				parent
					.find(".hide-label-field")
					.hide()
					.find(".field-config")
					.prop("checked", false);
			}
			if (
				fieldtype_defaults[select.val() + "_nosupport"].indexOf("caption") >= 0
			) {
				parent.find(".caption-field").hide().find(".field-config").val("");
			}
			if (
				fieldtype_defaults[select.val() + "_nosupport"].indexOf("required") >= 0
			) {
				parent
					.find(".required-field")
					.hide()
					.find(".field-config")
					.prop("checked", false);
			}
			if (
				fieldtype_defaults[select.val() + "_nosupport"].indexOf(
					"custom_class"
				) >= 0
			) {
				parent.find(".customclass-field").hide().find(".field-config").val("");
			}
			if (
				fieldtype_defaults[select.val() + "_nosupport"].indexOf("entry_list") >=
				0
			) {
				parent
					.find(".entrylist-field")
					.hide()
					.find(".field-config")
					.prop("checked", false);
			}
		}

		// seup options
		parent.find(".toggle_show_values").trigger("change");

		if (!jQuery(".engage-select-field-type").not(".field-initialized").length) {
			if (!core_form) {
				core_form = jQuery(".engage-forms-options-form");
			}

			// build previews
			if (!core_form.hasClass("builder-loaded")) {
				var fields = jQuery(".engage-select-field-type.field-initialized");
				for (var f = 0; f < fields.length; f++) {
					build_field_preview(jQuery(fields[f]).data("field"));
				}
				core_form.addClass("builder-loaded");
			} else {
				build_field_preview(select.data("field"));
			}
			jQuery(".engage-header-save-button").prop("disabled", false);
			rebuild_field_binding();
			baldrickTriggers();
		}
		if (jQuery(".color-field").length) {
			jQuery(".color-field").wpColorPicker({
				change: function (obj) {
					var trigger = jQuery(this);

					if (trigger.data("ev")) {
						clearTimeout(trigger.data("ev"));
					}
					trigger.data(
						"ev",
						setTimeout(function () {
							trigger.trigger("record");
						}, 200)
					);
					if (trigger.data("target")) {
						jQuery(trigger.data("target")).css(trigger.data("style"), trigger.val());
						jQuery(trigger.data("target")).val(trigger.val());
					}
				},
			});
		}

		if (["html", "section_break"].indexOf(select.val()) >= 0) {
			var $label = parent.find(".field-label");
			if (!$label.val()) {
				$label
					.val(select.val() + "__" + parent.find(".field-id").val())
					.trigger("change");
			}
		}
	}

	function build_field_preview(id) {
		var panel = jQuery("#" + id);
		var select = panel.find(".engage-select-field-type");
		var val = select.val();
		var preview_parent = jQuery('.layout-form-field[data-config="' + id + '"]'),
			preview_target = preview_parent.find(".field_preview"),
			preview = jQuery("#preview-" + val + "_tmpl").html(),
			template = get_compiled_template("preview-" + val),
			config = { id: id },
			data_fields = panel.find(".field-config"),
			objects = [];

		data_fields.each(function (k, v) {
			var field = jQuery(v),
				basename = field
					.prop("name")
					.split("[" + id + "]")[1]
					.substr(1),
				name = basename.substr(0, basename.length - 1).split("]["),
				value = field.is(":checkbox,:radio")
					? field.filter(":checked").val()
					: field.val(),
				lineconf = {};

			for (var i = name.length - 1; i >= 0; i--) {
				if (i === name.length - 1) {
					lineconf[name[i]] = value;
				} else {
					var newobj = lineconf;
					lineconf = {};
					lineconf[name[i]] = newobj;
				}
			}
			jQuery.extend(true, config, lineconf);
		});

		preview_target.html(template(config));
		preview_parent.removeClass("button");

		jQuery(".preview-field-config").prop("disabled", true);
	}

	// switch active group
	function switch_active_group(id) {
		var fields_panel = jQuery(".engage-editor-fields-panel"),
			groups_panel = jQuery(".engage-editor-groups-panel"),
			group_navs = jQuery(".engage-group-nav"),
			group_line = jQuery('[data-group="' + id + '"]'),
			group_name = group_line.find(".engage-config-group-name"),
			group_slug = group_line.find(".engage-config-group-slug"),
			group_desc = group_line.find(".engage-config-group-desc"),
			group_admin = group_line.find(".engage-config-group-admin"),
			group_name_edit = jQuery(".active-group-name"),
			group_slug_edit = jQuery(".active-group-slug"),
			group_desc_edit = jQuery(".active-group-desc"),
			group_admin_edit = jQuery(".active-group-admin"),
			field_lists = jQuery(".engage-editor-fields-list ul"),
			group_repeat = group_line.find(".engage-config-group-repeat"),
			repeat_button = jQuery(".repeat-config-button"),
			group_settings = jQuery(".engage-editor-group-settings"),
			deleter = jQuery(".engage-config-group-remove"),
			group_field_lists;

		// remove any hdden fields
		jQuery(".new-group-input").remove();
		jQuery(".new-field-input").remove();

		// remove current active group
		group_navs.removeClass("active");

		// show fields panel
		fields_panel.show();

		// hide all groups
		field_lists.hide();

		// remove active field
		field_lists.removeClass("active").find("li.active").removeClass("active");
		field_lists.hide();

		// set active group
		group_line.addClass("active");

		// hide delete button or show
		group_field_lists = jQuery(".engage-editor-fields-list ul.active li");

		if (group_field_lists.length) {
			// has fields
			deleter.hide();
		} else {
			deleter.show();
		}

		// hide all field configs
		jQuery(".engage-editor-field-config-wrapper").hide();

		// show groups fields
		group_line.show();

		// set group name edit field
		group_name_edit.val(group_name.val());

		// set group slug edit field
		group_slug_edit.val(group_slug.val());

		// set group slug edit field
		group_desc_edit.val(group_desc.val());

		// set group admin edit field
		if (group_admin.val() === "1") {
			group_admin_edit.prop("checked", true);
		} else {
			group_admin_edit.prop("checked", false);
		}

		// is repeatable
		if (group_repeat.val() === "1") {
			repeat_button.addClass("field-edit-open");
		} else {
			repeat_button.removeClass("field-edit-open");
		}
	}

	// tabs button
	jQuery("body").on("click", ".toggle_processor_event input", function (e) {
		var clicked = jQuery(this),
			parent = clicked.closest(".wrapper-instance-pane"),
			settings = parent.find(".engage-config-processor-setup"),
			notice = parent.find(".engage-config-processor-notice");

		var enabling = clicked.is(":checked");
		if (enabling) {
			clicked.parent().addClass("activated");
			clicked
				.parent()
				.find(".is_active")
				.show()
				.attr("aria-hidden", false)
				.css("visibility", "visible");
			clicked
				.parent()
				.find(".not_active")
				.hide()
				.attr("aria-hidden", true)
				.css("visibility", "hidden");

			jQuery(document).trigger("processor.enabled", {
				processorId:  clicked.parent().data('pid')
			});
		} else {
			clicked.parent().removeClass("activated");
			clicked
				.parent()
				.find(".is_active")
				.hide()
				.attr("aria-hidden", true)
				.css("visibility", "hidden");
			clicked
				.parent()
				.find(".not_active")
				.show()
				.attr("aria-hidden", false)
				.css("visibility", "visible");

			jQuery(document).trigger("processor.disabled", {
				processorId:  clicked.parent().data('pid')
			});
		}

		// Show or hide the settings panel
		if (enabling) {
			settings.slideDown(100);
			notice.slideUp(100);
		} else {
			settings.slideUp(100);
			notice.slideDown(100);
		}
	});

	jQuery("body").on("click", ".toggle_option_tab > a", function (e) {
		e.preventDefault();
		var clicked = jQuery(this),
			panel = jQuery(clicked.attr("href")),
			tabs = clicked.parent().find("a"),
			panels = clicked
				.closest(".engage-editor-config-wrapper")
				.find(".wrapper-instance-pane");

		tabs.removeClass("button-primary");

		panels.hide();
		panel.show();
		clicked.addClass("button-primary");
	});

	// Change Field Type
	jQuery(".engage-editor-body").on(
		"change",
		".engage-select-field-type",
		function (e) {
			// push element to config function
			build_fieldtype_config(this);
		}
	);

	// build group navigation
	jQuery(".engage-editor-body").on("click", ".engage-group-nav a", function (e) {
		// stop link
		e.preventDefault();

		//switch group
		switch_active_group(jQuery(this).attr("href").substr(1));
	});

	// build field navigation
	jQuery(".engage-editor-body").on(
		"click",
		".engage-editor-fields-list a",
		function (e) {
			// stop link
			e.preventDefault();

			var clicked = jQuery(this),
				field_config = jQuery(clicked.attr("href"));

			// remove any hdden fields
			jQuery(".new-group-input").remove();
			jQuery(".new-field-input").remove();

			// remove active field
			jQuery(".engage-editor-fields-list li.active").removeClass("active");

			// mark active
			clicked.parent().addClass("active");

			// hide all field configs
			jQuery(".engage-editor-field-config-wrapper").hide();

			// show field config
			field_config.show();
		}
	);

	// bind show group config panel
	jQuery(".engage-editor-body").on("click", ".group-config-button", function (e) {
		var clicked = jQuery(this),
			group_settings = jQuery(".engage-editor-group-settings"),
			parent = clicked.closest(".engage-editor-fields-panel"),
			deleter = jQuery(".engage-config-group-remove");

		// check if children
		if (parent.find(".engage-field-line").length) {
			// has fields
			deleter.hide();
		} else {
			deleter.show();
		}

		if (clicked.hasClass("field-edit-open")) {
			// show config
			group_settings.slideUp(100);
			clicked.removeClass("field-edit-open");
		} else {
			// hide config
			group_settings.slideDown(100);
			clicked.addClass("field-edit-open");
		}
	});
	jQuery(".engage-editor-body").on("keydown", ".field-config", function (e) {
		if (jQuery(this).is("textarea")) {
			return;
		}
		if (e.which === 13) {
			e.preventDefault();
		}
	});
	// field label bind
	jQuery(".engage-editor-body").on("change", ".field-label", function (e) {
		var field = jQuery(this)
			.closest(".engage-editor-field-config-wrapper")
			.prop("id");
		var field_line = jQuery('[data-field="' + field + '"]'),
			field_title = jQuery(
				"#" +
					field +
					" .engage-editor-field-title, .layout-form-field.field-edit-open .layout_field_name"
			),
			slug = jQuery("#" + field + " .field-slug");

		field_line.find("a").html('<i class="icn-field"></i> ' + this.value);
		field_title.text(this.value);
		if (e.type === "change") {
			slug.trigger("change");
		}
		rebuild_field_binding();
	});

	// rename group
	jQuery(".engage-editor-body").on("change", ".active-group-name", function (e) {
		e.preventDefault();
		var active_group = jQuery(".engage-group-nav.active"),
			group = active_group.data("group"),
			group_name = active_group.find(".engage-config-group-name"),
			group_label = active_group.find("span");

		// check its not blank
		if (e.type === "focusout" && !this.value.length) {
			this.value = "Group " + (parseInt(active_group.index()) + 1);
		}

		group_name.val(this.value);
		group_label.text(this.value);
	});
	// rename group slug
	jQuery(".engage-editor-body").on("change", ".active-group-slug", function (e) {
		e.preventDefault();

		var active_group = jQuery(".engage-group-nav.active"),
			group = active_group.data("group"),
			group_name = active_group.find(".engage-config-group-name").val(),
			group_slug = active_group.find(".engage-config-group-slug"),
			group_label = active_group.find("span"),
			slug_sanitized = this.value
				.split(" ")
				.join("_")
				.split("-")
				.join("_")
				.replace(/[^a-z0-9_]/gi, "")
				.toLowerCase();

		// check its not blank
		if (e.type === "focusout" && !this.value.length) {
			slug_sanitized = group_name
				.split(" ")
				.join("_")
				.split("-")
				.join("_")
				.replace(/[^a-z0-9_]/gi, "")
				.toLowerCase();
		}

		group_slug.val(slug_sanitized);
		this.value = slug_sanitized;
	});
	// rename group description
	jQuery(".engage-editor-body").on("change", ".active-group-desc", function (e) {
		e.preventDefault();

		var active_group = jQuery(".engage-group-nav.active"),
			group = active_group.data("group"),
			group_desc = active_group.find(".engage-config-group-desc");

		group_desc.val(this.value);
	});

	// set group admin
	jQuery(".engage-editor-body").on("change", ".active-group-admin", function (e) {
		e.preventDefault();

		var active_group = jQuery(".engage-group-nav.active"),
			group = active_group.data("group"),
			group_name = active_group.find(".engage-config-group-name").val(),
			group_admin = active_group.find(".engage-config-group-admin"),
			group_label = active_group.find("span"),
			slug_sanitized = this.value
				.split(" ")
				.join("_")
				.split("-")
				.join("_")
				.replace(/[^a-z0-9_]/gi, "")
				.toLowerCase();

		// check its not blank
		if (jQuery(this).prop("checked")) {
			group_admin.val(1);
			active_group.addClass("is-admin");
		} else {
			group_admin.val(0);
			active_group.removeClass("is-admin");
		}
	});

	// set repeatable
	jQuery(".engage-editor-body").on("click", ".repeat-config-button", function (e) {
		e.preventDefault();
		var active_group = jQuery(".engage-group-nav.active"),
			group = active_group.data("group"),
			icon = active_group.find("a .group-type"),
			group_repeat = active_group.find(".engage-config-group-repeat"),
			clicked = jQuery(this);

		if (clicked.hasClass("field-edit-open")) {
			// set static
			group_repeat.val("0");
			icon.removeClass("icn-repeat").addClass("icn-folder");
			clicked.removeClass("field-edit-open");
		} else {
			// set repeat
			group_repeat.val("1");
			icon.addClass("icn-repeat").removeClass("icn-folder");
			clicked.addClass("field-edit-open");
		}
	});

	// bind delete field
	jQuery(".engage-editor-body").on("click", ".delete-field", function () {
		var clicked = jQuery(this),
			field = clicked
				.closest(".engage-editor-field-config-wrapper")
				.prop("id");

		if (!confirm(clicked.data("confirm"))) {
			return;
		}
		// remove config
		jQuery("#" + field).remove();
		// remove options
		jQuery('option[value="' + field + '"]').remove();
		jQuery('[data-bind="' + field + '"]').remove();

		// remove field
		delete current_form_fields[field];

		jQuery('[data-config="' + field + '"]').slideUp(200, function () {
			var line = jQuery(this);

			// remove line
			line.remove();
			rebuild_field_binding();
			jQuery(document).trigger("field.removed", {
				fieldId: field,
			});
		});
	});

	// bind add new group button
	jQuery(".engage-editor-body").on(
		"click",
		".add-new-group,.add-field",
		function () {
			var clicked = jQuery(this);

			// remove any hdden fields
			jQuery(".new-group-input").remove();
			jQuery(".new-field-input").remove();

			if (clicked.hasClass("add-field")) {
				var field_input = jQuery(
					'<input type="text" class="new-field-input block-input">'
				);
				field_input
					.appendTo(jQuery(".engage-editor-fields-list ul.active"))
					.focus();
			} else {
				var group_input = jQuery(
					'<input type="text" class="new-group-input block-input">'
				);
				group_input.appendTo(jQuery(".engage-editor-groups-panel")).focus();
			}
		}
	);

	// dynamic group creation
	jQuery(".engage-editor-body").on("change keypress", ".new-group-input", function (
		e
	) {
		if (e.type === "keypress") {
			if (e.which === 13) {
				e.preventDefault();
			} else {
				return;
			}
		}

		var group_name = this.value,
			input = jQuery(this),
			wrap = jQuery(".engage-editor-groups-panel ul"),
			field_list = jQuery(".engage-editor-fields-list"),
			new_templ,
			new_group;

		if (!group_name.length) {
			// no name- just remove the input
			input.remove();
		} else {
			new_templ = Handlebars.compile(jQuery("#engage_group_line_templ").html());
			new_group = {
				id: group_name
					.split(" ")
					.join("_")
					.split("-")
					.join("_")
					.replace(/[^a-z0-9_]/gi, "")
					.toLowerCase(),
				name: group_name,
			};

			// place new group line
			wrap.append(new_templ(new_group));

			// create field list
			var new_list = jQuery('<ul data-group="' + new_group.id + '">').hide();

			// place list in fields list
			new_list.appendTo(field_list);

			// init sorting

			// remove input
			input.remove();

			// swtich to new group
			switch_active_group(new_group.id);
		}
	});

	// dynamic field creation
	jQuery(".engage-editor-body").on("change keypress", ".new-field-input", function (
		e
	) {
		if (e.type === "keypress") {
			if (e.which === 13) {
				e.preventDefault();
			} else {
				return;
			}
		}

		var new_name = this.value,
			input = jQuery(this),
			wrap = input.parent(),
			field_conf = jQuery(".engage-editor-field-config"),
			new_templ,
			new_conf_templ,
			new_field,
			deleter = jQuery(".engage-config-group-remove");

		if (!new_name.length) {
			// no name- just remove the input
			input.remove();
		} else {
			// hide delete group
			deleter.hide();
			// field line template
			new_templ = Handlebars.compile(jQuery("#engage_field_line_templ").html());
			// field conf template
			new_conf_templ = Handlebars.compile(
				jQuery("#engage_field_config_wrapper_templ").html()
			);

			new_field = {
				id: new_name
					.split(" ")
					.join("_")
					.split("-")
					.join("_")
					.replace(/[^a-z0-9_]/gi, "")
					.toLowerCase(),
				label: new_name,
				slug: new_name
					.split(" ")
					.join("_")
					.split("-")
					.join("_")
					.replace(/[^a-z0-9_]/gi, "")
					.toLowerCase(),
				group: jQuery(".engage-group-nav.active").data("group"),
			};

			var field = jQuery(new_templ(new_field));

			// place new field line
			field.appendTo(wrap);
			// pance new conf template
			field_conf.append(new_conf_templ(new_field));

			// load field
			field.find("a").trigger("click");

			// remove input
			input.remove();
		}
	});

	// bind slug editing to keep clean
	jQuery(".engage-editor-body").on("change", ".field-slug", function (e) {
		if (this.value.length) {
			this.value = this.value
				.split(" ")
				.join("_")
				.split("-")
				.join("_")
				.replace(/[^a-z0-9_]/gi, "")
				.toLowerCase();
		} else {
			if (e.type === "change") {
				this.value = jQuery(this)
					.closest(".engage-editor-field-config-wrapper")
					.find(".field-label")
					.val()
					.split(" ")
					.join("_")
					.split("-")
					.join("_")
					.replace(/[^a-z0-9_]/gi, "")
					.toLowerCase();
			}
		}
	});

	// bind add group button
	jQuery(".engage-editor-body").on("click", ".engage-add-group", function (e) {
		var clicked = jQuery(this),
			group = clicked.data("group"),
			template = jQuery("#" + group + "_panel_tmpl").html();

		clicked.parent().parent().append(template);
	});
	// bind remove group button
	jQuery(".engage-editor-body").on(
		"click",
		".engage-config-group-remove",
		function (e) {
			var group = jQuery(".active-group-slug").val();

			jQuery('[data-group="' + group + '"]').hide(0, function () {
				jQuery(this).remove();
				var navs = jQuery(".engage-group-nav");

				if (navs.length) {
					navs.first().find("a").trigger("click");
				} else {
					jQuery(".engage-editor-fields-panel").hide();
				}
			});
		}
	);

	jQuery("body").on("click", ".set-current-field", function (e) {
		e.preventDefault();

		var clicked = jQuery(this);

		jQuery("#" + clicked.data("field") + "_type")
			.val(clicked.data("type"))
			.trigger("change");

		jQuery("#" + clicked.data("field") + "_lable").focus();

		jQuery("#field_setup_baldrickModalCloser").trigger("click");
	});

	jQuery(".engage-editor-body").on("change record", ".field-config", function (e) {
		if( typeof e.target.name !== "undefined" && typeof e.target.value !== "undefined") {
			jQuery(document).trigger("field.config-change", {
				name: e.target.name,
				value: e.target.value,
			});
		}
		var field = jQuery(this),
			parent = field.closest(".engage-editor-field-config-wrapper");

		if (!current_form_fields) {
			return;
		}
		//
		if (field.prop("id") === parent.prop("id") + "_lable") {
			// update field bind label
			current_form_fields[parent.prop("id")].label = this.value;
		}
		if (field.prop("id") === parent.prop("id") + "_slug") {
			// update field bind slug
			current_form_fields[parent.prop("id")].slug = this.value;
		}
		if (field.prop("id") === parent.prop("id") + "_type") {
			// update field bind type
			current_form_fields[parent.prop("id")].type = this.value;
		}

		if (parent.length) {
			build_field_preview(parent.prop("id"));
		}
	});

	jQuery(".engage-editor-body").on("focus", ".engage-field-bind", function (e) {
		var field = jQuery(this),
			value = this.value;

		if (e.type && e.type === "focusin") {
			field.removeClass("bound_field").addClass("reload-binding");
			rebind_field_bindings();
			this.value = value;
			return;
		}
	});

	// load fist  group
	jQuery(".engage-group-nav").first().find("a").trigger("click");

	// toggle set values
	jQuery(".engage-editor-body").on("change", ".toggle_show_values", function (e) {
		var $clicked = jQuery(this),
			$wrap = $clicked.closest(".engage-config-group-toggle-options"),
			$labelInputs = $wrap.find(".toggle_label_field"),
			$valueInputs = $wrap.find(
				".toggle_value_field, .toggle_calc_value_field"
			),
			$valueLabels = $wrap.find(".option-setting-label-for-value"),
			$labelLabel = $wrap.find(".option-setting-label-for-label"),
			$optionGroupControl = $wrap.find(".option-group-control"),
			inputCss = {
				width: "100%",
				display: "inline",
				float: "left",
			};
		if (!$clicked.prop("checked")) {
			$valueInputs.hide().attr("aria-hidden", true);
			$valueLabels.hide().attr("aria-hidden", true);
			$labelInputs.css("width", 245);
			$labelLabel.css("display", "inline");
		} else {
			$valueInputs.show().css(inputCss).attr("aria-hidden", false);
			$labelInputs.show().css(inputCss).attr("aria-hidden", false);
			$valueLabels.show().css({
				display: "inline-block",
			});
			$labelLabel.css("display", "inline");
		}

		$labelInputs.trigger("toggle.values");
		init_magic_tags();
	});

	// autopopulate
	jQuery(".engage-editor-body").on("change", ".auto-populate-type", function () {
		jQuery(this)
			.closest(".wrapper-instance-pane")
			.find(".auto-populate-options")
			.trigger("change");
	});
	jQuery(".engage-editor-body").on("change", ".auto-populate-options", function () {
		var clicked = jQuery(this),
			wrap = clicked.closest(".wrapper-instance-pane"),
			manual = wrap.find(".engage-config-group-toggle-options"),
			autotype_wrap = wrap.find(".engage-config-group-auto-options"),
			autotype = autotype_wrap.find(".auto-populate-type");

		autotype_wrap.find(".auto-populate-type-panel").hide();

		if (clicked.prop("checked")) {
			manual.hide();
			autotype_wrap.show();
		} else {
			manual.show();
			autotype_wrap.hide();
		}

		autotype_wrap.find(".engage-config-group-auto-" + autotype.val()).show();
	});

	jQuery("body").on("change", ".pin-toggle-roles", function () {
		var clicked = jQuery(this),
			roles = jQuery("#engage-pin-rules");

		if (clicked.val() === "1") {
			roles.show();
		} else {
			roles.hide();
		}
	});

	jQuery("body").on("click", ".magic-tag-init", function (e) {
		var clicked = jQuery(this),
			input = clicked.prev();

		input.focus().trigger("init.magic");
	});
	// show magic tag autocompletes
	jQuery("body").on(
		"keyup  focus select click init.magic",
		".magic-tag-enabled",
		function (e) {
			init_magic_tags();
			var input = jQuery(this),
				wrap = input.parent(),
				fieldtype = wrap
					.closest(".wrapper-instance-pane")
					.find(".engage-select-field-type")
					.val()
					? wrap
							.closest(".wrapper-instance-pane")
							.find(".engage-select-field-type")
							.val()
					: "hidden",
				tags = wrap.find(".magic-tags-autocomplete"),
				list = tags.find("ul"),
				stream = this.value,
				tag = [],
				type_instances = [],
				current_tag = "",
				start = this.selectionStart,
				end = this.selectionEnd;

			if (tags.length && tags.data("focus")) {
				e.preventDefault();
				return;
			}

			//reset typed tag
			input.data("tag", "");
			if (this.selectionEnd > this.selectionStart) {
				current_tag = this.value.substr(
					this.selectionStart,
					this.selectionEnd - this.selectionStart
				);
			} else {
				if (
					(e.type === "select" || e.type === "keyup") &&
					e.which !== 40 &&
					e.which !== 38 &&
					e.which !== 39 &&
					e.which !== 37
				) {
					for (start = this.selectionStart; start > 0; start--) {
						var ch = stream.substr(start - 1, 1);

						if (
							ch === " " ||
							ch === "\n" ||
							((ch === "%" || ch === "}") && this.selectionStart === start)
						) {
							break;
						}
					}
					for (end = this.selectionStart; end < stream.length; end++) {
						var ch = stream.substr(end, 1);

						if (
							ch === " " ||
							ch === "\n" ||
							((ch === "%" || ch === "{") && this.selectionStart === end)
						) {
							break;
						}
					}

					current_tag = stream.substr(start, end - start);
				}
			}

			// start matching
			if (e.type !== "focusout") {
				if (e.type !== "init" && current_tag.length < 3) {
					if (tags.length) {
						tags.remove();
					}
				}
				if (!tags.length) {
					tags = jQuery('<div class="magic-tags-autocomplete"></div>');
					list = jQuery("<ul></ul>");
					list.appendTo(tags);
					tags.insertAfter(input);
					tags.on("mouseenter", function () {
						jQuery(this).data("focus", true);
					});
					tags.on("mouseleave", function () {
						jQuery(this).data("focus", false);

						setTimeout(function () {
							tags.remove();
						}, 200);
						if (!input.is(":focus")) {
							input.trigger("focusout");
						}
					});
				}

				//populate
				list.empty();
				// compatibility
				var tagtypes = "system";
				var is_static = false;
				if (
					fieldtype === "hidden" ||
					fieldtype === "dropdown" ||
					fieldtype === "radio" ||
					fieldtype === "toggle_switch" ||
					fieldtype === "checkbox"
				) {
					is_static = true;
					fieldtype = "text";
					tagtypes = "all";
				} else if (fieldtype === "paragraph" || fieldtype === "html") {
					fieldtype = "text";
				}
				// type set
				if (input.data("type")) {
					fieldtype = input.data("type");
				}
				// search em!
				fieldtype = fieldtype.split(",");
				fieldtype.push("vars");
				for (var ft = 0; ft < fieldtype.length; ft++) {
					for (var tp in system_values) {
						if (
							!system_values[tp] ||
							typeof system_values[tp].tags === "undefined" ||
							typeof system_values[tp].tags[fieldtype[ft]] === "undefined"
						) {
							continue;
						}

						type_instances = [tp];
						if (tp !== "system" && tp !== "variable" && tp !== "field") {
							var type_instance_confs = jQuery(".processor-" + tp),
								wrapper = input.closest(
									".engage-editor-processor-config-wrapper"
								),
								wrapper_id = wrapper.prop("id");
							type_instances = [];
							// processor based - orderd
							for (var c = 0; c < type_instance_confs.length; c++) {
								if (!wrapper.length && is_static === true) {
									// static non processor - can be used
									type_instances.push(type_instance_confs[c].id);
								} else {
									if (wrapper_id === type_instance_confs[c].id) {
										continue;
									}

									// check index order is valid
									if (
										jQuery("li." + type_instance_confs[c].id).index() <
										jQuery("li." + wrapper_id).index()
									) {
										type_instances.push(type_instance_confs[c].id);
									}
								}
							}
						}
						// all instances of tag
						for (
							var instance = 0;
							instance < type_instances.length;
							instance++
						) {
							if (tagtypes === "all" || tagtypes === tp || tp === "variable") {
								var heading = jQuery(
										'<li class="header">' +
											system_values[tp].type +
											(instance > 0 ? " [" + (instance + 1) + "]" : "") +
											"</li>"
									),
									matches = 0;
								heading.appendTo(list);

								for (
									var i = 0;
									i < system_values[tp].tags[fieldtype[ft]].length;
									i++
								) {
									if (input.data("parent")) {
										if (
											"variable:" + input.data("parent") ===
											system_values[tp].tags[fieldtype[ft]][i]
										) {
											continue;
										}
									}

									var this_tag =
										system_values[tp].wrap[0] +
										system_values[tp].tags[fieldtype[ft]][i] +
										system_values[tp].wrap[1];
									if (
										type_instances[instance] !== tp &&
										type_instances.length > 1
									) {
										this_tag =
											system_values[tp].wrap[0] +
											system_values[tp].tags[fieldtype[ft]][i] +
											":" +
											type_instances[instance] +
											system_values[tp].wrap[1];
									}
									if (this_tag.indexOf(current_tag) >= 0 || e.type === "init") {
										matches += 1;
										var view_tag = this_tag.replace(
											current_tag,
											"<strong>" + current_tag + "</strong>"
										);

										var linetag = jQuery(
											'<li class="tag" data-tag="' +
												this_tag +
												'">' +
												view_tag +
												"</li>"
										);

										linetag.on("click", function () {
											var selected = jQuery(this).data("tag");

											input
												.val(
													stream.substr(0, start) +
														selected +
														stream.substr(end)
												)
												.trigger("change")
												.focus();
											input[0].selectionStart =
												start +
												selected.length -
												(selected.indexOf("*") > 0 ? 2 : 0);
											input[0].selectionEnd =
												start +
												selected.length -
												(selected.indexOf("*") > 0 ? 1 : 0);
											end = start = input[0].selectionEnd;
											stream += selected;
											input.trigger("init.magic");
										});

										linetag.appendTo(list);
									}
								}
								if (matches === 0) {
									heading.remove();
								}
							}
						}
					}
				}
			}
			// count results found
			if (!list.children().length) {
				tags.remove();
			}

			// focus out - remove
			if (e.type === "focusout") {
				setTimeout(function () {
					tags.remove();
				}, 200);
			}
		}
	);

	// precompile tempaltes
	pre_compile_templates();
	//compiled_templates

	// build configs on load:
	// allows us to keep changes on reload as not to loose settings on accidental navigation
	jQuery(".engage-select-field-type")
		.not(".field-initialized")
		.each(function (k, v) {
			build_fieldtype_config(v);
		});
}); //

var rebuild_field_binding,
	rebind_field_bindings,
	current_form_fields = {},
	required_errors = {},
	add_new_grid_page,
	add_page_grid,
	init_magic_tags,
	core_form,
	compiled_templates = {};

init_magic_tags = function () {
	//init magic tags
	var magiefields = jQuery(".magic-tag-enabled");

	magiefields.each(function (k, v) {
		var input = jQuery(v);

		if (input.hasClass("magic-tag-init-bound")) {
			var currentwrapper = input.parent().find(".magic-tag-init");
			if (!input.is(":visible")) {
				currentwrapper.hide();
			} else {
				currentwrapper.show();
			}
			return;
		} else {
			var magictag = jQuery('<span class=""></span>'),
				wrapper = jQuery(
					'<span style="position:relative;display:inline-block; width:100%;"></span>'
				);

			if (input.is("input")) {
				magictag.css("borderBottom", "none");
			}

			if (input.hasClass("engage-conditional-value-field")) {
				wrapper.width("auto");
			}

			input.wrap(wrapper);
			magictag.insertAfter(input);
			input.addClass("magic-tag-init-bound");
			if (!input.is(":visible")) {
				magictag.hide();
			} else {
				magictag.show();
			}
		}
	});
};

rebuild_field_binding = function () {
	// check form is loaded first
	if (!core_form) {
		core_form = jQuery(".engage-forms-options-form");
	}

	if (!core_form.hasClass("builder-loaded")) {
		return;
	}

	var fields = jQuery(".engage-editor-field-config-wrapper"); //.not('.bound_field');

	// set object
	system_values.field = {
		tags: {
			text: [],
		},
		type: "Fields",
		wrap: ["%", "%"],
	};

	// each field
	for (var f = 0; f < fields.length; f++) {
		var field_id = fields[f].id,
			label = jQuery("#" + field_id + "_lable").val(),
			slug = jQuery("#" + field_id + "_slug").val(),
			type = jQuery("#" + field_id + "_type").val();

		if (typeof system_values.field.tags[type] === "undefined") {
			system_values.field.tags[type] = [];
		}
		system_values.field.tags[type].push(slug);
		if (type !== "text") {
			system_values.field.tags.text.push(slug);
		}

		current_form_fields[field_id] = {
			label: label,
			slug: slug,
			type: type,
		};

		// bind names
		jQuery("option.bound-field")
			.trigger("change")
			.each(function (k, v) {
				var bind = jQuery(v);
				if (bind.prop("value").indexOf("{") !== 0) {
					bind.text(
						jQuery("#" + bind.prop("value") + "_lable").val() +
							" [" +
							jQuery("#" + bind.prop("value") + "_lable").val() +
							"]"
					);
				} else {
					bind.text(bind.prop("value").replace("{", "").replace("}", ""));
				}
			})
			.removeClass("bound-field");
	}
};

rebind_field_bindings = function () {
	//return;
	var bindings = jQuery(".engage-field-bind").not(".bound_field"),
		type_instances,
		processor_li;

	if (!bindings.length) {
		return;
	}

	bindings.addClass("bound_field");

	for (var v = 0; v < bindings.length; v++) {
		var field = jQuery(bindings[v]),
			current = field.val(),
			default_sel = field.data("default"),
			excludes = field.data("exclude"),
			count = 0,
			wrapper = field.closest(".engage-editor-processor-config-wrapper"),
			wrapper_id = wrapper.prop("id"),
			valid = "";

		if (default_sel && !field.hasClass("reload-binding")) {
			current = default_sel;
		}

		if (field.is("select")) {
			field.empty();

			var optgroup = jQuery('<optgroup label="Fields">');
			for (var fid in current_form_fields) {
				if (field.data("type")) {
					if (
						field
							.data("type")
							.split(",")
							.indexOf(current_form_fields[fid].type) < 0
					) {
						continue;
					}
				}
				// check this field is not the same
				if (field.data("id") !== fid) {
					optgroup.append(
						'<option value="' +
							fid +
							'"' +
							(current === fid ? 'selected="selected"' : "") +
							">" +
							current_form_fields[fid].label +
							" [" +
							current_form_fields[fid].slug +
							"]</option>"
					);
				}

				count += 1;
			}
			optgroup.appendTo(field);
			// system values
			if (count === 0) {
				field.empty();
			}

			for (var type in system_values) {
				type_instances = [];

				if (excludes) {
					if (excludes.split(",").indexOf(type) >= 0) {
						continue;
					}
				}

				if (type !== "system" && type !== "variable") {
					var type_instance_confs = jQuery(".processor-" + type);

					for (var c = 0; c < type_instance_confs.length; c++) {
						if (wrapper_id === type_instance_confs[c].id) {
							continue;
						}

						type_instances.push(type_instance_confs[c].id);
						if (type_instance_confs.length > 1) {
							if (
								(processor_li = jQuery(
									"li." + type_instance_confs[c].id + " .processor-line-number"
								))
							) {
								processor_li.html("[" + (c + 1) + "]");
							}
						}
					}
				} else {
					type_instances.push("__system__");
				}

				var types = [];
				if (field.data("type")) {
					types = field.data("type").split(",");
					types.push("vars");
				} else {
					types = ["text", "vars"];
				}

				for (var t = 0; t < types.length; t++) {
					if (
						system_values[type] !== null &&
						system_values[type].tags &&
						system_values[type].tags[types[t]]
					) {
						for (
							var instance = 0;
							instance < type_instances.length;
							instance++
						) {
							// check index order is valid
							if (
								jQuery("li." + type_instances[instance]).index() >
									jQuery("li." + wrapper_id).index() &&
								type_instances[instance] !== "__system__"
							) {
								if (
									field.closest(".engage-editor-processors-panel-wrap").length
								) {
									valid = ' disabled="disabled"';
								}
							} else {
								valid = "";
							}

							var optgroup = jQuery(
								'<optgroup label="' +
									system_values[type].type +
									(type_instances[instance] !== "__system__"
										? " " +
										  jQuery("li." + type_instances[instance])
												.find(".processor-line-number")
												.html()
										: "") +
									'"' +
									valid +
									">"
							);

							for (
								var i = 0;
								i < system_values[type].tags[types[t]].length;
								i++
							) {
								var bind_value = system_values[type].tags[types[t]][i];
								// update labels on multiple
								if (type_instances[instance] !== "__system__") {
									bind_value = bind_value.replace(
										type,
										type_instances[instance]
									);
								}

								optgroup.append(
									'<option value="{' +
										bind_value +
										'}"' +
										(current === "{" + bind_value + "}"
											? 'selected="selected"'
											: "") +
										valid +
										">" +
										system_values[type].tags[types[t]][i] +
										"</option>"
								);

								count += 1;
							}

							if (optgroup.children().length) {
								optgroup.appendTo(field);
							}
						}
					}
				}
			}
			if (count === 0) {
				field.empty();
				if (field.data("type")) {
					field.append(
						'<option value="">No ' +
							field.data("type").split(",").join(" or ") +
							" in form</option>"
					);
					var no_options = true;
				}
			} else {
				field.prop("disabled", false);
			}

			if (!field.hasClass("required") && typeof no_options === "undefined") {
				field.prepend('<option value=""></option>');
			}
			field.val(current);
		}
	}

	init_magic_tags();
	jQuery(document).trigger("bound.fields");
	jQuery(".engage-header-save-button").prop("disabled", false);
	if (undefined != typeof ef_revisions_ui) {
		ef_revisions_ui();
	}
};

function setup_field_type(obj) {
	return { id: obj.trigger.prop("id") };
}

function check_required_bindings(el) {
	var fields,
		savebutton = jQuery(".engage-header-save-button"),
		field_elements = jQuery(".layout-form-field"),
		nav_elements = jQuery(".engage-processor-nav"),
		all_clear = true;

	if (el) {
		fields = jQuery(el);
	} else {
		fields = jQuery(".engage-config-field .required");
	}

	fields.removeClass("has-error");
	field_elements.removeClass("has-error");
	nav_elements.removeClass("has-error");

	jQuery(".error-tag").remove();
	//reset list
	required_errors = {};

	fields.each(function (k, v) {
		var field = jQuery(v),
			panel = field.closest(".engage-config-editor-panel");

		if (!v.value.length) {
			if (!required_errors[panel.prop("id")]) {
				required_errors[panel.prop("id")] = 0;
			}

			var is_field = field.closest(".engage-editor-field-config-wrapper"),
				is_process = field.closest(".engage-editor-processor-config-wrapper");

			if (is_field.length) {
				jQuery(
					'.layout-form-field[data-config="' + is_field.prop("id") + '"]'
				).addClass("has-error");
			}
			if (is_process.length) {
				jQuery("." + is_process.prop("id")).addClass("has-error");
			}
			required_errors[panel.prop("id")] += 1;
			field.addClass("has-error");

			all_clear = false;
		} else {
			//unique
			if (field.hasClass("field-slug")) {
				var slugs = jQuery(".field-slug").not(field);

				for (var s = 0; s < slugs.length; s++) {
					if (slugs[s].value === v.value) {
						var field = jQuery(slugs[s]);

						if (!required_errors[panel.prop("id")]) {
							required_errors[panel.prop("id")] = 0;
						}
						var is_field = field.closest(
								".engage-editor-field-config-wrapper"
							),
							is_process = field.closest(
								".engage-editor-processor-config-wrapper"
							);

						if (is_field.length) {
							jQuery(
								'.layout-form-field[data-config="' + is_field.prop("id") + '"]'
							).addClass("has-error");
						}
						if (is_process.length) {
							jQuery("." + is_process.prop("id")).addClass("has-error");
						}
						required_errors[panel.prop("id")] += 1;
						field.addClass("has-error");
						all_clear = false;
						break;
					}
				}
			}
			if (field.hasClass("toggle_value_field")) {
				var vals = field
					.closest(".engage-config-group")
					.find(".toggle_value_field")
					.not(field);

				for (var s = 0; s < vals.length; s++) {
					if (vals[s].value === v.value) {
						var field = jQuery(vals[s]);

						if (!required_errors[panel.prop("id")]) {
							required_errors[panel.prop("id")] = 0;
						}
						var is_field = field.closest(
								".engage-editor-field-config-wrapper"
							),
							is_process = field.closest(
								".engage-editor-processor-config-wrapper"
							);

						if (is_field.length) {
							jQuery(
								'.layout-form-field[data-config="' + is_field.prop("id") + '"]'
							).addClass("has-error");
						}
						if (is_process.length) {
							jQuery("." + is_process.prop("id")).addClass("has-error");
						}
						required_errors[panel.prop("id")] += 1;
						field.addClass("has-error");
						all_clear = false;
						break;
					}
				}
			}
		}
	});

	for (var t in required_errors) {
		jQuery(".engage-forms-options-form")
			.find('a[href="#' + t + '"]')
			.append('<span class="error-tag">' + required_errors[t] + "</span>");
	}

	jQuery(".engage-conditional-field-set").trigger("change");

	return all_clear;
}

jQuery( function ($) {
	add_new_grid_page = function (obj) {
		return { page_no: "pg_" + Math.round(Math.random() * 10000000) };
	};

	add_page_grid = function (obj) {
		var btn_count = jQuery(".page-toggle").length + 1,
			button = jQuery(
				'<button type="button" data-name="Page ' +
					btn_count +
					'" data-page="' +
					obj.rawData.page_no +
					'" class="page-toggle button">' +
					obj.params.trigger.data("addtitle") +
					" " +
					btn_count +
					"</button> "
			),
			option_tab = jQuery("#page-toggles");
		button.appendTo(option_tab);
		option_tab.show();
		buildSortables();
		button.trigger("click");
		if (btn_count === 1) {
			option_tab.hide();
		}
		jQuery(document).trigger("add.page");
	};

	// bind pages tab
	jQuery(document).on("remove.page add.page load.page", function (e) {
		var btn_count = jQuery(".page-toggle").length,
			pages_tab = jQuery("#tab_pages");

		if (btn_count <= 1) {
			pages_tab.hide();
		} else {
			pages_tab.show();
		}
	});

	function buildLayoutString() {
		var grid_panels = jQuery(".layout-grid-panel"),
			row_index = 0;

		grid_panels.each(function (pk, pv) {
			var panel = jQuery(pv),
				capt = panel.find(".layout-structure"),
				rows = panel.find(".row"),
				struct = [];

			rows.each(function (k, v) {
				var row = jQuery(v),
					cols = row.children().not(".column-merge"),
					rowcols = [];
				row_index += 1;
				cols.each(function (p, c) {
					span = jQuery(c).attr("class").split("-");
					rowcols.push(span[2]);
					var fields = jQuery(c).find(".field-location");
					if (fields.length) {
						fields.each(function (x, f) {
							var field = jQuery(f);
							field.val(row_index + ":" + (p + 1)).removeAttr("disabled");
						});
					}
					// set name
				});
				struct.push(rowcols.join(":"));
			});
			capt.val(struct.join("|"));
		});
	}

	function insert_new_field(newfield, target, field_default) {
		var name = "fld_" + Math.round(Math.random() * 10000000),
			new_name = name,
			field_conf = jQuery("#field_config_panels"),
			new_conf_templ,
			field_set;

		newfield.prop("id", "").prop("title", "");

		// field conf template
		new_conf_templ = Handlebars.compile(
			jQuery("#engage_field_config_wrapper_templ").html()
		);

		field_set = jQuery.extend(
			{},
			{
				id: new_name,
				label: "",
				slug: "",
			},
			field_default
		);
		// reset slug to blank
		field_set.slug = "";
		var x = new_conf_templ(field_set);
		// pance new conf template
		field_conf.append(new_conf_templ(field_set));

		newfield
			.removeClass("button-small")
			.removeClass("button")
			.removeClass("button-primary")
			.removeClass("ui-draggable")
			.removeClass("layout-new-form-field")
			.addClass("layout-form-field")
			.attr("data-config", name)
			.css({ display: "", opacity: "" });

		newfield.find(".layout_field_name").remove();
		newfield
			.find(".field-location")
			.prop("name", "config[layout_grid][fields][" + name + "]");
		newfield.find(".settings-panel").show();
		newfield.appendTo(target);
		buildSortables();
		newfield.find(".icon-edit").trigger("click");

		jQuery("#" + name + "_lable")
			.focus()
			.select();
		baldrickTriggers();
		jQuery(document).trigger("field.added", {
			field: field_set,
		});
		if (field_default) {
			jQuery("#" + new_name + "_type")
				.data("type", field_set.type)
				.trigger("change");
		} else {
			jQuery("#" + name).trigger("field.drop");
		}
		rebuild_field_binding();
	}

	function buildSortables() {
		// Sortables
		jQuery(".toggle-options").sortable({
			handle: ".dashicons-sort",
		});

		jQuery("#grid-pages-panel").sortable({
			placeholder: "row-drop-helper",
			handle: ".sort-handle",
			items: ".first-row-level",
			axis: "y",
			stop: function () {
				buildLayoutString();
			},
		});
		jQuery(".layout-column").sortable({
			connectWith: ".layout-column",
			appendTo: "#grid-pages-panel",
			helper: "clone",
			items: ".layout-form-field",
			handle: ".drag-handle",
			cursor: "move",
			opacity: 0.7,
			cursorAt: { left: 100, top: 15 },
			start: function (e, ui) {
				ui.helper.css({ width: "200px", height: "35px", paddingTop: "20px" });
			},
			stop: function (e, ui) {
				ui.item.removeAttr("style");
				buildLayoutString();
			},
		});

		// Draggables
		jQuery("h3 .layout-new-form-field").draggable({
			helper: "clone",
			appendTo: "body",
		});
		jQuery(".page-toggle.button").droppable({
			accept: ".layout-form-field",
			over: function (e, ui) {
				jQuery(this).trigger("click");
				jQuery(".layout-column").sortable("refresh");
			},
		});
		// Tools Bar Items
		jQuery(".layout-column").droppable({
			greedy: true,
			activeClass: "ui-state-dropper",
			hoverClass: "ui-state-hoverable",
			accept: ".layout-new-form-field",
			drop: function (event, ui) {
				var newfield = ui.draggable.clone(),
					target = jQuery(this);

				insert_new_field(newfield, target);
			},
		});

		buildLayoutString();
	}
	buildSortables();

	jQuery("#grid-pages-panel").on(
		"click",
		".column-fieldinsert .dashicons-plus-alt",
		function (e) {
			//newfield-tool
			var target = jQuery(this).closest(".column-container"),
				newfield = jQuery("#newfield-tool").clone().css("display", "");

			insert_new_field(newfield, target);
		}
	);

	jQuery("#grid-pages-panel").on("click", ".column-split", function (e) {
		var column = jQuery(this).parent().parent(),
			size = column.attr("class").split("-"),
			newcol = jQuery("<div>").insertAfter(column);

		var left = Math.ceil(size[2] / 2),
			right = Math.floor(size[2] / 2);

		size[2] = left;
		column.attr("class", size.join("-"));
		size[2] = right;
		newcol
			.addClass(size.join("-"))
			.append('<div class="layout-column column-container">');
		jQuery(this).remove();
		buildSortables();

		jQuery(".column-tools").remove();
		jQuery(".column-merge").remove();
	});
	jQuery("#grid-pages-panel").on("click", ".column-remove", function (e) {
		var row = jQuery(this).closest(".row"),
			fields = row.find(".layout-form-field"),
			wrap = row.closest(".layout-grid-panel");

		//find fields
		if (fields.length) {
			if (!confirm(jQuery("#row-remove-fields-message").text())) {
				return;
			}
			fields.each(function (k, v) {
				var field_id = jQuery(v).data("config");
				jQuery("#" + field_id).remove();
				// remove options
				jQuery('option[value="' + field_id + '"]').remove();
				jQuery('[data-bind="' + field_id + '"]').remove();

				// remove field
				delete current_form_fields[field_id];
			});
		}
		//return;

		row.slideUp(200, function () {
			jQuery(this).remove();
			buildLayoutString();
			rebuild_field_binding();
			if (!wrap.find(".row").length) {
				wrap.remove();
				var btn = jQuery("#page-toggles .button-primary"),
					prev = btn.prev(),
					next = btn.next();

				btn.remove();
				if (prev.length) {
					prev.trigger("click");
				} else {
					next.trigger("click");
				}
			}
			jQuery(document).trigger("remove.page");
		});

		jQuery(".column-tools").remove();
		jQuery(".column-merge").remove();
	});

	jQuery(".engage-config-editor-main-panel").on(
		"click",
		".engage-add-row",
		function (e) {
			e.preventDefault();
			var wrap = jQuery(".page-active");
			if (!wrap.length) {
				jQuery(".engage-add-page").trigger("click");
				return;
			}
			var new_row = jQuery(
				'<div style="display:none;" class="first-row-level row"><div class="col-xs-12"><div class="layout-column column-container"></div></div></div>'
			);

			jQuery(".page-active").append(new_row);
			new_row.slideDown(200);
			buildSortables();
			buildLayoutString();
		}
	);

	jQuery("#grid-pages-panel").on("click", ".column-join", function (e) {
		var column = jQuery(this).parent().parent().parent();

		var prev = column.prev(),
			left = prev.attr("class").split("-"),
			right = column.attr("class").split("-");
		left[2] = parseFloat(left[2]) + parseFloat(right[2]);

		column
			.find(".layout-column")
			.contents()
			.appendTo(prev.find(".layout-column"));
		prev.attr("class", left.join("-"));
		column.remove();
		buildLayoutString();
		jQuery(".column-tools").remove();
		jQuery(".column-merge").remove();
	});

	jQuery("#grid-pages-panel").on("mouseenter", ".row", function (e) {
		var setrow = jQuery(this);
		jQuery(".column-tools,.column-merge").remove();
		setrow
			.children()
			.children()
			.first()
			.append(
				'<div class="column-remove column-tools" data-placement="top" title="' +
					EF_ADMIN_TOOLTIPS.delete_row +
					'" ><i class="icon-remove"></i></div>'
			);
		setrow
			.children()
			.children()
			.last()
			.append(
				'<div class="column-sort column-tools" style="text-align:right;"><i class="dashicons dashicons-menu drag-handle sort-handle"></i></div>'
			);

		setrow
			.children()
			.children()
			.not(":first")
			.prepend(
				'<div class="column-merge"><div class="column-join column-tools"><i class="icon-join"></i></div></div>'
			);
		var single = setrow.parent().parent().parent().width() / 12 - 1;
		setrow
			.children()
			.children()
			.each(function (k, v) {
				var column = jQuery(v);
				var width = column.width() / 2 - 5;
				column.prepend(
					'<div class="column-fieldinsert column-tools"><i class="dashicons dashicons-plus-alt" data-toggle="tooltip" data-placement="top" title="' +
						EF_ADMIN_TOOLTIPS.add_field_row +
						'"></i></div>'
				);
				if (!column.parent().hasClass("col-xs-1")) {
					column.prepend(
						'<div class="column-split column-tools" data-placement="top" title="' +
							EF_ADMIN_TOOLTIPS.split_row +
							'"><i class="dashicons dashicons-leftright"></i></div>'
					);
					column.find(".column-split").css("left", width);
				}
			});

		jQuery(".column-merge").draggable({
			axis: "x",
			helper: "clone",
			appendTo: setrow,
			grid: [single, 0],
			drag: function (e, ui) {
				jQuery(this).addClass("dragging");
				jQuery(".column-tools").remove();
				jQuery(".column-split").remove();
				var column = jQuery(this).parent().parent(),
					dragged = ui.helper,
					direction =
						ui.originalPosition.left > dragged.position().left
							? "left"
							: "right",
					step = 0,
					prev = column.prev(),
					single = Math.round(column.parent().width() / 12 - 10),
					distance = Math.abs(
						ui.originalPosition.left - dragged.position().left
					);

				column.parent().addClass("sizing");

				if (distance >= single) {
					var left = prev.attr("class").split("-"),
						right = column.attr("class").split("-");

					left[2] = parseFloat(left[2]);
					right[2] = parseFloat(right[2]);

					if (direction === "left") {
						left[2]--;
						right[2]++;
						if (left[2] > 0 && left[2] < left[2] + right[2]) {
							prev.attr("class", left.join("-"));
							column.attr("class", right.join("-"));
							ui.originalPosition.left = dragged.position().left;
						} else {
							jQuery(this).draggable("option", "disabled", true);
						}
					} else {
						left[2]++;
						right[2]--;
						if (right[2] > 0 && right[2] < right[2] + right[2]) {
							prev.attr("class", left.join("-"));
							column.attr("class", right.join("-"));
							ui.originalPosition.left = dragged.position().left;
						} else {
							jQuery(this).draggable("option", "disabled", true);
						}
					}
					buildLayoutString();
				}
			},
			stop: function () {
				jQuery(this)
					.removeClass("dragging")
					.parent()
					.parent()
					.parent()
					.removeClass("sizing");
			},
		});
	});
	jQuery("#grid-pages-panel").on("mouseleave", ".row", function (e) {
		jQuery(".column-tools").remove();
		jQuery(".column-merge").remove();
	});

	jQuery("#grid-pages-panel").on(
		"click",
		".layout-form-field .icon-remove",
		function () {
			var clicked = jQuery(this),
				panel = clicked.parent(),
				config = jQuery("#" + panel.data("config"));

			panel.slideUp(100, function () {
				jQuery(this).remove();
			});
			config.slideUp(100, function () {
				jQuery(this).remove();
			});
		}
	);
	jQuery(document).on(
		"click",
		".layout-form-field .dashicons-admin-page",
		function () {
			var clicked = jQuery(this),
				wrap = clicked.parent(),
				clone_id = wrap.data("config"),
				clone = jQuery("#" + clone_id).formJSON(),
				target = clicked.closest(".column-container"),
				newfield = wrap.clone().css("display", ""),
				new_params = {};

			if (wrap.hasClass("field-edit-open")) {
				wrap.removeClass("field-edit-open");
				newfield.removeClass("field-edit-open");
				jQuery(".engage-editor-field-config-wrapper").hide();
			}

			if (clone.config.fields[clone_id]) {
				new_params = clone.config.fields[clone_id];
				delete new_params.ID;
			}

			insert_new_field(newfield, target, new_params);
		}
	);
	jQuery(document).on("click", ".layout-form-field .icon-edit", function () {
		var clicked = jQuery(this),
			panel = clicked.parent(),
			type = jQuery("#" + panel.data("config") + "_type").val();

		jQuery(".engage-editor-field-config-wrapper").hide();

		if (panel.hasClass("field-edit-open")) {
			panel.removeClass("field-edit-open");
		} else {
			jQuery(".layout-form-field").removeClass("field-edit-open");
			panel.addClass("field-edit-open");
			jQuery("#" + panel.data("config")).show();
		}

		jQuery(document).trigger("show." + panel.data("config"));
		jQuery(document).trigger("show.fieldedit");

		if (
			type === "radio" ||
			type === "checkbox" ||
			type === "dropdown" ||
			type === "toggle_switch"
		) {
			jQuery("#" + panel.data("config") + "_auto").trigger("change");
		}
	});
	jQuery("body").on(
		"click",
		".layout-modal-edit-closer,.layout-modal-save-action",
		function (e) {
			e.preventDefault();

			var clicked = jQuery(this),
				panel = jQuery(".layout-form-field.edit-open"),
				modal = clicked.closest(".layout-modal-container");
			settings = modal.find(".settings-panel").first();

			jQuery(".edit-open").removeClass("edit-open");
			settings.appendTo(panel.find(".settings-wrapper")).hide();

			modal.hide();
		}
	);

	// clear params
	jQuery(".layout-editor-body").on("change", ".layout-core-pod-query", function () {
		jQuery(this).parent().find(".settings-panel-row").remove();
		jQuery(".edit-open")
			.find(".drag-handle .set-pod")
			.html(" - " + jQuery(this).val());
	});
	jQuery(".layout-editor-body").on("click", ".remove-where", function () {
		jQuery(this).closest(".settings-panel-row").remove();
	});
	// load pod fields
	jQuery(".layout-editor-body").on("click", ".use-pod-container", function () {
		var clicked = jQuery(this),
			podselect = clicked.prev(),
			pod = podselect.val(),
			container = "";

		if (!pod.length) {
			return;
		}

		jQuery(".edit-open")
			.find(".drag-handle .set-pod")
			.html(" - " + podselect.val());

		clicked.parent().parent().find(".spinner").css("display", "inline-block");

		var data = {
			action: "pq_loadpod",
			pod_reference: {
				pod: pod,
			},
		};

		jQuery.post(efAdminAJAX, data, function (res) {
			clicked.parent().find(".spinner").css("display", "none");

			var template = jQuery("#where-line-tmpl").html(),
				fields = "",
				container = clicked.closest(".settings-panel").data("container");

			for (var i in res) {
				fields += '<option value="' + res[i] + '">' + res[i] + "</option>";
			}
			template = template
				.replace(/{{fields}}/g, fields)
				.replace(/{{container_id}}/g, container);

			clicked.parent().append(template);
		});
	});

	// edit row
	jQuery(".engage-editor-header").on("click", ".column-sort .icon-edit", function (
		e
	) {});
	// bind tray stuff
	jQuery(".layout-editor-body").on(
		"tray_loaded",
		".layout-template-tray",
		function () {
			buildSortables();
		}
	);
	// build panel navigation
	jQuery(".engage-editor-header").on(
		"click",
		".engage-editor-header-nav a",
		function (e) {
			e.preventDefault();

			var clicked = jQuery(this);

			// remove active tab
			jQuery(".engage-editor-header-nav li").removeClass("active");

			// hide all tabs
			jQuery(".engage-editor-body").hide();

			// show new tab
			jQuery(clicked.attr("href")).show();

			// set active tab
			clicked.parent().addClass("active");
			rebind_field_bindings();
		}
	);

	jQuery("body").on("change", ".required", function () {
		check_required_bindings(this);
	});

	// prevent error forms from submiting
	jQuery("body").on("submit", ".engage-forms-options-form", function (e) {
		var errors = jQuery(".required.has-error");
		if (errors.length) {
			e.preventDefault();
		}
	});

	//toggle_option_row
	jQuery(".engage-editor-body").on("click", ".add-toggle-option", function (e) {
		var clicked = jQuery(this);

		if (clicked.data("bulk")) {
			jQuery(clicked.data("bulk")).toggle();
			jQuery(clicked.data("bulk")).find("textarea").focus();
			return;
		}

		var wrapper = clicked.closest(".engage-editor-field-config-wrapper"),
			toggle_rows = wrapper.find(".toggle-options"),
			row = jQuery("#field-option-row-tmpl").html(),
			template = Handlebars.compile(row),
			key = "opt" + parseInt((Math.random() + 1) * 0x100000),
			config = {
				_name: "config[fields][" + wrapper.prop("id") + "][config]",
				option: {},
			};

		if (clicked.data("options")) {
			var batchinput = jQuery(clicked.data("options")),
				batch = batchinput.val().split("\n"),
				has_vals = false;
			for (var i = 0; i < batch.length; i++) {
				var label = batch[i],
					val = label,
					parts = val.split("|");
				if (parts.length > 1) {
					val = parts[0];
					label = parts[1];
					has_vals = true;
					var calc = parts[2] || false;
				}
				config.option["opt" + parseInt((Math.random() + i) * 0x100000)] = {
					value: val,
					calc_value: calc || val,
					label: label,
					default: false,
				};
			}
			jQuery(clicked.data("options")).parent().hide();
			batchinput.val("");
			if (true === has_vals) {
				wrapper.find(".toggle_show_values").prop("checked", true);
			} else {
				wrapper.find(".toggle_show_values").prop("checked", false);
			}
			toggle_rows.empty();
		} else {
			// add new option
			config.option[key] = {
				value: "",
				label: "",
				calc_value: "",
				default: false,
			};
		}

		jQuery(".preset_options").val("");
		// place new row
		toggle_rows.append(template(config));
		wrapper.find(".toggle_show_values").trigger("change");

		jQuery(".toggle-options").sortable({
			handle: ".dashicons-sort",
		});
		if (!batch) {
			toggle_rows.find(".toggle_label_field").last().focus();
		}
	});
	// presets
	jQuery(".engage-editor-body").on("change", ".preset_options", function (e) {
		var select = jQuery(this),
			preset = select.val(),
			batch = jQuery(select.data("bulk"));

		if (
			preset_options &&
			preset_options[preset] &&
			preset_options[preset].data
		) {
			if (typeof preset_options[preset].data === "object") {
				if (preset_options[preset].data.length) {
					preset_options[preset].data = preset_options[preset].data.join("\n");
				} else {
				}
			}
			batch.val(preset_options[preset].data);
		}
	});
	// remove an option row
	jQuery(".engage-editor-body").on("click", ".toggle-remove-option", function (e) {
		var triggerfield = jQuery(this)
			.closest(".engage-editor-field-config-wrapper")
			.find(".field-config")
			.first();
		jQuery(this).parent().remove();
		triggerfield.trigger("change");
		jQuery(document).trigger("option.remove");
	});

	jQuery(".engage-editor-body").on("click", ".page-toggle", function (e) {
		var clicked = jQuery(this),
			wrap = clicked.parent(),
			btns = wrap.find(".button");

		btns.removeClass("button-primary");
		jQuery(".layout-grid-panel").hide().removeClass("page-active");
		jQuery("#" + clicked.data("page"))
			.show()
			.addClass("page-active");
		clicked.addClass("button-primary");
		//reindex
		btns.each(function (k, v) {
			jQuery(v).html(wrap.data("title") + " " + (k + 1));
		});
		if (btns.length === 1) {
			wrap.hide();
		}
	});

	jQuery(".engage-editor-body").on(
		"blur toggle.values",
		".toggle_label_field",
		function (e) {
			var $label = jQuery(this),
				$value = jQuery(
					'.toggle_value_field[data-opt="' + $label.data("option") + '"]'
				);

			if ($value.is(":visible")) {
				return;
			}

			$value.val($label.val());
		}
	);

	jQuery(document).on("change focusout", ".toggle_value_field", function () {
		jQuery(document).trigger("show.fieldedit");
	});

	jQuery(document).on("show.fieldedit option.remove", function (e) {
		jQuery(".toggle_value_field.has-error").removeClass("has-error");
		var field = jQuery("#" + jQuery(".layout-form-field.field-edit-open").data("config")),
			options = field.find(".toggle_value_field"),
			notice = field.find(".notice"),
			count = 0;

		for (var i = 0; i < options.length; i++) {
			var option = options[i].value,
				repeats = 0;
			for (var f = 0; f < options.length; f++) {
				if (options[i] === options[f]) {
					continue;
				}

				if (options[i].value === options[f].value) {
					jQuery(options[f]).addClass("has-error");
					repeats++;
				}
			}
			if (repeats > 0) {
				jQuery(options[i]).addClass("has-error");
				count++;
			}
		}

		if (count > 0) {
			notice.slideDown();
			e.preventDefault();
		} else {
			notice.slideUp();
		}
	});

	var is_pulsating = false,
		pulsing_adders;

	focus_initial_field = function (e) {
		var field = jQuery(".layout-grid-panel .icon-edit").first();
		if (field.length) {
			field.trigger("click");
		} else {
			jQuery(".layout-column.column-container").first().trigger("mouseover");
			is_pulsating = setInterval(pulsate_adders, 500);
		}
		jQuery(document).off("load.page", focus_initial_field);
	};
	jQuery(document).on("load.page", focus_initial_field);
	function pulsate_adders() {
		if (is_pulsating) {
			var adders = jQuery(".column-fieldinsert");
			if (adders.length) {
				adders.stop().fadeToggle(700);
				jQuery(".layout-new-form-field").stop().fadeToggle(700);
			} else {
				ef_clear_puler();
			}
		}
	}

	ef_clear_puler = function () {
		if (is_pulsating) {
			clearTimeout(is_pulsating);
			jQuery(document).off(
				"mouseover",
				".layout-new-form-field, .column-fieldinsert",
				ef_clear_puler
			);
		}
		jQuery(".layout-new-form-field, .column-fieldinsert").fadeIn();
	};
	jQuery(document).on(
		"mouseover",
		".layout-new-form-field, .column-fieldinsert",
		ef_clear_puler
	);
	// build fild bindings
	rebuild_field_binding();
	jQuery(document).trigger("load.page");

	var $newProcessorButton = jQuery(".new-processor-button");
	var addProcessorButtonPulser;

	// build processor sortables
	function build_processor_sortables() {
		// set sortable groups
		jQuery(".engage-editor-processors-panel ul").sortable({
			update: function () {
				rebuild_field_binding();
			},
			/**
			 * Pulses processor button, changes to primary color if processor list is empty to make obvious to user
			 *
			 * @since 1.5.0.9
			 */
			create: function () {
				if (0 == jQuery(".engage-editor-processors-panel ul").children().length) {
					$newProcessorButton.addClass("button-primary");
					addProcessorButtonPulser = new EngageFormsButtonPulse(
						$newProcessorButton
					);
					window.setTimeout(function () {
						addProcessorButtonPulser.startPulse();
					}, 3000);
				}
			},
		});
	}

	// set active processor editor
	jQuery("body").on("click", ".engage-processor-nav a", function (e) {
		e.preventDefault();

		var clicked = jQuery(this);

		jQuery(".engage-processor-nav").removeClass("active");
		jQuery(".engage-editor-processor-config-wrapper").hide();
		jQuery(clicked.attr("href")).show();
		clicked.parent().addClass("active");
	});

	jQuery("body").on("click", ".add-new-processor", function (e) {
		if ("object" === typeof addProcessorButtonPulser) {
			$newProcessorButton.removeClass("button-primary");
			addProcessorButtonPulser.stopPulse();
		}

		var clicked = jQuery(this),
			new_conf_templ = Handlebars.compile(jQuery("#processor-wrapper-tmpl").html());
		(wrap = jQuery(".active-processors-list")),
			(process_conf = jQuery(".engage-editor-processor-config")),
			(processid = Math.round(Math.random() * 100000000));

		new_templ = Handlebars.compile(jQuery("#processor-line-tmpl").html());
		new_proc = {
			id: "fp_" + processid,
			type: clicked.data("type"),
		};

		// place new group line
		wrap.append(new_templ(new_proc));

		// place config
		process_conf.append(new_conf_templ(new_proc));

		// reset sortable
		jQuery("#form_processor_baldrickModalCloser").trigger("click");
		jQuery(".engage-processor-nav a").last().trigger("click");
		jQuery("#fp_" + processid + "_type")
			.val(clicked.data("type"))
			.trigger("change");
		build_processor_sortables();

		baldrickTriggers();
		jQuery(document).trigger("processor.added", {
			processor: new_proc,
		});
	});

	// remove processor
	jQuery("body").on("click", ".delete-processor", function (e) {
		var clicked = jQuery(this),
			parent = clicked.closest(".engage-editor-processor-config-wrapper"),
			type = parent.data("type");

		if (!confirm(clicked.data("confirm"))) {
			return;
		}

		jQuery("." + parent.prop("id")).remove();
		parent.remove();

		jQuery(".engage-processor-nav a").first().trigger("click");

		rebuild_field_binding();
	});

	// set title & config of selected processor
	jQuery("body").on("change", ".engage-select-processor-type", function (e) {
		var selected = jQuery(this),
			parent = selected.closest(".engage-editor-processor-config-wrapper"),
			title = selected.find('option[value="' + selected.val() + '"]').text(),
			title_line = parent.find(".engage-editor-processor-title"),
			activeline = jQuery(".engage-processor-nav.active a");

		if (title === "") {
			title = title_line.data("title");
		}

		title_line.html(title);
		activeline
			.html(title + ' <span class="processor-line-number"></span>')
			.parent()
			.addClass("processor_type_" + selected.val());

		// get config
		build_processor_config(this);

		rebuild_field_binding();
	});
	jQuery(document).on("click", "#ef-shortcode-preview", function () {
		jQuery(this).focus().select();
	});
	jQuery(document).on("change", ".ef-email-preview-toggle", function () {
		var clicked = jQuery(this),
			preview_button = jQuery(".engage-header-email-preview-button");
		if (clicked.is(":checked")) {
			preview_button
				.show()
				.attr("aria-hidden", "false")
				.css("visibility", "visible");
		} else {
			preview_button
				.hide()
				.attr("aria-hidden", "true")
				.css("visibility", "hidden");
		}
	});

	// build processor type config
	function build_processor_config(el) {
		var select = jQuery(el),
			templ = jQuery("#" + select.val() + "-tmpl").length
				? jQuery("#" + select.val() + "-tmpl").html()
				: "",
			parent = select.closest(".engage-editor-processor-config-wrapper"),
			target = parent.find(".engage-config-processor-setup"),
			template = Handlebars.compile(templ),
			config = parent.find(".processor_config_string").val(),
			current_type = select.data("type");

		// Be sure to load the processors preset when switching back to the initial processor type.
		if (config.length && current_type === select.val()) {
			config = JSON.parse(config);
		} else {
			// default config
			config = processor_defaults[select.val() + "_efg"];
		}

		// build template
		if (!config) {
			config = {};
		}

		config._id = parent.prop("id");
		config._name = "config[processors][" + parent.prop("id") + "][config]";

		template = jQuery("<div>").html(template(config));

		// send to target
		target.html(template.html());

		// check for init function
		if (typeof window[select.val() + "_init"] === "function") {
			window[select.val() + "_init"](parent.prop("id"), target);
		}

		// check if conditions are allowed
		if (parent.find(".no-conditions").length) {
			// conditions are not supported - remove them
			parent.find(".toggle_option_tab").remove();
		}

		rebuild_field_binding();
		baldrickTriggers();

		// initialise baldrick triggers
		jQuery(".wp-baldrick").baldrick({
			request: efAdminAJAX,
			method: "POST",
			before: function (el) {
				var tr = jQuery(el);

				if (tr.data("addNode") && !tr.data("request")) {
					tr.data("request", "ef_get_default_setting");
				}
			},
		});
	}

	// build configs on load:
	// allows us to keep changes on reload as not to loose settings on accedental navigation
	rebuild_field_binding();
	jQuery(".engage-select-processor-type").each(function (k, v) {
		build_processor_config(v);
	});

	build_processor_sortables();
}); //

// field binding helper
Handlebars.registerHelper("_field", function (args) {
	var config = this,
		required = "",
		is_array = "",
		exclude = "";

	var default_val = this[args.hash.slug]
		? ' data-default="' + this[args.hash.slug] + '"'
		: "";

	if (args.hash.required) {
		required = " required";
	}
	if (args.hash.exclude) {
		exclude = 'data-exclude="' + args.hash.exclude + '"';
	}
	if (args.hash.array) {
		is_array = "[]";
		if (args.hash.array !== "true") {
			default_val = 'value="' + args.hash.array + '"';
		}
	}

	out =
		"<select " +
		(args.hash.type ? 'data-type="' + args.hash.type + '"' : "") +
		default_val +
		" " +
		exclude +
		' name="' +
		this._name +
		"[" +
		args.hash.slug +
		"]" +
		is_array +
		'" id="' +
		this._id +
		"_" +
		args.hash.slug +
		'" class="block-input field-config engage-field-bind' +
		required +
		'">';
	if (this[args.hash.slug]) {
		out +=
			'<option class="bound-field" value="' +
			this[args.hash.slug] +
			'" class="bound-field"></option>';
	} else {
		if (!args.hash.required) {
			out += '<option value=""></option>';
		}
	}
	for (var fid in current_form_fields) {
		var sel = "";

		if (args.hash.type) {
			if (current_form_fields[fid].type !== args.hash.type) {
				continue;
			}
		}

		if (config[args.hash.slug]) {
			if (config[args.hash.slug] === fid) {
				sel = ' selected="selected"';
			}
		}

		out +=
			'<option value="' +
			fid +
			'"' +
			sel +
			">" +
			current_form_fields[fid].label +
			" [" +
			current_form_fields[fid].slug +
			"]</option>";
	}

	out += "</select>";
	if (args.hash.required) {
		out +=
			'<input class="field-config" name="' +
			this._name +
			'[_required_bounds][]" type="hidden" value="' +
			args.hash.slug +
			'">';
	}
	return out;
});

Handlebars.registerHelper("console", function (context, options) {
	console.log(this);
});

var revisions = {};
/**
 * Get revisions from API and update panel UI
 *
 * @since 1.5.3
 */
function ef_revisions_ui() {
	var url = EF_ADMIN.rest.revisions;
	var templateEl = document.getElementById("tmpl--revisions");
	if (null === templateEl) {
		return;
	}

	var $spinner = jQuery("#engage-forms-revisions-spinner");
	$spinner.css({
		visibility: "visible",
		float: "none",
	});
	jQuery
		.get(url, function (r) {
			if (r.hasOwnProperty("message")) {
				document.getElementById("engage-forms-revisions").innerHTML =
					'<p class="notice notice-large notice-info">' + r.message + "</p>";
			} else {
				var data = {
					revisions: r,
				};
				revisions = r;
				var template = templateEl.innerHTML;
				var source = jQuery("#tmpl--revisions").html();
				template = Handlebars.compile(source);
				document.getElementById("engage-forms-revisions").innerHTML = template(
					data
				);
			}

			$spinner.css({
				visibility: "hidden",
				float: "none",
			});

			jQuery("input[type=radio][name=engage-forms-revision]").change(
				function () {
					jQuery("#engage-forms-revision-go")
						.attr("href", jQuery(this).data("edit"))
						.css({
							display: "inline-block",
							visibility: "visible",
						})
						.attr("aria-hidden", false);
				}
			);
		})
		.fail(function () {
			$spinner.css({
				visibility: "hidden",
				float: "none",
			});
		});
}

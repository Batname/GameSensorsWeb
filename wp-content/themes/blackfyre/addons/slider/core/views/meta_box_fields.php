<?php

/*
*  Meta box - fields
*
*  This template file is used when editing a field group and creates the interface for editing fields.
*
*  @type	template
*  @date	26/01/13
*/


// global
global $post, $field_types;


// get fields
$fields = apply_filters('acf/field_group/get_fields', array(), $post->ID);


// add clone
$fields[] = apply_filters('acf/load_field_defaults',  array(
	'key' => 'field_clone',
	'label' => esc_html__("New Field",'blackfyre'),
	'name' => 'new_field',
	'type' => 'text',
));


// get name of all fields for use in field type drop down
$field_types = apply_filters('acf/registered_fields', array());


// helper function
function field_type_exists( $name )
{
	global $field_types;

	foreach( $field_types as $category )
	{
		if( isset( $category[ $name ] ) )
		{
			return $category[ $name ];
		}
	}

	return false;
}


// conditional logic dummy data
$conditional_logic_rule = array(
	'field' => '',
	'operator' => '==',
	'value' => ''
);

$error_field_type = '<b>' . esc_html__('Error', 'blackfyre') . '</b> ' . esc_html__('Field type does not exist', 'blackfyre');

?>

<!-- Hidden Fields -->
<div style="display:none;">
	<input type="hidden" name="acf_nonce" value="<?php echo wp_create_nonce( 'field_group' ); ?>" />
</div>
<!-- / Hidden Fields -->


<!-- Fields Header -->
<div class="fields_header">
	<table class="acf widefat">
		<thead>
			<tr>
				<th class="field_order"><?php esc_html_e('Field Order','blackfyre'); ?></th>
				<th class="field_label"><?php esc_html_e('Field Label','blackfyre'); ?></th>
				<th class="field_name"><?php esc_html_e('Field Name','blackfyre'); ?></th>
				<th class="field_type"><?php esc_html_e('Field Type','blackfyre'); ?></th>
				<th class="field_key"><?php esc_html_e('Field Key','blackfyre'); ?></th>
			</tr>
		</thead>
	</table>
</div>
<!-- / Fields Header -->


<div class="fields">

	<!-- No Fields Message -->
	<div class="no_fields_message" <?php if(count($fields) > 1){ echo 'style="display:none;"'; } ?>>
		<?php esc_html_e("No fields. Click the <strong>+ Add Field</strong> button to create your first field.",'blackfyre'); ?>
	</div>
	<!-- / No Fields Message -->

	<?php foreach($fields as $field):
		$fake_name = $field['key'];
	?>
	<div class="field field_type-<?php echo esc_attr($field['type']); ?> field_key-<?php echo esc_attr($field['key']); ?>" data-type="<?php echo esc_attr($field['type']); ?>" data-id="<?php echo esc_attr($field['key']); ?>">
		<input type="hidden" class="input-field_key" name="fields[<?php echo esc_attr($field['key']); ?>][key]" value="<?php echo esc_attr($field['key']); ?>" />
		<div class="field_meta">
			<table class="acf widefat">
				<tr>
					<td class="field_order"><span class="circle"><?php echo (int)$field['order_no'] + 1; ?></span></td>
					<td class="field_label">
						<strong>
							<a class="acf_edit_field row-title" title="<?php esc_html_e("Edit this Field",'blackfyre'); ?>" href="javascript:;"><?php echo esc_attr($field['label']); ?></a>
						</strong>
						<div class="row_options">
							<span><a class="acf_edit_field" title="<?php esc_html_e("Edit this Field",'blackfyre'); ?>" href="javascript:;"><?php esc_html_e("Edit",'blackfyre'); ?></a> | </span>
							<span><a title="<?php esc_html_e("Read documentation for this field",'blackfyre'); ?>" href="http://www.advancedcustomfields.com/resources/#field-types" target="_blank"><?php esc_html_e("Docs",'blackfyre'); ?></a> | </span>
							<span><a class="acf_duplicate_field" title="<?php esc_html_e("Duplicate this Field",'blackfyre'); ?>" href="javascript:;"><?php esc_html_e("Duplicate",'blackfyre'); ?></a> | </span>
							<span><a class="acf_delete_field" title="<?php esc_html_e("Delete this Field",'blackfyre'); ?>" href="javascript:;"><?php esc_html_e("Delete",'blackfyre'); ?></a></span>
						</div>
					</td>
					<td class="field_name"><?php echo esc_attr($field['name']); ?></td>
					<td class="field_type"><?php $l = field_type_exists( $field['type'] ); if( $l ){ echo esc_attr($l); }else{ echo esc_attr($error_field_type); } ?></td>
					<td class="field_key"><?php echo esc_attr($field['key']); ?></td>
				</tr>
			</table>
		</div>
		<div class="field_form_mask">
			<div class="field_form">

				<table class="acf_input widefat acf_field_form_table">
					<tbody>
						<tr class="field_label">
							<td class="label">
								<label><?php esc_html_e("Field Label",'blackfyre'); ?><span class="required">*</span></label>
								<p class="description"><?php esc_html_e("This is the name which will appear on the EDIT page",'blackfyre'); ?></p>
							</td>
							<td>
								<?php
								do_action('acf/create_field', array(
									'type'	=>	'text',
									'name'	=>	'fields[' .$fake_name . '][label]',
									'value'	=>	$field['label'],
									'class'	=>	'label',
								));
								?>
							</td>
						</tr>
						<tr class="field_name">
							<td class="label">
								<label><?php esc_html_e("Field Name",'blackfyre'); ?><span class="required">*</span></label>
								<p class="description"><?php esc_html_e("Single word, no spaces. Underscores and dashes allowed",'blackfyre'); ?></p>
							</td>
							<td>
								<?php
								do_action('acf/create_field', array(
									'type'	=>	'text',
									'name'	=>	'fields[' .$fake_name . '][name]',
									'value'	=>	$field['name'],
									'class'	=>	'name',
								));
								?>
							</td>
						</tr>
						<tr class="field_type">
							<td class="label">
								<label><?php esc_html_e("Field Type",'blackfyre'); ?><span class="required">*</span></label>
							</td>
							<td>
								<?php
								do_action('acf/create_field', array(
									'type'		=>	'select',
									'name'		=>	'fields[' .$fake_name . '][type]',
									'value'		=>	$field['type'],
									'choices' 	=>	$field_types,
								));
								?>
							</td>
						</tr>
						<tr class="field_instructions">
							<td class="label"><label><?php esc_html_e("Field Instructions",'blackfyre'); ?></label>
							<p class="description"><?php esc_html_e("Instructions for authors. Shown when submitting data",'blackfyre'); ?></p></td>
							<td>
								<?php
								do_action('acf/create_field', array(
									'type'	=>	'textarea',
									'name'	=>	'fields[' .$fake_name . '][instructions]',
									'value'	=>	$field['instructions'],
									'rows'	=> 6
								));
								?>
							</td>
						</tr>
						<tr class="required">
							<td class="label"><label><?php esc_html_e("Required?",'blackfyre'); ?></label></td>
							<td>
								<?php
								do_action('acf/create_field', array(
									'type'	=>	'radio',
									'name'	=>	'fields[' .$fake_name . '][required]',
									'value'	=>	$field['required'],
									'choices'	=>	array(
										1	=>	esc_html__("Yes",'blackfyre'),
										0	=>	esc_html__("No",'blackfyre'),
									),
									'layout'	=>	'horizontal',
								));
								?>
							</td>
						</tr>
						<?php

						$field['name'] = $fake_name;
						do_action('acf/create_field_options', $field );

						?>
						<tr class="conditional-logic" data-field_name="<?php echo esc_attr($field['key']); ?>">
							<td class="label"><label><?php esc_html_e("Conditional Logic",'blackfyre'); ?></label></td>
							<td>
								<?php
								do_action('acf/create_field', array(
									'type'	=>	'radio',
									'name'	=>	'fields['.$field['key'].'][conditional_logic][status]',
									'value'	=>	$field['conditional_logic']['status'],
									'choices'	=>	array(
										1	=>	esc_html__("Yes",'blackfyre'),
										0	=>	esc_html__("No",'blackfyre'),
									),
									'layout'	=>	'horizontal',
								));


								// no rules?
								if( ! $field['conditional_logic']['rules'] )
								{
									$field['conditional_logic']['rules'] = array(
										array() // this will get merged with $conditional_logic_rule
									);
								}

								?>
								<div class="contional-logic-rules-wrapper" <?php if( ! $field['conditional_logic']['status'] ) echo 'style="display:none"'; ?>>
									<table class="conditional-logic-rules widefat acf-rules <?php if( count($field['conditional_logic']['rules']) == 1) echo 'remove-disabled'; ?>">
										<tbody>
										<?php foreach( $field['conditional_logic']['rules'] as $rule_i => $rule ):

											// validate
											$rule = array_merge($conditional_logic_rule, $rule);


											// fix PHP error in 3.5.4.1
											if( strpos($rule['value'],'Undefined index: value in') !== false  )
											{
												$rule['value'] = '';
											}

											?>
											<tr data-i="<?php echo esc_attr($rule_i); ?>">
												<td>
													<input class="conditional-logic-field" type="hidden" name="fields[<?php echo esc_attr($field['key']); ?>][conditional_logic][rules][<?php echo esc_attr($rule_i); ?>][field]" value="<?php echo esc_attr($rule['field']); ?>" />
												</td>
												<td width="25%">
													<?php
													do_action('acf/create_field', array(
														'type'	=>	'select',
														'name'	=>	'fields['.$field['key'].'][conditional_logic][rules][' . $rule_i . '][operator]',
														'value'	=>	$rule['operator'],
														'choices'	=>	array(
															'=='	=>	esc_html__("is equal to",'blackfyre'),
															'!='	=>	esc_html__("is not equal to",'blackfyre'),
														),
													));
													?>
												</td>
												<td><input class="conditional-logic-value" type="hidden" name="fields[<?php echo esc_attr($field['key']); ?>][conditional_logic][rules][<?php echo esc_attr($rule_i); ?>][value]" value="<?php echo esc_attr($rule['value']); ?>" /></td>
												<td class="buttons">
													<ul class="hl clearfix">
														<li><a class="acf-button-remove" href="javascript:;"></a></li>
														<li><a class="acf-button-add" href="javascript:;"></a></li>
													</ul>
												</td>
											</tr>
										<?php endforeach; ?>
										</tbody>
									</table>

									<ul class="hl clearfix">
										<li style="padding:4px 4px 0 0;"><?php esc_html_e("Show this field when",'blackfyre'); ?></li>
										<li><?php do_action('acf/create_field', array(
												'type'	=>	'select',
												'name'	=>	'fields['.$field['key'].'][conditional_logic][allorany]',
												'value'	=>	$field['conditional_logic']['allorany'],
												'choices' => array(
													'all'	=>	esc_html__("all",'blackfyre'),
													'any'	=>	esc_html__("any",'blackfyre'),
												),
										)); ?></li>
										<li style="padding:4px 0 0 4px;"><?php esc_html_e("these rules are met",'blackfyre'); ?></li>
									</ul>

								</div>



							</td>
						</tr>
						<tr class="field_save">
							<td class="label"></td>
							<td>
								<ul class="hl clearfix">
									<li>
										<a class="acf_edit_field acf-button grey" title="<?php esc_html_e("Close Field",'blackfyre'); ?>" href="javascript:;"><?php esc_html_e("Close Field",'blackfyre'); ?></a>
									</li>
								</ul>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<div class="table_footer">
	<div class="order_message"><?php esc_html_e('Drag and drop to reorder','blackfyre'); ?></div>
	<a href="javascript:;" id="add_field" class="acf-button"><?php esc_html_e('+ Add Field','blackfyre'); ?></a>
</div>
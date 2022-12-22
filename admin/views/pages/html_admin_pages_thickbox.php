<?php
// Leaven empty tag to let automation add the path disclosure line
?>
<div class="parent_div">
	<p <?php /** @var wc4bp_admin $this */ echo wp_kses_post( $this->disable_class_tag( 'p', wc4bp_base::$starter_plan_id ) ); ?> style="margin: 0 0 20px 0;">
		<input <?php echo wp_kses_post( $this->disable_input_tag( 'button', wc4bp_base::$starter_plan_id ) ); ?> alt="#TB_inline?height=300&amp;width=400&amp;inlineId=add_page" title="<?php esc_html_e( 'Add an existing page to your BuddyPress member profiles', 'wc4bp' ); ?>" class="button button-secondary cptfbp_thickbox cptfbp_thickbox_add " type="button" value="<?php esc_html_e( 'Add a page to your BuddyPress Member Profiles', 'wc4bp' ); ?>"/>

	</p>
</div>

<div id="LoadingImage" class="child_div" style="display: none">
	<img class="lds-spinner" src="/wp-admin/images/wpspin_light.gif" />
</div>

<div id="add_page" style="display:none"></div>





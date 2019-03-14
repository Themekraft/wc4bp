<?php if ( ! empty( $message ) ): ?>
	<div class="notice review-notice active" id="wc4bp-notice" style="">
	<div class="review-notice-logo"><span class="dashicons dashicons-chart-line"></span></div>
	<div class="review-notice-message">
		<strong>WC4BP -> WooCommerce BuddyPress Integration</strong> <br>
		<p><?php echo $message ?></p>
		~ <a target="_blank" href="https://www.themekraft.com/">ThemeKraft Team</a>
	</div>
	<?php if ( ! empty( $links ) && is_array( $links ) ): ?>
		<div class="review-notice-cta">
			<?php foreach ( $links as $link_key => $link_data ): ?>
				<a class="review-notice-dismiss" href="<?php echo esc_attr( $link_data['target'] ) ?>" data-action="<?php echo esc_attr( $link_key ) ?>">
					<?php echo $link_data['name'] ?>
				</a>
			<?php endforeach; ?>
		</div>
		</div>
	<?php endif; ?>
<?php endif; ?>

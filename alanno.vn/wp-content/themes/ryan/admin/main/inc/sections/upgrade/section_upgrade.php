<?php
/**
 * Section - Upgrade (Top Level).
 *
 * @package ThinkUpThemes
 */

if( class_exists( 'WP_Customize_Control' ) ) {
	class thinkup_customizer_customswitch_upgrade extends WP_Customize_Section {

		// The type of customize section being rendered.
		public $type = 'upgrade-top';

		// Custom button text to output.
		public $upgrade_text = '';

		// Custom pro button URL.
		public $upgrade_url = '';

		// Add custom parameters to pass to the JS via JSON.
		public function json() {
			$json = parent::json();

			$json['upgrade_text'] = esc_html( $this->upgrade_text );
			$json['upgrade_url']  = esc_url( $this->upgrade_url );

			return $json;
		}

		// Outputs the Underscore.js template.
		protected function render_template() { ?>

			<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">

				<h3 class="accordion-section-title">
					{{ data.title }}

					<# if ( data.upgrade_text && data.upgrade_url ) { #>
						<a href="{{ data.upgrade_url }}" class="button button-secondary alignright" target="_blank">{{ data.upgrade_text }}</a>
					<# } #>
				</h3>
			</li>
		<?php }
	}
}
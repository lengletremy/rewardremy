<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Class to manage badges for customer levels
 *
 * @class   YITH_WC_Points_Rewards_Level_Badge
 * @since   2.2.0
 * @author  YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Points and Rewards
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WC_Points_Rewards_Cpt_Object', false ) ) {
	include_once YITH_YWPAR_INC . '/objects/abstract-yith-wc-points-rewards-cpt-object.php';
}

if ( ! class_exists( 'YITH_WC_Points_Rewards_Level_Badge' ) ) {

	/**
	 * Class YITH_WC_Points_Rewards_Level_Badge
	 */
	class YITH_WC_Points_Rewards_Level_Badge extends YITH_WC_Points_Rewards_Cpt_Object {

		/**
		 * Array of data
		 *
		 * @var array
		 */
		protected $data = array(
			'name'              => '',
			'status'            => 'on',
                        'points_to_collect' => array(
                                'from' => '',
                                'to'   => '',
                        ),
                        'badge_enabled'     => 'no',
                        'image'             => '',
                        'level_color'       => '#000000',
                        'reward_type'       => 'none',
                        'reward_discount'   => '',
                        'reward_product'    => 0,
		);

		/**
		 * Post type name
		 *
		 * @var string
		 */
		protected $post_type = 'ywpar-level-badge';

		/**
		 * Object type
		 *
		 * @var string
		 */
		protected $object_type = 'level_badge';


		/**
		 * Return the status of this level
		 *
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @return string
		 */
		public function get_status( $context = 'view' ) {
			return $this->get_prop( 'status', $context );
		}

		/**
		 * Return the points to collect for this level
		 *
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @return array
		 */
		public function get_points_to_collect( $context = 'view' ) {
			return $this->get_prop( 'points_to_collect', $context );
		}

		/**
		 * Return the image of the badge for this level
		 *
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @return string
		 */
		public function get_image( $context = 'view' ) {
			return $this->get_prop( 'image', $context );
		}

		/**
		 * Return the name of the level
		 *
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @return string
		 */
		public function get_name( $context = 'view' ) {
			return $this->get_prop( 'name', $context );
		}
		/**
		 * Return the level color of the level
		 *
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @return string
		 */
                public function get_level_color( $context = 'view' ) {
                        return $this->get_prop( 'level_color', $context );
                }

                /**
                 * Return the reward type configured for the level.
                 *
                 * @param string $context What the value is for. Valid values are view and edit.
                 *
                 * @return string
                 */
                public function get_reward_type( $context = 'view' ) {
                        return $this->get_prop( 'reward_type', $context );
                }

                /**
                 * Return the discount configured for the level reward.
                 *
                 * @param string $context What the value is for. Valid values are view and edit.
                 *
                 * @return float
                 */
                public function get_reward_discount( $context = 'view' ) {
                        $discount = $this->get_prop( 'reward_discount', $context );

                        return '' === $discount ? 0 : (float) $discount;
                }

                /**
                 * Return the product used for the gift reward.
                 *
                 * @param string $context What the value is for. Valid values are view and edit.
                 *
                 * @return int
                 */
                public function get_reward_product( $context = 'view' ) {
                        return (int) $this->get_prop( 'reward_product', $context );
                }

		/**
		 * Set the status
		 *
		 * @param string $value The value to set.
		 */
                public function set_status( $value ) {
                        $this->set_prop( 'status', $value );
                }

                /**
                 * Set reward type.
                 *
                 * @param string $value Value to set.
                 */
                public function set_reward_type( $value ) {
                        $this->set_prop( 'reward_type', $value );
                }

                /**
                 * Set reward discount.
                 *
                 * @param float|string $value Value to set.
                 */
                public function set_reward_discount( $value ) {
                        $this->set_prop( 'reward_discount', '' === $value ? '' : (float) $value );
                }

                /**
                 * Set reward product.
                 *
                 * @param int $value Product id.
                 */
                public function set_reward_product( $value ) {
                        $this->set_prop( 'reward_product', absint( $value ) );
                }

		/**
		 * Return the html of badge
		 */
		public function get_badge_html() {
			ob_start();
			if ( 'on' === $this->get_status() ) :
				$img   = $this->get_image();
				$color = $this->get_level_color();
				?>
				<div class="ywpar_level level">
					<?php if ( ! empty( $img ) ) : ?>
						<img src="<?php echo esc_url( $img ); ?>"
							alt="<?php echo esc_attr( $this->get_name() ); ?>"/>
					<?php endif; ?>
					<span style="color:<?php echo esc_attr( $color ); ?>"><?php echo esc_html( $this->get_name() ); ?></span>
				</div>
				<?php
			endif;
			return ob_get_clean();
		}

		/**
		 * Check if the product is valid
		 *
		 * @param int $product_id Product id.
		 */
		public function is_valid_for_product( $product_id ) {
			return true;
		}
	}
}

if ( ! function_exists( 'ywpar_get_level_badge' ) ) {
	/**
	 * Return the levels badge object
	 *
	 * @param mixed $level_badge Level Badge.
	 * @return YITH_WC_Points_Rewards_Level_Badge
	 */
	function ywpar_get_level_badge( $level_badge ) {
		if ( function_exists( 'wpml_object_id_filter' ) ) {
			global $sitepress;

			if ( ! is_null( $sitepress ) && is_callable( array( $sitepress, 'get_current_language' ) ) ) {
				$level_badge = wpml_object_id_filter( $level_badge, 'post', true, $sitepress->get_current_language() );
			}
		}

		return new YITH_WC_Points_Rewards_Level_Badge( $level_badge );
	}
}

<?php

if ( class_exists( 'WPCleverWoonp' ) ) {
	return;
}

class WoonpCore {
	public function __construct() {
		add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_to_cart_item_data' ], PHP_INT_MAX );
		add_filter( 'woocommerce_get_cart_contents', [ $this, 'get_cart_contents' ], PHP_INT_MAX, 1 );
		add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'loop_add_to_cart_link' ], PHP_INT_MAX, 2 );
		add_filter( 'woocommerce_get_price_html', [ $this, 'hide_original_price' ], PHP_INT_MAX, 2 );
		add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'add_input_field_to_frontend' ], PHP_INT_MAX );
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), PHP_INT_MAX, 2 );
	}

	public function add_to_cart_item_data( $cart_item_data ) {
		if ( isset( $_REQUEST['wpc_name_your_price'] ) ) {
			$cart_item_data['wpc_name_your_price'] = self::sanitize_price( $_REQUEST['wpc_name_your_price'] );
			unset( $_REQUEST['wpc_name_your_price'] );
		}

		return $cart_item_data;
	}

	public function get_cart_contents( $cart_contents ) {
		foreach ( $cart_contents as $cart_item ) {
			if ( ! isset( $cart_item['wpc_name_your_price'] ) ) {
				continue;
			}

			$final_value = $cart_item['wpc_name_your_price'];
			$cart_item['data']->set_price( $final_value );
		}

		return $cart_contents;
	}

	public function hide_original_price( $price, $product ) {
		if ( is_admin() ) {
			return $price;
		}

		$product_id    = $product->get_id();
		$get_post_meta = get_post_meta( $product_id, '_woonp_status', true );

		if (
			( get_option( '_woonp_global_status', 'enable' ) === 'enable' && $get_post_meta !== 'disable' ) ||
			( get_option( '_woonp_global_status', 'enable' ) === 'disable' && $get_post_meta === 'overwrite' )
		) {
			$suggested_price = apply_filters( 'woonp_suggested_price', get_option( '_woonp_suggested_price', esc_html__( 'Suggested Price: %s', 'wpc-name-your-price' ) ), $product_id );

			return sprintf( $suggested_price, $price );
		}

		return $price;
	}

	function loop_add_to_cart_link( $link, $product ) {
		$product_id    = $product->get_id();
		$get_post_meta = get_post_meta( $product_id, '_woonp_status', true );

		if ( ( get_option( '_woonp_atc_button', 'show' ) === 'hide' ) &&
		     ( ( get_option( '_woonp_global_status', 'enable' ) === 'enable' && $get_post_meta !== 'disable' ) ||
		       ( get_option( '_woonp_global_status', 'enable' ) === 'disable' && $get_post_meta === 'overwrite' ) )
		) {
			return '';
		}

		return $link;
	}

	public static function is_woonp_product() {
		global $product;

		if ( $product->is_type( 'variation' ) && $product->get_parent_id() ) {
			$product_id = $product->get_parent_id();
		} else {
			$product_id = $product->get_id();
		}

		$woonp_status = get_post_meta( $product_id, '_woonp_status', true ) ?: 'default';

		return ( $woonp_status !== 'disable' );
	}

	public static function add_input_field_to_frontend() {
		global $product;

		if ( self::is_woonp_product() ) {
			$product_id   = $product->get_id();
			$woonp_status = get_post_meta( $product_id, '_woonp_status', true ) ?: 'default';

			if ( $woonp_status === 'overwrite' ) {
				$woonp_global_status = 'enable';
				$woonp_type          = get_post_meta( $product_id, '_woonp_type', true );
				$woonp_min           = get_post_meta( $product_id, '_woonp_min', true );
				$woonp_max           = get_post_meta( $product_id, '_woonp_max', true );
				$woonp_step          = get_post_meta( $product_id, '_woonp_step', true );
				$woonp_values        = get_post_meta( $product_id, '_woonp_values', true );
			} elseif ( $woonp_status === 'default' ) {
				$woonp_global_status = get_option( '_woonp_global_status', 'enable' );
				$woonp_type          = get_option( '_woonp_type', 'default' );
				$woonp_min           = get_option( '_woonp_min' );
				$woonp_max           = get_option( '_woonp_max' );
				$woonp_step          = get_option( '_woonp_step' );
				$woonp_values        = get_option( '_woonp_values' );
			}

			if ( $woonp_global_status === 'disable' ) {
				return;
			}

			switch ( get_option( '_woonp_value', 'price' ) ) {
				case 'price':
					$value = self::sanitize_price( $product->get_price() );
					break;
				case 'min':
					$value = self::sanitize_price( $woonp_min );
					break;
				case 'max':
					$value = self::sanitize_price( $woonp_max );
					break;
				default:
					$value = '';
			}

			if ( is_product() && isset( $_REQUEST['wpc_name_your_price'] ) ) {
				$value = self::sanitize_price( $_REQUEST['wpc_name_your_price'] );
			}

			$input_id    = 'woonp_' . $product_id;
			$input_label = apply_filters( 'woonp_input_label', get_option( '_woonp_label', esc_html__( 'Name Your Price (%s) ', 'wpc-name-your-price' ) ), $product_id );
			$label       = sprintf( $input_label, get_woocommerce_currency_symbol() );
			?>
            <div
                    class="<?php echo esc_attr( 'woonp woonp-' . $woonp_status . ' woonp-type-' . $woonp_type ); ?>"
                    data-min="<?php echo esc_attr( $woonp_min ); ?>" data-max="<?php echo esc_attr( $woonp_max ); ?>"
                    data-step="<?php echo esc_attr( $woonp_step ); ?>">
                <label for="<?php echo esc_attr( $input_id ); ?>">
					<?php echo esc_attr( $label ); ?>
                </label>
				<?php
				if ( $woonp_type === 'select' ) {
					// select
					$woonp_values = WPCleverWoonp::woonp_values( $woonp_values );
					?>
                    <select id="<?php echo esc_attr( $input_id ); ?>"
                            class="woonp-select"
                            name="wpc_name_your_price"
                            title="<?php echo esc_attr_x( 'Name your price', 'Product open-price input tooltip',
						        'wpc-name-your-price' ); ?>">
						<?php foreach ( $woonp_values as $woonp_value ) {
							echo '<option value="' . esc_attr( $woonp_value['value'] ) . '" ' . ( $value == $woonp_value['value'] ? 'selected' : '' ) . '>' . $woonp_value['name'] . '</option>';
						} ?>
                    </select>
					<?php
				} else {
					// default
					?>
                    <input
                            type="number"
                            id="<?php echo esc_attr( $input_id ); ?>"
                            class="woonp-input"
                            step="<?php echo esc_attr( $woonp_step ); ?>"
                            min="<?php echo esc_attr( $woonp_min ); ?>"
                            max="<?php echo esc_attr( 0 < $woonp_max ? $woonp_max : '' ); ?>"
                            name="wpc_name_your_price"
                            value="<?php echo esc_attr( $value ); ?>"
                            title="<?php echo esc_attr_x( 'Name Your Price', 'Product price input tooltip', 'wpc-name-your-price' ); ?>"
                            size="4"/>
					<?php
				} ?>
            </div>
			<?php
		}
	}

	public static function add_to_cart_validation( $passed, $product_id ) {
		if ( isset( $_REQUEST['wpc_name_your_price'] ) ) {
			$woonp_price = (float) $_REQUEST['wpc_name_your_price'];

			if ( $woonp_price < 0 ) {
				wc_add_notice( esc_html__( 'You can\'t fill the negative price.', 'wpc-name-your-price' ), 'error' );

				return false;
			} else {
				$woonp_status = get_post_meta( $product_id, '_woonp_status', true ) ?: 'default';
				$woonp_step   = 1;

				if ( $woonp_status === 'overwrite' ) {
					$woonp_min  = (float) get_post_meta( $product_id, '_woonp_min', true );
					$woonp_max  = (float) get_post_meta( $product_id, '_woonp_max', true );
					$woonp_step = (float) ( get_post_meta( $product_id, '_woonp_step', true ) ?: 1 );
				} elseif ( $woonp_status === 'default' ) {
					$woonp_status = get_option( '_woonp_global_status', 'enable' );
					$woonp_min    = (float) get_option( '_woonp_min' );
					$woonp_max    = (float) get_option( '_woonp_max' );
					$woonp_step   = (float) ( get_option( '_woonp_step' ) ?: 1 );
				}

				if ( $woonp_step <= 0 ) {
					$woonp_step = 1;
				}

				if ( $woonp_status !== 'disable' ) {
					$woonp_pow = pow( 10, strlen( (string) $woonp_step ) );
					$woonp_mod = ( ( $woonp_price * $woonp_pow ) - ( $woonp_min * $woonp_pow ) ) / ( $woonp_step * $woonp_pow );

					if ( ( $woonp_min && ( $woonp_price < $woonp_min ) ) || ( $woonp_max && ( $woonp_price > $woonp_max ) ) || ( $woonp_mod != intval( $woonp_mod ) ) ) {
						wc_add_notice( esc_html__( 'Invalid price. Please try again!', 'wpc-name-your-price' ), 'error' );

						return false;
					}
				}
			}
		}

		return $passed;
	}

	public static function sanitize_price( $price ) {
		return filter_var( sanitize_text_field( $price ), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
	}

	public static function fmod_round( $x, $y ) {
		$i = round( $x / $y );

		return $x - $i * $y;
	}
}

new WoonpCore();

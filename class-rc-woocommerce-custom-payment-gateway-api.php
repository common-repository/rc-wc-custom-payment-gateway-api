<?php 

class RC_WooCommerce_Custom_Payment_Gateway_API extends WC_Payment_Gateway{

    private $order_status;

	public function __construct(){
		$this->id 					= 'custom_payment';
		$this->method_title 		= __('Custom Payment','rc-woocommerce-custom-payment-gateway-api');
		$this->title 				= __('Custom Payment','rc-woocommerce-custom-payment-gateway-api');
		$this->method_description 	= __('Allow custom payment gatway for woocommerce and after compelete payment call restful api.','rc-woocommerce-custom-payment-gateway-api');
		$this->has_fields 			= true;
		$this->init_form_fields();
		$this->init_settings();
		$this->enabled 				= $this->get_option('enabled');
		$this->title 				= $this->get_option('title');
		$this->description 			= $this->get_option('description');
		$this->hide_text_box 		= $this->get_option('hide_text_box');
		$this->text_box_required 	= $this->get_option('text_box_required');
		$this->order_status 		= $this->get_option('order_status');
		//API setting
		$this->rch_api_url 			= $this->get_option('rch_api_url');
		//API keys
		$this->rch_api_key1 		= $this->get_option('rch_api_key1');
		$this->rch_api_key2 		= $this->get_option('rch_api_key2');
		$this->rch_api_key3 		= $this->get_option('rch_api_key3');
		//Key Values
		$this->rch_api_value1 		= $this->get_option('rch_api_value1');
		$this->rch_api_value2 		= $this->get_option('rch_api_value2');
		$this->rch_api_value3 		= $this->get_option('rch_api_value3');
		//Add action
		add_action('woocommerce_update_options_payment_gateways_'.$this->id, array($this, 'process_admin_options'));
	}

	

	public function init_form_fields(){
				$this->form_fields = array(
					'enabled' => array(
					'title' 		=> __( 'Enable/Disable', 'rc-woocommerce-custom-payment-gateway-api' ),
					'type' 			=> 'checkbox',
					'label' 		=> __( 'Enable Custom Payment', 'rc-woocommerce-custom-payment-gateway-api' ),
					'default' 		=> 'yes'
					),

		            'title' => array(
						'title' 		=> __( 'Method Title', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 			=> 'text',
						'description' 	=> __( 'This controls the title', 'rc-woocommerce-custom-payment-gateway-api' ),
						'default'		=> __( 'Custom Payment', 'rc-woocommerce-custom-payment-gateway-api' ),
						'desc_tip'		=> true,
					),
					'description' => array(
						'title' 	=> __( 'Customer Message', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 		=> 'textarea',
						'css' 		=> 'width:500px;',
						'default' 	=> 'None of the custom payment options are suitable for you? please drop us a note about your favourable payment option and we will contact you as soon as possible.',
						'description' 	=> __( 'The message which you want it to appear to the customer in the checkout page.', 'rc-woocommerce-custom-payment-gateway-api' ),
					),
					'text_box_required' => array(
						'title' 		=> __( 'Make the text field required', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 			=> 'checkbox',
						'label' 		=> __( 'Make the text field required', 'rc-woocommerce-custom-payment-gateway-api' ),
						'default' 		=> 'no'
					),
					'hide_text_box' => array(
						'title' 		=> __( 'Hide The Payment Field', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 			=> 'checkbox',
						'label' 		=> __( 'Hide', 'rc-woocommerce-custom-payment-gateway-api' ),
						'default' 		=> 'no',
						'description' 	=> __( 'If you do not need to show the text box for customers at all, enable this option.', 'rc-woocommerce-custom-payment-gateway-api' ),
					),
					'order_status' => array(
						'title' => __( 'Order Status After The Checkout', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 			=> 'select',
						'options' 		=> wc_get_order_statuses(),
						'default' 		=> 'wc-on-hold',
						'description' 	=> __( 'The default order status if this gateway used in payment.', 'rc-woocommerce-custom-payment-gateway-api' ),
					),

					'rch_api_url' => array(
						'title' 		=> __( 'API URL', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 			=> 'text',
						'description' 	=> __( 'After complete payment call api.', 'rc-woocommerce-custom-payment-gateway-api' ),
						'default'		=> __( 'http://dummy.restapiexample.com/api/v1/create', 'rc-woocommerce-custom-payment-gateway-api' ),
						'desc_tip'		=> true,
					),	
					'rch_api_key1' => array(
						'title' 		=> __( 'POST Data Key 1', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 			=> 'text',
						'description' 	=> __( 'After complete payment post data for API.', 'rc-woocommerce-custom-payment-gateway-api' ),
						'default'		=> __( 'name', 'rc-woocommerce-custom-payment-gateway-api' ),
						'desc_tip'		=> true,
					),	
					'rch_api_value1' => array(
						'title' 		=> __( 'POST Data Value for key 1', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 			=> 'text',
						'description' 	=> __( 'After complete payment post data for API.', 'rc-woocommerce-custom-payment-gateway-api' ),
						'default'		=> __( 'Ram', 'rc-woocommerce-custom-payment-gateway-api' ),
						'desc_tip'		=> true,
					),	
					'rch_api_key2' => array(
						'title' 		=> __( 'POST Data Key 2', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 			=> 'text',
						'description' 	=> __( 'After complete payment post data for API.', 'rc-woocommerce-custom-payment-gateway-api' ),
						'default'		=> __( 'salary', 'rc-woocommerce-custom-payment-gateway-api' ),
						'desc_tip'		=> true,
					),	
					'rch_api_value2' => array(
						'title' 		=> __( 'POST Data Value for key 2', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 			=> 'text',
						'description' 	=> __( 'After complete payment post data for API.', 'rc-woocommerce-custom-payment-gateway-api' ),
						'default'		=> __( '1000', 'rc-woocommerce-custom-payment-gateway-api' ),
						'desc_tip'		=> true,
					),	
					'rch_api_key3' => array(
						'title' 		=> __( 'POST Data Key 3', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 			=> 'text',
						'description' 	=> __( 'After complete payment post data for API.', 'rc-woocommerce-custom-payment-gateway-api' ),
						'default'		=> __( 'age', 'rc-woocommerce-custom-payment-gateway-api' ),
						'desc_tip'		=> true,
					),	
					'rch_api_value3' => array(
						'title' 		=> __( 'POST Data Value for key 3', 'rc-woocommerce-custom-payment-gateway-api' ),
						'type' 			=> 'text',
						'description' 	=> __( 'After complete payment post data for API.', 'rc-woocommerce-custom-payment-gateway-api' ),
						'default'		=> __( '40', 'rc-woocommerce-custom-payment-gateway-api' ),
						'desc_tip'		=> true,
					),	

			 );
	}
	

	//To upgdare option

	public function validate_fields() {
	    if($this->text_box_required === 'no'){
	        return true;
        }

	    $textbox_value = (isset($_POST['custom_payment-admin-note']))? trim(sanitize_text_field($_POST['custom_payment-admin-note'])): '';
		if($textbox_value === ''){
			wc_add_notice( __('Please, complete the payment information.','rc-woocommerce-custom-payment-gateway-api'), 'error');
			return false;
        }
		return true;
	}

	public function process_payment( $order_id ) {
		global $woocommerce;
		$order = new WC_Order( $order_id );
		// Mark as on-hold (we're awaiting the cheque)
		$order->update_status($this->order_status, __( 'Awaiting payment', 'rc-woocommerce-custom-payment-gateway-api' ));
		// Reduce stock levels
		wc_reduce_stock_levels( $order_id );
		if(isset($_POST[ $this->id.'-admin-note']) && trim(sanitize_text_field($_POST[ $this->id.'-admin-note']))!=''){
			$order->add_order_note(wp_kses_post($_POST[ $this->id.'-admin-note']),1);
		}

		//Call API
		$rch_api_url 	= $this->get_option('rch_api_url');
		$rch_api_key1 	= $this->get_option('rch_api_key1');
		$rch_api_key2 	= $this->get_option('rch_api_key2');
		$rch_api_key3 	= $this->get_option('rch_api_key3');

		$rch_api_value1 = $this->get_option('rch_api_value1');
		$rch_api_value2 = $this->get_option('rch_api_value2');
		$rch_api_value3 = $this->get_option('rch_api_value3');

		//$d = $rch_api_url." = ".$rch_api_key1." = ".$rch_api_key2." = ".$rch_api_key3." = ".$rch_api_value1." = ".$rch_api_value2." = ".$rch_api_value3;

		if($rch_api_url!=''){
			// User data to send using HTTP POST method 
			$post_data  = array();

			$post_data[$rch_api_key1] 	= $rch_api_value1; 
			$post_data[$rch_api_key2] 	= $rch_api_value2; 
			$post_data[$rch_api_key3] 	= $rch_api_value3; 

			$args = array(
			    'body' 			=> json_encode($post_data),
			    'timeout' 		=> '5',
			    'redirection' 	=> '5',
			    'httpversion' 	=> '1.0',
			    'blocking' 		=> true,
			    'headers' 		=> array(),
			    'cookies' 		=> array()
			);
            
            //Post Request for api
			$response 		= wp_remote_post( $rch_api_url, $args );
			$response_body 	= $response['body'];

			// Create post object

		}



		// Remove cart
		$woocommerce->cart->empty_cart();
		// Return thankyou redirect
		return array(
			'result' => 'success',
			'redirect' => $this->get_return_url( $order )
		);	
	}

	public function payment_fields(){
	    ?>
		<fieldset>
			<p class="form-row form-row-wide">
                <label for="<?php echo $this->id; ?>-admin-note"><?php echo ($this->description); ?> <span class="required">*</span></label>
                <?php if($this->hide_text_box !== 'yes'){ ?>
				    <textarea id="<?php echo $this->id; ?>-admin-note" class="input-text" type="text" name="<?php echo $this->id; ?>-admin-note"></textarea>
                <?php } ?>
			</p>						
			<div class="clear"></div>
		</fieldset>
		<?php
	}
}
<?php
/*
Plugin Name: RatingCaptian
Description: Integrate your woocommerce shop with ratingcaptain
Author: mathieu.pl
Version: 2.7
License: GPLv2
Text Domain: rating-captain
*/
require('php-client/RatingCaptain.php');
if(!class_exists('duplicate_page')):
    class duplicate_page
    {
        /*
        * AutoLoad Hooks
        */
        public function __construct(){
            register_activation_hook(__FILE__, array(&$this, 'ratingcaptain_install'));
            add_action('admin_menu', array(&$this, 'ratingcaptain_options_page'));
/*            add_action('woocommerce_thankyou', array(&$this, 'ratingcaptain_init'), 10, 1);*/
            add_action( 'woocommerce_order_status_completed', array(&$this, 'proccess_backend'));

        }

        /*
        * Activation Hook
        */
        public function ratingcaptain_install(){
            $this->storeData('', false);
        }
        public function proccess_backend($order_id){
            $order = wc_get_order($order_id);
            if($order instanceof WC_Order){
                $opt = get_option('ratingcaptain_data');
                if($opt && $opt != ''){
                    $options = json_decode($opt);
                    if(isset($options->api_key)){
                        $ratingcaptain = new RatingCaptain($options->api_key);
                        if(isset($options->send_products) && $options->send_products != false){
                            foreach ($order->get_items() as $key_data => $item_data){
                                $product = $item_data->get_product();
                                if($product instanceof WC_Product){
                                    $image_id = $product->get_image_id();
                                    if($image_id){
                                        $image_url = wp_get_attachment_image_url($image_id, 'full');
                                    }else{
                                        $image_url = null;
                                    }
                                    $ratingcaptain->addProduct($product->get_sku(), $product->get_name(), $product->get_price(), $image_url);
                                }
                            }
                            $ratingcaptain->send(['external_id' => $order->get_order_number(), 'email' => $order->get_billing_email()]);
                        }
                    }
                }
            }
        }
        /*
        * Admin Menu
        */
        public function ratingcaptain_options_page(){
            add_options_page('RatingCaptain Page', 'RatingCaptain Page','manage_options','ratingcaptain_settings',array(&$this, 'ratingcaptain_settings'));
        }
        
        public function storeData($key, $send_products = false){
           add_option('ratingcaptain_data', json_encode(['api_key' => $key, 'send_products' => $send_products]));
        }
        /*
        * Duplicate Page Admin Settings
        */
        public function ratingcaptain_settings(){
            if(current_user_can( 'manage_options' )){
                include('Admin/settings.php');
            }
        }

        public function ratingcaptain_init($order_id){
            $opt = get_option('ratingcaptain_data');
            if($opt != null){
                $options = json_decode($opt);
                if(isset($options->api_key) && $options->api_key != null){
                    $this->add_script($order_id);
                }
            }
        }

        public function add_script($order_id){
            $opt = get_option('ratingcaptain_data');
            $options = json_decode($opt);

            if($order_id > 0){
                $order = wc_get_order($order_id);
                if($order instanceof WC_Order){
                    ?>
                    <script>
                        var RatingCaptain_data_script = {
                            "email": "<?php echo $order->get_billing_email()?>",
                            "external_id": "<?php echo $order->get_order_number()?>",
                            <?php if(isset($options->send_products) && $options->send_products){ ?>
                            "products":[
                                <?php foreach ($order->get_items() as $item_key => $item_data){
                                $product = $item_data->get_product();
                                $image_id = $product->get_image_id();
                                if($image_id){
                                    $image_url = wp_get_attachment_image_url($image_id, 'full');
                                }
                                ?>
                                {
                                    "id": "<?php echo $product->get_sku()?>",
                                    "name": "<?php echo $product->get_name()?>",
                                    "price": "<?php echo $product->get_price()?>",
                                    <?php if(isset($image_url) && $image_url != null){?>
                                    "imageUrl": "<?php echo $image_url?>"
                                    <?php } ?>
                                }
                                <?php } ?>
                            ]
                            <?php } ?>
                        }
                    </script>
                    <script src="https://ratingcaptain.com/api/js/<?php echo $options->api_key?>"></script>
                    <?php
                }
            }
        }

    }
    new duplicate_page;
endif;
?>

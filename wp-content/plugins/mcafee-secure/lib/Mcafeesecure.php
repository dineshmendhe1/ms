<?php
defined('ABSPATH') OR exit;

class Mcafeesecure
{
    public static function activate()
    {
        update_option('mcafeesecure_active', 1);
    }

    public static function get_site_id(){
        $existing_site_id = get_option('mcafeesecure_site_id');
        if (!empty($existing_site_id)) {
            return $existing_site_id;
        }

        $endpoint_host = "https://www.mcafeesecure.com";
        $arrHost = parse_url(home_url('', $scheme = 'http'));
        $host = $arrHost['host'];

        $sitemap_req_url = $endpoint_host . "/rpc/ajax?do=lookup-site-status&host=" . urlencode($host);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $sitemap_req_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        
        $rjson = json_decode($response, true);
        $site_id = $rjson['siteId'];
        update_option('mcafeesecure_site_id', $site_id);
        return $site_id;
    }

    public static function install()
    {
        add_shortcode('mcafeesecure', 'Mcafeesecure::engagement_trustmark_shortcode');
        add_shortcode('mcafeesecure_sip', 'Mcafeesecure::sip_trustmark_shortcode');
        add_action('admin_menu', 'Mcafeesecure::admin_menus');
        add_action('wp_footer', 'Mcafeesecure::inject_code');
        add_action('do_robots', 'Mcafeesecure::robots');

        add_action('admin_enqueue_scripts', 'Mcafeesecure::admin_onboarding');
        add_action('wp_ajax_mcafeesecure_get_data', 'Mcafeesecure::ajax_get_data');
        add_action('wp_ajax_mcafeesecure_save_data', 'Mcafeesecure::ajax_save_data');

        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            Mcafeesecure::install_woocommerce();
        }
    }

    public static function robots(){
        $site_id = Mcafeesecure::get_site_id();
        if(!empty($site_id)){
            echo "\nSitemap: http://cdn.ywxi.net/sitemap/".$site_id."/1.xml\n";
        }else{
            error_log("Failed to retrieve site ID");
        }
    }

    public static function request_sip($order_id)
    {
        $endpoint_host = "https://www.mcafeesecure.com";

        $order = new WC_Order($order_id);
        $order_total = $order->get_total();
        $first_name = $order->billing_first_name;
        $last_name = $order->billing_last_name;
        $order_number = $order->get_order_number();
        $email = $order->billing_email;

        if (empty($email)) {
            return;
        }

        $country_code = $order->billing_country;

        $arrHost = parse_url(home_url('', $scheme = 'http'));
        $host = $arrHost['host'];

        $sip_req_url = $endpoint_host . "/rpc/ajax?do=track-site-conversion&jsoncallback=f&t=purchase&s=6&o=" . urlencode($order_number) . "&e=" . urlencode($email) . "&fn=" . urlencode($first_name) . "&ln=" . urlencode($last_name) . "&c=" . urlencode($country_code) . "&h=" . urlencode($host) . "&a=" . urlencode($order_total);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $sip_req_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
    }

    public static function install_woocommerce()
    {
        add_action('woocommerce_order_status_on-hold', 'Mcafeesecure::request_sip');
    }

    public static function ajax_get_data()
    {
        wp_send_json(get_option('mcafeesecure_data', array("empty" => 1)));
    }

    public static function ajax_save_data()
    {
        update_option('mcafeesecure_data', $_POST['data']);
        wp_send_json(get_option('mcafeesecure_data', array("empty" => 1)));
    }

    public static function admin_onboarding($hook)
    {
        if ('plugins.php' != $hook) {
            return;
        }

        wp_enqueue_script('mcafeesecure-onboarding-script', plugins_url('/js/onboarding.js', WP_PLUGIN_DIR . '/mcafee-secure/mcafee-secure.php'), array('jquery'));
        wp_enqueue_script('mcafeesecure-bootstrap-js', plugins_url('/js/bootstrap.min.js', WP_PLUGIN_DIR . '/mcafee-secure/mcafee-secure.php'), array('jquery'));
        wp_enqueue_style('mcafeesecure-popover-css', plugins_url('/css/popover.css', WP_PLUGIN_DIR . '/mcafee-secure/mcafee-secure.php'));
        wp_enqueue_style('mcafeesecure-override-css', plugins_url('/css/override.css', WP_PLUGIN_DIR . '/mcafee-secure/mcafee-secure.php'));

        $mcafeesecure_ajax_object = array('ajax_url' => admin_url('admin-ajax.php'), 'data' => get_option('mcafeesecure_data', "{}"));

        wp_localize_script('mcafeesecure-onboarding-script', 'mcafeesecure_ajax_object', $mcafeesecure_ajax_object);
    }

    public static function deactivate()
    {
        delete_option("mcafeesecure_active");
    }

    public static function uninstall()
    {
        delete_option("mcafeesecure_active");
        delete_option("mcafeesecure_data");
        delete_option("mcafeesecure_site_id");
    }

    public static function engagement_trustmark_shortcode($atts = array())
    {
        $a = shortcode_atts(array(
            'width' => 90,
        ), $atts);

        $width = intval($a['width']);
        $width = min(max(60, $width), 120);
        return "<script src='https://cdn.ywxi.net/js/inline.js?w=" . $width . "'></script>";
    }

    public static function sip_trustmark_shortcode($atts = array())
    {
        return "<script src='https://cdn.ywxi.net/js/inline.js?t=103'></script>";
    }

    public static function admin_menus()
    {
        add_menu_page(
            'McAfee SECURE',
            'McAfee SECURE',
            'activate_plugins',
            'mcafee-secure-settings',
            'Mcafeesecure::settings_page',
            plugins_url() . '/mcafee-secure/images/mcafee-secure-16x16.png');
    }

    public static function settings_page()
    {
        require WP_PLUGIN_DIR . '/mcafee-secure/lib/settings_page.php';
    }

    public static function inject_code()
    {
        echo <<<EOT
            <script type="text/javascript">
              (function() {
                var sa = document.createElement('script'); sa.type = 'text/javascript'; sa.async = true;
                sa.src = ('https:' == document.location.protocol ? 'https://cdn' : 'http://cdn') + '.ywxi.net/js/1.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(sa, s);
              })();
            </script>
EOT;
    }
}

?>
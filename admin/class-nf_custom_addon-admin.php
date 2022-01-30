<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://apurba.me
 * @since      1.0.0
 *
 * @package    Nf_custom_addon
 * @subpackage Nf_custom_addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nf_custom_addon
 * @subpackage Nf_custom_addon/admin
 * @author     Apurba Podder <apurba.jnu@gmail.com>
 */
class Nf_custom_addon_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * Increment id of this Form
     */
    private $form_id = 0;	
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;	

    /**
     * plugin folder
     */

    private $plugin_dir = 'nf_custom_addon';

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        // update_option('ymb_',0);
        // die();
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->actions();

        // $this->generatePdf('test','<h1>Hello world!</h1>');
    }

    /**
    * difined actions and filter
    * @return void
    */
    public function actions()
    {
        add_filter( 'ninja_forms_register_fields', [$this, 'register_fields'] );

        add_filter( 'ninja_forms_field_template_file_paths', [$this, 'register_template_path'] );
        add_filter( 'ninja_forms_submit_data', [$this, 'base64toImage'] );
        // add_action( 'my_ninja_forms_processing', [$this, 'my_ninja_forms_processing_callback'] );

        add_filter( 'ninja_forms_action_email_attachments', [$this, 'attached_pdf_after_generating'], 10, 3 );
    }

    public function base64toImage($formData)
    {
        // error_log(print_r($formData,1));
        foreach ( $formData['fields'] as $key => $field ) {
            if ( $this->is_base64_image($field['value'])) {
                $formData['fields'][$key]['value'] = $this->saveSignature($field['value']);
                // $field['value'] = $this->saveSignature($field['value']);
            }
        }
        // error_log('From Data');
        // error_log(print_r($formData,1));

        return $formData;
    }

    /**
	 * Directory create following array
	 */
    public function create_directory_from_array_by_index(Array $folder_list):String
    {
        $directory_path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->plugin_dir;
        $parent_folder = $directory_path . DIRECTORY_SEPARATOR;

        if (count($folder_list) > 0) {
            foreach ($folder_list as $folder) {
                $parent_folder = $parent_folder . DIRECTORY_SEPARATOR . $folder ;
                if (!is_dir($parent_folder)) {
                    mkdir($parent_folder );
                }
            }
        }

        return $parent_folder;
    }

    public function create_basic_directory_for_pdf( Array $resource_directory = []):String
    {
        $basic_directory = ['pdfs', date('Y'), date('F'), date('j')];   
        $basic_directory = array_merge($basic_directory,$resource_directory);
        return $this->create_directory_from_array_by_index($basic_directory);
    }

    public function generatePdf($file_name,$content)
    {
        $destination = $this->create_basic_directory_for_pdf ();
        $this->get_incriment_id('YMB_');

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHTMLFooter('
        <div class="footer">
        	<table>
        		<tr>
        			<td><img width="300px" src="' . plugin_dir_path( __FILE__ ) . 'signs/logo2.png' . '"></td>
        			<td>
        				<p>
        					<span>YOURMONEYBACK</span> | ' . $this->form_id . '
        				</p>
        			</td>
        		</tr>
        	</table>
        </div>');
        $mpdf->WriteHTML($content);
        $mpdf->Output($destination . DIRECTORY_SEPARATOR . $file_name . '.pdf', 'F' );
        return $destination . DIRECTORY_SEPARATOR . $file_name . '.pdf';
    }

    public function get_incriment_id($prefix)
    {
        $key_ = 'ymb_';
        if ($this->form_id === 0) {
            $submitted_id = get_option($key_,false);
            if (empty($submitted_id)) {
                update_option($key_,1);
            }
            $submitted_id = intval(get_option($key_,false)) + 1;
            update_option($key_,$submitted_id);
            $this->form_id = $prefix . $submitted_id;
        }
    }

    function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                if (isset($_SERVER['HTTP_X_FORWARDED'])) {
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                } else {
                    if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    } else {
                        if (isset($_SERVER['HTTP_FORWARDED'])) {
                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                        } else {
                            if (isset($_SERVER['REMOTE_ADDR'])) {
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                            } else {
                                $ipaddress = 'UNKNOWN';
                            }
                        }
                    }
                }
            }
        }
        return $ipaddress;
    }

    function attached_pdf_after_generating( $attachments, $data, $settings )
    {
        $pdf_elements = [
            // 'logo', //image src
            // 'logo_2', // image
            // 'admin_sign', //image
            // 'signer',
            // 'account_id',
            // 'form_data', //containg form data with 'ip'
            // 'spouse',
            // 'spouse_form_data',
            // signs

        ];
        // error_log(print_r($data['fields'],1));
        $pdf_elements['logo'] = plugin_dir_path( __FILE__ ) . 'signs/logo.png';
        $pdf_elements['logo_2'] = plugin_dir_path( __FILE__ ) . 'signs/logo2.png';
        $pdf_elements['admin_sign'] = plugin_dir_path( __FILE__ ) . 'signs/admin.jpeg';
        $pdf_elements['spouse_form_data'] = null;
        $pdf_elements['spouse'] = null;
        if (!empty($data) && array_key_exists('fields',$data)) {
            $fields = $data['fields'];
            $available_settings = array_column($fields,'settings'); 
            $settings_keys = array_column($available_settings,'key');
            // error_log(print_r($available_settings,1));
            //if not YMB registration form return 
            if (!in_array('pps_number',$settings_keys)) {
                return $attachments;
            }
            //check is it a YMB Registration form 
            foreach ($fields as $field) {
                $settings = $field['settings'];

                //get form data
                if ( in_array( $settings['key'], [
                    'first_name',
                    'surname',
                    'address',
                    'email_address',
                    'preferred_payment_method_',
                    'pps_number',
                    'date_of_birth',
                    'phone_number',
                    'job_title',
                    'number_of_childrenss',
                    'marital_status',

                ] ) ) {
                    $pdf_elements['form_data'][$settings['field_label']] = $field['value'];
                }
                // spouse form data

                if ( in_array( $settings['key'], [
                    'spouse_first_name',
                    'spouse_surname',
                    'spouse_email_address',
                    'spouse_pps_number',
                    'spouse_job_title',
                    'spouse_phone_number',
                    'date_of_marriage',
                    'spouse_date_of_birth',

                ]  ) && !empty($field['value']) ) {
                    $pdf_elements['spouse_form_data'][$settings['field_label']] = $field['value'];
                }

                if ( in_array( $settings['key'], ['first_name', 'surname'] ) ) {
                    $pdf_elements['signer'] .= ' ' . $field['value'];
                }

                if ( in_array( $settings['key'], ['spouse_first_name', 'spouse_surname'] ) && !empty($field['value']) ) {
                    $pdf_elements['spouse'] .= ' ' . $field['value'];
                }

                $pdf_elements['account_id'] = $this->form_id;
                $pdf_elements['form_data']['ip'] = $this->get_client_ip();
                if ($settings['type'] == 'signature') {
                    $pdf_elements['signs'][] = $field['value'];
                }
            }//end foreach
        }
        // error_log(print_r($pdf_elements,1));
        // error_log(pdf_contents($pdf_elements));

        $file_name = $pdf_elements['signer'] . '_' . $pdf_elements['account_id'];
        $attachments[] = $this->generatePdf($file_name, pdf_contents($pdf_elements));

        return $attachments;
    }

    public function is_base64_image($base64)
    {
        $base64 = explode(',', $base64);
        if (!array_key_exists(1,$base64)) {
            return false;
        }
        $base64 = $base64[1];

        if (empty($base64)) {
            return false;
        }

        $data = base64_decode($base64);
        $img = @imagecreatefromstring($data);
        if (!$img) {
            return false;
        }

        imagepng($img, 'tmp.png');
        $info = getimagesize('tmp.png');

        unlink('tmp.png');

        if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
            return true;
        }

        return false;
    }

    public  function saveSignature($data_uri)
    {
        $random_name = strftime( '%Y%m%d-%H%M%S-' ) . substr( md5( mt_rand() ), 0, 18 );
        $destination = $this->create_basic_directory_for_pdf (['sign']);
        $encoded_image = explode(',', $data_uri)[1];
        $decoded_image = base64_decode($encoded_image);
        file_put_contents( $destination . DIRECTORY_SEPARATOR . $random_name . '.png', $decoded_image);
        return plugin_dir_url( $destination) . 'sign' . DIRECTORY_SEPARATOR . $random_name . '.png';
    }

    /**
    * Optional. If your extension creates a new field interaction or display template...
    */
    public function register_fields( $fields )
    {
        $fields['signature'] = new SignatureFieldInitiate;

        return $fields;
    }

    public function register_template_path( $paths )
    {
        $paths[] = plugin_dir_path(__FILE__) . 'ninja_fields/templates/';

        return $paths;
    }

    /**
    * Register the stylesheets for the admin area.
    *
    * @since 1.0.0
    */
    public function enqueue_styles()
    {
        /**
        * This function is provided for demonstration purposes only.
        *
        * An instance of this class should be passed to the run() function
        * defined in Nf_custom_addon_Loader as all of the hooks are defined
        * in that particular class.
        *
        * The Nf_custom_addon_Loader will then create the relationship
        * between the defined hooks and the functions defined in this
        * class.
        */

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nf_custom_addon-admin.css', [], $this->version,
'all' );
    }

    /**
    * Register the JavaScript for the admin area.
    *
    * @since 1.0.0
    */
    public function enqueue_scripts()
    {
        /**
        * This function is provided for demonstration purposes only.
        *
        * An instance of this class should be passed to the run() function
        * defined in Nf_custom_addon_Loader as all of the hooks are defined
        * in that particular class.
        *
        * The Nf_custom_addon_Loader will then create the relationship
        * between the defined hooks and the functions defined in this
        * class.
        */

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nf_custom_addon-admin.js', ['jquery'],
$this->version, false );
    }
}
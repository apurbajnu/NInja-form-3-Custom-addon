<?php
if ( ! defined( 'ABSPATH' ) ) exit;



class SignatureFieldInitiate extends NF_Abstracts_Input
{
    protected $_name = 'signature';

    protected $_section = 'misc';

    protected $_icon = 'hashtag';

    protected $_type = 'signature';

    protected $_templates = 'signature';

    protected $_test_value = 0;

    // protected $_settings = array( 'number' );

    // protected $_settings_exclude = array( 'input_limit_set', 'disable_input' );

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = esc_html__( 'Signature Field', 'ninja-forms' );
    }

    public function get_parent_type()
    {
        return parent::get_type();
    }

   

    protected $_settings = array( 'label','label_pos','filtrip_shortcode','required');

   

}











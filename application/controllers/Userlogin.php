<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userlogin extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
		// Force SSL
		//$this->force_ssl();
	
		// Form and URL helpers always loaded (just for convenience)
		$this->load->helper('url');
		$this->load->helper('form');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function login()
	{
		// Method should not be directly accessible
		//current admin credentials:  viadmin/Bbf1234!
		//echo 'logging in';exit;
	
		if( $this->uri->uri_string() == 'examples/login')
			show_404();
	
		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' )
			$this->require_min_level(1);
		
		
	
		$this->setup_login_form();
		
		//echo 'logging in';exit;
		$html = '';
		//$html = $this->load->view('userlogin/page_header', '', TRUE);
		//echo 'logging in';exit;
		$html .= $this->load->view('userlogin/login_form', '', TRUE);
		
		//$html .= $this->load->view('userlogin/page_footer', '', TRUE);
		
		//echo 'logging in';exit;
	
		echo $html;
	}
	
	public function logout()
	{
		$this->authentication->logout();
	
		// Set redirect protocol
		$redirect_protocol = USE_SSL ? 'https' : NULL;
	
		redirect( site_url( LOGIN_PAGE . '?' . AUTH_LOGOUT_PARAM . '=1', $redirect_protocol ) );
	}
	
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	/**
	 * Main Controller for managing data and assembling the How are Vermont's Young Children report
	 */
	public function index()
	{
		
		if( $this->require_role('admin') )
		{
			$data = array();
			$this->load->model('User_model');
			$users = $this->User_model->getUsers();
			$data['users'] = $users;
			
			$this->load->view('user/index',$data);
		}
		
	}

	public function edit($userId = null)
	{
		
		if( $this->require_role('admin') )
		{
			$data = array();
			$this->load->model('User_model');

			if($userId != null){
				$user = $this->User_model->getUserById($userId);
				$data['user'] = $users;
			}
			
			$this->load->view('user/edit',$data);
		}
		
	}

	public function save(){

		print_r($_POST); 
		$this->load->helper('auth');
		$this->load->model('User_model');
		//exit;

		$user_data = array();
		$user_data['username']   = $_POST['username'];
		$user_data['passwd']     = $_POST['password'];
		$user_data['email']      = $_POST['email'];
		$user_data['auth_level'] = $_POST['role'];
		$user_data['passwd']     = $this->authentication->hash_passwd($user_data['passwd']);
		$user_data['user_id']    = $this->User_model->get_unused_id();
		$user_data['created_at'] = date('Y-m-d H:i:s');

		$this->db->set($user_data)->insert(db_table('user_table'));

		if( $this->db->affected_rows() == 1 ) {
			//echo '<h1>Congratulations</h1>' . '<p>User ' . $user_data['username'] . ' was created.</p>';
		}
		else
		{
			//echo '<h1>User Creation Error(s)</h1>' . validation_errors();
		}

		$this->load->helper("Url");
		redirect('/users');

	}

}
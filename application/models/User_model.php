<?php
class User_model extends MY_Model {

  public function __construct()
   {
      $this->load->database();
   }
   
   function createUser($data){
   	$this->db->insert('acs_report', $data);
   }
   
   function getUserById($userId){
    $this->db->select('*');
   	$this->db->from('users');
    $this->db->where('user_id', $userId);
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results[0];
    }
   
   function getUsers(){
   
   	//get all system users
   	$this->db->select('user_id, username, email');
   	$this->db->from('users');
   	$query = $this->db->get();
   	$results = $query->result();
   	return $results;
   
   }

   public function get_unused_id()
    {
        // Create a random user id between 1200 and 4294967295
        $random_unique_int = 2147483648 + mt_rand( -2147482448, 2147483647 );

        // Make sure the random user_id isn't already in use
        $query = $this->db->where( 'user_id', $random_unique_int )
            ->get_where( $this->db_table('user_table') );

        if( $query->num_rows() > 0 )
        {
            $query->free_result();

            // If the random user_id is already in use, try again
            return $this->get_unused_id();
        }

        return $random_unique_int;
    }
     
   
}
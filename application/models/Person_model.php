<?php defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );
/*
 * Person model, or everything one Phonebook entry can care about.
 * Mainly defines the table layout,  CRUD op set, builds the actual query on the client input.
 *
 * author: @sheha
 */

class Person_model extends CI_Model {


	var $table = 'persons';

	var $column_order = array( // column layout
		'first_name',
		'last_name',
		'gender',
		'address',
		'dob',
		null // image, no significance in data wrangling, thus set to null
	);

	 var $column_search = array('first_name','last_name','address'); // column search indexers

	 var $order = array('id' => 'desc'); // default order

	public function __construct() {
		parent::__construct();

	}

	/*
	 * helper for populating datatable
	 */
	private function _get_datatables_query( $user_id = null ) {

		$this->db->from( $this->table );
		if ( isset ( $user_id ) ) {
			$this->db->where( 'user_id', $user_id );
		}

		$i = 0; // like every good counter, has to re/start somewhere

		foreach ( $this->column_search as $item ) // loop through indexers
		{
			if ( $this->input->post['search']['value'] ) // if there is a search param from client
			{

				if ( $i === 0 ) // first loop
				{
					$this->db->group_start(); // query builder group start
					$this->db->like( $item, $this->input->post['search']['value'] ); // first filter applied
				} else {
					$this->db->or_like( $item, $this->input->post['search']['value'] );
					// clause
				}

				if ( count( $this->column_search ) - 1 == $i ) //last loop, bail out
				{
					$this->db->group_end(); // pack up filter clauses
				}
			}
			$i ++;
		}

		if ( isset( $this->input->post['order'] ) ) // order processing turned OFF
		{

			$this->db->order_by( $this->column_order[ $this->input->post['order']['0']['column'] ], $this->input->post['order']['0']['dir'] );

		} else if ( isset( $this->order ) ) {

			$order = $this->order;
			$this->db->order_by( key( $order ), $order[ key( $order ) ] );
		}
	}

	/*
	 * Main getter for the datatable on the client
	 */
	function get_datatables( $user_id ) {
		$this->_get_datatables_query( $user_id );
		if ( $this->input->post['length'] != - 1 ) {
			$this->db->limit( $this->input->post['length'], $this->input->post['start'] );
		}
		$query = $this->db->get();

		return $query->result();
	}

	function count_filtered() {
		$this->_get_datatables_query();
		return $query = $this->db->count_all_results();
	}

	public function count_all( $id ) {
		$this->db->select('id');
		$this->db->from( $this->table );
		$this->db->where( 'user_id', $id );

		return $this->db->count_all_results();
	}

	public function get_by_id( $id ) {
		$this->db->from( $this->table );
		$this->db->where( 'id', $id );
		$query = $this->db->get();

		return $query->row();
	}

	/*
	 * C.R.U.D. helpers for the Person controller
	 */
	public function save( $data ) {
		$this->db->insert( $this->table, $data );

		return $this->db->insert_id();
	}

	public function update( $where, $data ) {
		$this->db->update( $this->table, $data, $where );

		return $this->db->affected_rows();
	}

	public function delete_by_id( $id ) {
		$this->db->where( 'id', $id );
		$this->db->delete( $this->table );
	}
	// Compares the original owner id in the DB with the one stored in session
	public function verify_relation( $id, $email ){
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('email', $email);
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

}
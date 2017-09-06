<?php defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/*
 * Person model, or plainly, model of one record in our phonebook.
 * Defines the getter and setters method for the
 * entry in the phonebook.
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
	private function _get_datatables_query( $user_id  ) {

		$this->db->select('*');
		$this->db->from( $this->table );
		$this->db->where( 'user_id', $user_id );

		$i = 0; // like every good counter, has to re/start somewhere

		foreach ( $this->column_search as $item ) // loop through indexers
		{
			if ( $this->input->post['search']['value'] ) // if there is a search param from client
			{

				if ( $i === 0 ) // first loop
				{
					$this->db->group_start(); // query builder start
					$this->db->like( $item, $this->input->post['search']['value'] ); // first filter applied
				} else {
					$this->db->or_like( $item, $this->input->post['search']['value'] ); // concat current with previous filter
					// clause
				}

				if ( count( $this->column_search ) - 1 == $i ) //last loop, bail out
				{
					$this->db->group_end(); // pack up filter clauses
				}
			}
			$i ++;
		}

		if ( isset( $this->input->post['order'] ) ) // order processing
		{

			$this->db->order_by( $this->column_order[ $this->input->post['order']['0']['column'] ], $this->input->post['order']['0']['dir'] );

		} else if ( isset( $this->order ) ) {

			$order = $this->order;
			$this->db->order_by( key( $order ), $order[ key( $order ) ] );
		}
	}

	/*
	 * Retrieves the populated datatable
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
		$query = $this->db->get();

		return $query->num_rows();
	}

	/*
	 * C.R.U.D. helpers designed to be invoked from the Person controller
	 */

	public function count_all( $id ) {
		$this->db->from( $this->table );
		$this->db->where( 'id', $id );
		return $this->db->count_all_results();
	}

	public function get_by_id( $id ) {
		$this->db->from( $this->table );
		$this->db->where( 'id', $id );
		$query = $this->db->get();

		return $query->row();
	}

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
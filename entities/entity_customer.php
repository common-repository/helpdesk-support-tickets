<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that represents a customer (a WP registered user)
class hst_Entities_Customer
{

	//Properties
	public $CustomerId;
    public $CustomerDisplayName;
	public $CustomerEmail;

	public $CustomerAvatar;

}

?>
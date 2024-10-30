<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that represents a file attachment
class hst_Entities_Attachment
{

	//Properties
	public $AttachmentId;
    public $EntityId;
	public $EntityType;
	public $AttachmentUrl;
	public $AttachmentPath;
	public $AttachmentSize;
	public $AttachmentFilename;
	public $AttachmentUploadUserId;
	public $AttachmentUploadUserType;
	public $AttachmentCreatedDate;

	//Readonly (joins)
	public $AttachmentUploadUserDisplayName;

}

?>
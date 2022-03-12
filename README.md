# Magelearn_CustomerAvatar
This module adds ability to Customer to upload the profile picture in their account.

This image upload option will available in `Customer Registration Page`, `Edit Account Inofrmation Page`, `Product Review Page` and `Cutomer listing grid` at admin.

# Issue
`Exception #0 (InvalidArgumentException): Invalid parameter given. A valid $fileId[tmp_name] is expected.`

# Solutions

You can solve this issue in two ways.

1. Reconfigure your <b>php.ini</b> and set the value <b>upload_tmp_dir</b> to one of the below allowed folders of Magento.

"<magento_root>/pub/media"

"<magento_root>/var"

"<magento_root>/var/tmp"

"<magento_root>/pub/media/upload"

2. Go to <code>vendor/magento/framework/File/Uploader.php</code>

In function <b>_setUploadFileId($fileId)</b>, change:

$this->validateFileId($fileId);
to

//$this->validateFileId($fileId);


# Referances
https://magento.stackexchange.com/questions/296044/invalid-parameter-given-a-valid-fileidtmp-name-is-expected

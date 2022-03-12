<?php

namespace Magelearn\CustomerAvatar\Block\Attributes;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Customer\Api\CustomerMetadataInterface;

class Avatar extends \Magento\Framework\View\Element\Template
{   
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;
    
    /**
     * Core file storage
     *
     * @var \Magento\MediaStorage\Helper\File\Storage
     */
    protected $coreFileStorage;
    
    /**
     * @var \Magento\Framework\View\Element\AbstractBlock
     */
    protected $viewFileUrl;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     *
     * @param Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Helper\File\Storage $coreFileStorage
     * @param \Magento\Framework\View\Asset\Repository $viewFileUrl
     * @param \Magento\Customer\Model\Customer $customer
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Helper\File\Storage $coreFileStorage,
        \Magento\Framework\View\Asset\Repository $viewFileUrl,
        \Magento\Customer\Model\Customer $customer
    ) {
        $this->_filesystem = $filesystem;
        $this->coreFileStorage = $coreFileStorage;
        $this->viewFileUrl = $viewFileUrl;
        $this->customer = $customer;
        parent::__construct($context);
    }

    /**
     * Check the file is already exist in the path.
     * @return boolean
     */
    public function checkImageFile($file)
    {
        $file = base64_decode($file);
        $directory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $fileName = CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER . '/' . ltrim($file, '/');
        $path = $this->_filesystem->getDirectoryRead(
                DirectoryList::MEDIA
            )->getAbsolutePath($fileName);
        if (!$directory->isFile($fileName)
            && !$this->coreFileStorage->processStorageFile($path)
        ) {
            return false;
        }
        return true;
    }

    /**
     * Get the avatar of the customer is already logged in
     * @return string
     */
    public function getAvatarCurrentCustomer($file)
    {
        if ($this->checkImageFile(base64_encode($file)) === true) {
            return $this->getUrl('viewfile/avatar/view/', ['image' => base64_encode($file)]);
        }
        return $this->viewFileUrl->getUrl('Magelearn_CustomerAvatar::images/no-profile-photo.jpg');
    }

    /**
     * Get the avatar of the customer by the customer id
     * @return string
     */
    public function getCustomerAvatarById($customer_id = false)
    {
        if ($customer_id) {
            $customerDetail = $this->customer->load($customer_id);
            if ($customerDetail && !empty($customerDetail->getProfilePicture())) {
                if ($this->checkImageFile(base64_encode($customerDetail->getProfilePicture())) === true) {
                    return $this->getUrl('viewfile/avatar/view/', ['image' => base64_encode($customerDetail->getProfilePicture())]);
                }
            }
        }
        return $this->viewFileUrl->getUrl('Magelearn_CustomerAvatar::images/no-profile-photo.jpg');
    }
}

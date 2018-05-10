<?php

namespace Boraso\Toolkit\Observer;

use Boraso\Toolkit\Helper\Data;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\ObserverInterface;

class AddHandles implements ObserverInterface
{

    const CATALOG_CATEGORY_VIEW_ACTION_NAME = 'catalog_category_view';

    protected $customerSession;
    protected $request;
    protected $toolkitHelper;

    public function __construct(
        CustomerSession $customerSession,
        Http $request,
        Data $helper
    ) {
        $this->customerSession = $customerSession;
        $this->request         = $request;
        $this->toolkitHelper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $fullActionName = $observer->getFullActionName();

        $layout = $observer->getEvent()->getLayout();

        if ($this->customerSession->isLoggedIn()) {
            $layout->getUpdate()->addHandle('customer_logged_in');
        } else {
            $layout->getUpdate()->addHandle('customer_logged_out');
        }

        if ($fullActionName == self::CATALOG_CATEGORY_VIEW_ACTION_NAME) {
            if($this->toolkitHelper->getHideCategoryDescription() && !empty($this->request->getParam('p'))){
                $layout->getUpdate()->addHandle('category_pagination_two_or_more');
                $layout->getUpdate()->addHandle('pagination_' . $this->request->getParam('p'));
            }
            else{
                $layout->getUpdate()->addHandle('category_pagination_first');
            }
        }
    }
}
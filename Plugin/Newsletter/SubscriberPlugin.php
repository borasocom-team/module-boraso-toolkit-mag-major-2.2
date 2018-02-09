<?php

namespace Boraso\Toolkit\Plugin\Newsletter;

use Magento\Newsletter\Model\Subscriber;
use Boraso\Toolkit\Helper\Data;

class SubscriberPlugin {

    /**
     * @var Data
     */
    protected $helperData;

    public function __construct(Data $helperData){
        $this->helperData = $helperData;
    }

    public function aroundSendConfirmationRequestEmail(Subscriber $subject, callable $proceed){
        if(!$this->helperData->getEnableSendEmail()) { //Invio mail transazionali newsletter da Magento disabilitato
            return false;
        }

        $result = $proceed();
        return $result;
    }

    public function aroundSendConfirmationSuccessEmail(Subscriber $subject, callable $proceed){
        if(!$this->helperData->getEnableSendEmail()) { //Invio mail transazionali newsletter da Magento disabilitato
            return false;
        }

        $result = $proceed();
        return $result;
    }

    public function aroundSendUnsubscriptionEmail(Subscriber $subject, callable $proceed){
        if(!$this->helperData->getEnableSendEmail()) { //Invio mail transazionali newsletter da Magento disabilitato
            return false;
        }

        $result = $proceed();
        return $result;
    }
}
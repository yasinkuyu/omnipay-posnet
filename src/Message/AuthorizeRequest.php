<?php

namespace Omnipay\Posnet\Message;

/**
 * Posnet Authorize Request
 * 
 * (c) Yasin Kuyu
 * 2015, insya.com
 * http://www.github.com/yasinkuyu/omnipay-posnet
 */
class AuthorizeRequest extends PurchaseRequest {

    public function getData() {

        $this->validate('card');
        $this->getCard()->validate();
        $currency = $this->getCurrency();

        $data['orderID'] = $this->getOrderId();
        $data['currencyCode'] = $this->currencies[$currency];
        $data['installment'] = $this->getInstallments();
        
        $data['extraPoint'] = $this->getExtraPoint();
        $data['multiplePoint'] = $this->getMultiplePoint();
        
        $data['amount'] = $this->getAmountInteger();
        $data['ccno'] = $this->getCard()->getNumber();
        $data['expDate'] = $this->getCard()->getExpiryDate('my');
        $data["cvc"] = $this->getCard()->getCvv();
 
        return $data;
    }

}

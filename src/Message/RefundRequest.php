<?php

namespace Omnipay\Posnet\Message;

/**
 * Posnet Purchase Request
 * 
 * (c) Yasin Kuyu
 * 2015, insya.com
 * http://www.github.com/yasinkuyu/omnipay-posnet
 */
class RefundRequest extends PurchaseRequest {

    public function getData() {

        $this->validate('transid', 'amount');
        $currency = $this->getCurrency();
 
        $data['hostLogKey'] = $this->getTransactionId();
        $data['currencyCode'] = $this->currencies[$currency];
        $data['amount'] = $this->getAmountInteger();

        return $data;
    }

}

<?php

namespace Omnipay\Posnet\Message;

/**
 * Posnet Complete Capture Request
 * 
 * (c) Yasin Kuyu
 * 2015, insya.com
 * http://www.github.com/yasinkuyu/omnipay-posnet
 */
class CaptureRequest extends PurchaseRequest {

    public function getData() {

        $this->validate('transid', 'amount');
        $currency = $this->getCurrency();
 
        $data['hostLogKey'] = $this->getTransId();
        $data['currencyCode'] = $this->currencies[$currency];
        $data['amount'] = $this->getAmountInteger();
        $data['installment'] = $this->getInstallment();

        return $data;
    }

}

<?php

namespace Omnipay\Posnet\Message;

use DOMDocument;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Posnet Purchase Request
 * 
 * (c) Yasin Kuyu
 * 2015, insya.com
 * http://www.github.com/yasinkuyu/omnipay-posnet
 */
class PurchaseRequest extends AbstractRequest {

    protected $endpoint = "http://setmpos.ykb.com/PosnetWebService/XML";
    protected $authentication = "https://www.posnet.ykb.com/PosnetWebService/XML";
    protected $authorization = "https://www.posnet.ykb.com/3DSWebService/YKBPaymentService";
 
    protected $currencies = [
        'TRY' => 'YT',
        'USD' => 'US',
        'EUR' => 'EU'
    ];

    public function getData() {

        $this->validate('amount', 'card');
        $this->getCard()->validate();
        $currency = $this->getCurrency();

        $data['orderID'] = $this->getOrderId();
        $data['currencyCode'] = $this->currencies[$currency];
        $data['installment'] = $this->getInstallments();
        
        $data['extraPoint'] = $this->getExtraPoint();
        $data['multiplePoint'] = $this->getMultiplePoint();
        
        $data['cardHolderName'] = $this->getCard()->getFistName() . " ". $this->getCard()->getLastName();
        $data['amount'] = $this->getAmount();
        $data['ccno'] = $this->getCard()->getNumber();
        $data['expDate'] = $this->getCard()->getExpiryDate('my');
        $data["cvc"] = $this->getCard()->getCvv();
 
        return $data;
    }

    public function sendData($data) {

        $document = new DOMDocument('1.0', 'ISO-8859-9');
        $root = $document->createElement('posnetRequest');

        $root->appendChild($document->createElement('mid', $this->getMerchantId()));
        $root->appendChild($document->createElement('tid', $this->getTerminalId()));

        // Each array element 
        $ossRequest = $document->createElement($this->getType());
        foreach ($data as $id => $value) {
            $ossRequest->appendChild($document->createElement($id, $value));
        }

        $document->appendChild($root);

        // Post to Posnet
        $headers = array(
            'Content-Type' => 'application/x-www-form-urlencoded'
        );

        // Register the payment
        $this->httpClient->setConfig(array(
            'curl.options' => array(
                'CURLOPT_SSL_VERIFYHOST' => 2,
                'CURLOPT_SSLVERSION' => 0,
                'CURLOPT_SSL_VERIFYPEER' => 0,
                'CURLOPT_RETURNTRANSFER' => 1,
                'CURLOPT_POST' => 1
            )
        ));

        $httpResponse = $this->httpClient->post($this->endpoint, $headers, $document->saveXML())->send();

        return $this->response = new Response($this, $httpResponse->getBody());
    }
    public function getMerchantId() {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value) {
        return $this->setParameter('merchantId', $value);
    }

    public function getTerminalId() {
        return $this->getParameter('terminalId');
    }

    public function setTerminalId($value) {
        return $this->setParameter('terminalId', $value);
    }

    public function getInstallments() {
        return $this->getParameter('installments');
    }

    public function setInstallments($value) {
        return $this->setParameter('installments', $value);
    }

    public function getType() {
        return $this->getParameter('type');
    }

    public function setType($value) {
        return $this->setParameter('type', $value);
    }

    public function getOrderId() {
        return $this->getParameter('orderid');
    }

    public function setOrderId($value) {
        return $this->setParameter('orderid', $value);
    }

    public function getExtraPoint() {
        return $this->getParameter('extrapoint');
    }

    public function setExtraPoint($value) {
        return $this->setParameter('extrapoint', $value);
    }

    public function getMultiplePoint() {
        return $this->getParameter('multiplePoint');
    }

    public function setMultiplePoint($value) {
        return $this->setParameter('multiplePoint', $value);
    }

}

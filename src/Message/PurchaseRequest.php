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
    
    protected $endpoint = '';
    protected $endpoints = array(
        'test'       => 'http://setmpos.ykb.com/PosnetWebService/XML',
        'purchase'   => 'https://www.posnet.ykb.com/PosnetWebService/XML',
        '3d'         => 'https://www.posnet.ykb.com/3DSWebService/YKBPaymentService'
    );
 
    protected $currencies = [
        'TRY' => 'YT',
        'USD' => 'US',
        'EUR' => 'EU'
    ];

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

    public function sendData($data) {

        $document = new DOMDocument('1.0', 'UTF-8');
        $root = $document->createElement('posnetRequest');

        $root->appendChild($document->createElement('mid', $this->getMerchantId()));
        $root->appendChild($document->createElement('tid', $this->getTerminalId()));

        // Each array element 
        $ossRequest = $document->createElement($this->getType());
        foreach ($data as $id => $value) {
            $ossRequest->appendChild($document->createElement($id, $value));
        }

        $root->appendChild($ossRequest);
        
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
       
        $xml = "xmldata=".$document->saveXML();
        
        $this->endpoint = $this->getTestMode() ? $this->endpoints['test'] : $this->endpoints['purchase'];
        
        $httpResponse = $this->httpClient->post($this->endpoint, $headers, $xml)->send();

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

    public function getInstallment() {
        return $this->getParameter('installment');
    }

    public function setInstallment($value) {
        return $this->setParameter('installment', $value);
    }

    public function getType() {
        return $this->getParameter('type');
    }

    public function setType($value) {
        return $this->setParameter('type', $value);
    }

    public function getTransId() {
        return $this->getParameter('transId');
    }

    public function setTransId($value) {
        return $this->setParameter('transId', $value);
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

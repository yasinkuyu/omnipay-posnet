<?php

namespace Omnipay\Posnet;

use Omnipay\Common\AbstractGateway;

/**
 * Posnet Gateway
 * 
 * (c) Yasin Kuyu
 * 2015, insya.com
 * http://www.github.com/yasinkuyu/omnipay-posnet
 */
class Gateway extends AbstractGateway {

    public function getName() {
        return 'Posnet';
    }

    public function getDefaultParameters() {
        return array(
            'merchantId' => '',
            'terminalId' => '',
            'installment' => '00',
            'type' => 'sale',
            'currency' => 'TRY'
        );
    }

    public function authorize(array $parameters = array()) {
        return $this->createRequest('\Omnipay\Posnet\Message\AuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array()) {
        return $this->createRequest('\Omnipay\Posnet\Message\CaptureRequest', $parameters);
    }

    public function purchase(array $parameters = array()) {
        return $this->createRequest('\Omnipay\Posnet\Message\PurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array()) {
        return $this->createRequest('\Omnipay\Posnet\Message\RefundRequest', $parameters);
    }

    public function void(array $parameters = array()) {
        return $this->createRequest('\Omnipay\Posnet\Message\VoidRequest', $parameters);
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

    public function getOrderId() {
        return $this->getParameter('orderid');
    }

    public function setOrderId($value) {
        return $this->setParameter('orderid', $value);
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

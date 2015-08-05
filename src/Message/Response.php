<?php

namespace Omnipay\Posnet\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Posnet Response
 *
 * (c) Yasin Kuyu
 * 2015, insya.com
 * http://www.github.com/yasinkuyu/omnipay-posnet
 */
class Response extends AbstractResponse implements RedirectResponseInterface {

    /**
     * Constructor
     *
     * @param  RequestInterface         $request
     * @param  string                   $data / response data
     * @throws InvalidResponseException
     */
    public function __construct(RequestInterface $request, $data) {
        $this->request = $request;
        try {
            $this->data = (array) simplexml_load_string($data);
        } catch (\Exception $ex) {
            throw new InvalidResponseException();
        }
        echo $data;
        die();
    }

    /**
     * Whether or not response is successful
     *
     * @return bool
     */
    public function isSuccessful() {
        if (isset($this->data["approved"])) {
            return (string) $this->data["approved"] === '1';
        } else {
            return false;
        }
    }

    /**
     * Get is redirect
     *
     * @return bool
     */
    public function isRedirect() {
        return false; //todo
    }

    /**
     * Get a code describing the status of this response.
     *
     * @return string|null code
     */
    public function getCode() {
        return $this->isSuccessful() ? $this->data["authCode"] : parent::getCode();
    }

    /**
     * Get transaction reference
     *
     * @return string
     */
    public function getTransactionReference() {

        return $this->isSuccessful() ? $this->data["hostlogkey"] : '';
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage() {
        if ($this->isSuccessful()) {
            $moneyPoints = $this->data["pointInfo"]->pointAmount;
            if (!empty($moneyPoints))
                return (string) $this->data["approved"] . '. Available money points : ' . $moneyPoints;
            else
                return $this->data["approved"] == '1' ? "Approved " : "";
        }
        return $this->data["respText"];
    }

    /**
     * Get error
     *
     * @return string
     */
    public function getError() {
        return $this->data["respText"];
    }

    /**
     * Get Redirect url
     *
     * @return string
     */
    public function getRedirectUrl() {
        if ($this->isRedirect()) {
            $data = array(
                'TransId' => $this->data["hostlogkey"]
            );
            return $this->getRequest()->getEndpoint() . '/test/index?' . http_build_query($data);
        }
    }

    /**
     * Get Redirect method
     *
     * @return POST
     */
    public function getRedirectMethod() {
        return 'POST';
    }

    /**
     * Get Redirect url
     *
     * @return null
     */
    public function getRedirectData() {
        return null;
    }

}

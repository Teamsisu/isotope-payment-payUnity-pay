<?php
if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Isotope eCommerce Workgroup 2009-2012
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */



class PaymentPayUnityPay extends IsotopePayment
{

    /**
	 * processPayment function.
	 *
	 * @access public
	 * @return void
	 */
    public function processPayment()
    {
        
        $objOrder = new IsotopeOrder();
        if (!$objOrder->findBy('cart_id', $this->Isotope->Cart->id)) {
            return false;
        }

        if ($objOrder->date_paid > 0 && $objOrder->date_paid <= time()) {
            IsotopeFrontend::clearTimeout();
            return true;
        }

        if (IsotopeFrontend::setTimeout()) {
            // Do not index or cache the page
            global $objPage;
            $objPage->noSearch = 1;
            $objPage->cache = 0;

            $objTemplate = new FrontendTemplate('mod_message');
            $objTemplate->type = 'processing';
            $objTemplate->message = $GLOBALS['TL_LANG']['MSC']['payment_processing'];
            return $objTemplate->parse();
        }

        $this->log('Payment could not be processed.', __METHOD__, TL_ERROR);
        $this->redirect($this->addToUrl('step=failed', true));
    }

    
    /**
     * Return the PayPal form.
     *
     * @access public
     * @return string
     */
    public function checkoutForm()
    {
                
        $objOrder = new IsotopeOrder();

        if (!$objOrder->findBy('cart_id', $this->Isotope->Cart->id)) {
            $this->redirect($this->addToUrl('step=failed', true));
        }
        
        $objAddress = $this->Isotope->Cart->billingAddress;
        
        
        $payUnityUrl = 'https://' . ($this->payunity_sandbox ? 'test.' : '') . 'payunity.com/frontend/payment.prc';
        
        
        // Build parameters array for transaction init
        $parameters = array(
            
            // User Credentials
            'SECURITY.SENDER'               => $this->payunity_sandbox ? $this->payunity_test_sender_id:$this->payunity_live_sender_id,
            'USER.LOGIN'                    => $this->payunity_sandbox ? $this->payunity_test_user_id:$this->payunity_live_user_id,
            'USER.PWD'                      => $this->payunity_sandbox ? $this->payunity_test_user_pwd:$this->payunity_live_user_pwd,
            'TRANSACTION.CHANNEL'           => $this->payunity_sandbox ? $this->payunity_test_channel_id:$this->payunity_live_channel_id,
            'TRANSACTION.MODE'              => $this->payunity_sandbox ? 'INTEGRATOR_TEST':'LIVE',
            
            
            // Order / Adress Information
            'IDENTIFICATION.TRANSACTIONID'  => $objOrder->id,
            'NAME.GIVEN'                    => $objAddress->firstname,
            'NAME.FAMILY'                   => $objAddress->lastname,
            'ADDRESS.STREET'                => $objAddress->street_1,
            'ADDRESS.ZIP'                   => $objAddress->postal,
            'ADDRESS.CITY'                  => $objAddress->city,
            'ADDRESS.COUNTRY'               => strtoupper($objAddress->country),
            'CONTACT.EMAIL'                 => $objAddress->email,
            
            
            // Standard Configuration
            'REQUEST.VERSION'               => '1.0',
            'FRONTEND.ENABLED'              => 'true',
            'FRONTEND.POPUP'                => 'false',
            'FRONTEND.MODE'                 => 'WPF_PRESELECTION',
            'FRONTEND.LANGUAGE'             => $GLOBALS['TL_LANGUAGE'],
            'FRONTEND.RESPONSE_URL'         => $this->Environment->base . 'system/modules/isotope/postsale.php?mod=pay&id=' . $this->id,
            'FRONTEND.CSS_PATH'             => 'https://mission2pay.com/master_style.css',
            'FRONTEND.JSCRIPT_PATH'         => 'https://mission2pay.com/master_style.js',
            
            
            // Payment Information
            'PAYMENT.CODE'                  => 'CC.DB',
            'PRESENTATION.AMOUNT'           => $objOrder->grandTotal,
            'PRESENTATION.CURRENCY'         => $this->Isotope->Config->currency,
        );

        
        // build the post string for the curl request
        $string = '';
        foreach($parameters AS $key => $value){
            $string .= strtoupper($key).'='.urlencode($value).'&';
        }
        $postString = stripslashes($string);
        
        // make curl request to payUnity
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $payUnityUrl);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_USERAGENT, "php ctpepost");
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postString);
        
        $curlresultURL = curl_exec($curlHandle);
        $curlerror = curl_error($curlHandle);
        $curlinfo = curl_getinfo($curlHandle);
        curl_close($curlHandle);
        
        // parse request from payUnity
        $tmpArray=explode("&",$curlresultURL);
        $parsedReturn = array();
        foreach($tmpArray AS $parameter){
            $tmp = explode('=', $parameter, 2);
            $parsedReturn[$tmp[0]] = urldecode($tmp[1]);
        }
        unset($tmpArray);
        
        // check for errors and log them to contao log
        if($parsedReturn['POST.VALIDATION'] == 'ACK'){
            
            $this->redirect($parsedReturn['FRONTEND.REDIRECT_URL']);
            
        }else{
            $this->log('PayUnity error with code: '.$parsedReturn['POST.VALIDATION'], __METHOD__, TL_ERROR);
            
            // redirect to error page
            $this->redirect($this->addToUrl('step=failed', true));
        }

    }
    
    /**
	 * Process redirects etc.
	 *
	 * @access public
	 * @return void
	 */
	public function processPostSale(){
                
        global $objPage;
        
        if($this->Input->post('PROCESSING_RESULT') == 'ACK'){
            
            $objOrder = new IsotopeOrder();

			if (!$objOrder->findBy('id', $this->Input->post('IDENTIFICATION_TRANSACTIONID')))
			{
				$this->log('Order ID "' . $this->Input->post('IDENTIFICATION_TRANSACTIONID') . '" not found', __METHOD__, TL_ERROR);
				return;
			}
            
            if (!$objOrder->checkout())
			{
				$this->log('PayUnity Pay checkout for Order ID "' . $this->Input->post('IDENTIFICATION_TRANSACTIONID') . '" failed', __METHOD__, TL_ERROR);
				return;
			}
            
            // update paid date and order status
            $objOrder->date_paid = time();
            $objOrder->updateOrderStatus($this->new_order_status);
            $objOrder->save();
            
            $this->log('PayUnity Pay transaction completed for Order: '.$objOrder->order_id, __METHOD__, TL_GENERAL);
            
            // get page details for redirecting
            $objPage = $this->getPageDetails($this->payunity_redirect_success);
            echo $this->Environment->base.$this->generateFrontendUrl($objPage->row());
            
        }else{
            
            // get page details and redirect to checkout failed
            $objPage = $this->getPageDetails($this->payunity_redirect_checkout);
            echo $this->Environment->base.$this->addToUrl('step=failed', true);
            
        }
        
    }
    

}
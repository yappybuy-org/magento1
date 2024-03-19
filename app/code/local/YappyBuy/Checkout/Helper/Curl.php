<?php

class YappyBuy_Checkout_Helper_Curl extends Mage_Core_Helper_Abstract
{

		
	
  public function authenticate()
  {
		return Mage::helper('ybcheckout')->getMerchantKey();
		
  }		


  public function curlRequest($type,$endpoint,$data=null)
  {

		if($authKey=$this->authenticate()){			

			$formattedResponse= null;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, Mage::helper('ybcheckout')->getApiUrl().$endpoint);						
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'accept: */*',
				'Content-Type: application/json;charset=UTF-8',
				'Authorization: Bearer '.$authKey
			]);		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch, CURLINFO_HEADER_OUT, 0);		
			
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
			
			if($type=='POST'){
				curl_setopt($ch, CURLOPT_POST, 1);
				$this->attachRequestBody($ch, $data);
			}
			
			if($type=='PUT'){
			
				$this->attachRequestBody($ch, $data);
			}			
			$responseContent = curl_exec($ch);
			$err      = curl_error($ch);			
	

			$response['headers'] = curl_getinfo($ch);
			$response = $this->setResponseState($response, $responseContent, $ch);	   
		   
			$formattedResponse = $this->formatResponse($response);

			curl_close($ch);

			return $formattedResponse;		
		}
		return false;
  }	



  private function setResponseState($response, $responseContent, $ch)
  {
    if ($responseContent === false) {
        $this->lastError = curl_error($ch);
    } else {
        $headerSize = $response['headers']['header_size'];
        $response['httpHeaders'] = $this->getHeadersAsArray(substr($responseContent, 0, $headerSize));
        $response['body'] = substr($responseContent, $headerSize);
        if (isset($response['headers']['request_header'])) {
            $this->lastRequest['headers'] = $response['headers']['request_header'];
        }
    }

    return $response;
  } 

  private function getHeadersAsArray($headerString)
  {
    $headers = [];
    foreach (explode("\r\n", $headerString) as $i => $line) {
      if ($i === 0) {
          continue;
      }
      $line = trim($line);
      if (empty($line) ) {
          continue;
      }		
      $arr = explode(': ', $line);
			if(isset($arr[1])){
				$headers[$arr[0]] = $arr[1];
			}
    }
    return $headers;
  }

  private function attachRequestBody(&$ch, $data)
  {	
		if($data){
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}
    }

    private function formatResponse($response)
    {        
        if (!empty($response['body'])) {
            return json_decode($response['body'],true);
        }
        return false;
  }		
    
}

<?php


class YappyBuy_Checkout_Helper_Data extends Mage_Core_Helper_Abstract
{

	const URL_LIVE				= "https://checkout-api.yappybuy.com/";		
	const URL_TEST				= "https://checkout-api.dev.yappybuy.com/";
		
	private $settings;
		
	public function getEconomicConfigDataGeneral($value = ''){
		return trim(Mage::getStoreConfig(sprintf('economic/general/%s', $value)));
	}

	public function isEnabled()
	{			
		return Mage::getStoreConfig("yappyBuy/general/enable");
	}

	public function getLocale()
	{			
		$storeId = $this->storeManager->getStore()->getStoreId();	
		return $localeCode =  $this->scopeConfig->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
		
	}

	public function getMerchantLogin()
	{			
		return Mage::getStoreConfig("yappyBuy/general/merchantLogin");
	}
	
	public function getMerchantKey()
	{			
		return 'PFR6WMJTEDYWEW5WRU0FP1B1O8WWFQ10';//Mage::getStoreConfig("yappyBuy/general/apiKey");
	}

	public function isTest()
	{			
		return Mage::getStoreConfig("yappyBuy/general/environment");
	}

	public function getApiUrl()
	{			
		return "https://checkout-api.dev.yappybuy.com/" ;//$this->isTest()==1?self::URL_TEST:($this->isTest()==2?self::URL_LIVE:Mage::getStoreConfig("yappyBuy/general/apiUrl")) ;
	}


	public function getButtonLabel()
	{			
		return Mage::getStoreConfig("yappyBuy/design/label");
	}

	public function getQuickBuyLabel()
	{	
		return Mage::getStoreConfig("yappyBuy/design/quicklabel");
	}

	public function getYbutton()
	{	
		return Mage::getStoreConfig("yappyBuy/design/yIcon");
	}
	
	

	public function getButtonBackground()
	{	
		return str_replace('##','#','#'.Mage::getStoreConfig("yappyBuy/design/background"));
	}

	public function getButtonForeground()
	{			
		return str_replace('##','#','#'.Mage::getStoreConfig("yappyBuy/design/foreground"));
	}	

	public function getButtonRadius()
	{			
		return Mage::getStoreConfig("yappyBuy/design/radius");
	}	
	
	public function getButtonWidth()
	{			
		return Mage::getStoreConfig("yappyBuy/design/width");
	}	

	public function getHideMagento()
	{			
		return Mage::getStoreConfig("yappyBuy/design/hideMagento");
	}	

	public function getAddToCartButtonSelector()
	{	
		return Mage::getStoreConfig("yappyBuy/design/addToCartButtonSelector");
	}	

	public function getShowQuickButton()
	{	
		return Mage::getStoreConfig("yappyBuy/design/showQuickButton");
	}	

	public function getCartButtonSelector()
	{			
		return Mage::getStoreConfig("yappyBuy/design/cartButtonSelector");
	}	

	public function getMiniCartButtonSelector()
	{			
		return Mage::getStoreConfig("yappyBuy/design/miniCartButtonSelector");
	}		

	public function getMode()
	{	
		return Mage::getStoreConfig("yappyBuy/design/mode");
	}		

	public function logData($data){
		Mage::log(print_r($data,true),null,"yappyBuy.log");
	}


	public function registerCart(){
		//Mage::helper('ybcheckout/curl')->
	}

	public function getButton()
	{
		
		$width=(int) $this->getButtonWidth()? (int) $this->getButtonWidth():256;
		$bg=$this->getButtonBackground();
		$fg=$this->getButtonForeground();		
		$label=$this->getButtonLabel()?$this->getButtonLabel():'1-Click Order';
		$corner=(int) $this->getButtonRadius()?(int) $this->getButtonRadius():10;
		
		return str_replace(array('{{width}}','{{arrow}}','{{corner}}','{{bg}}','{{fg}}','{{label}}'),array($width,$width-35,$corner,$bg, $fg, $label),
		'<svg viewBox="0 0 {{width}} 54" xmlns="http://www.w3.org/2000/svg">
  <defs>
	<style>
		.bg{fill:{{bg}};}
		.icon{fill:#882898;}		
		.iconBg{fill:#ffffff;}
		.arrow{fill:{{fg}};}
		.label{fill:{{fg}};font: 25px sans-serif;}
		
	</style>
  </defs>
  <rect class="bg" x="0" width="{{width}}" height="54" rx="{{corner}}" />  
  <g>
	'.($this->getYbutton()?'<rect class="iconBg" x="8.93" y="14.42" width="27.34" height="25.15" rx="4.71"/>':'').'
	<g>
		'.($this->getYbutton()?'<g>
			<path  class="icon" d="M855.49,537.13c.42-1.36,1-3.8,1.23-4.61s.34-1.62.47-2.4a4.74,4.74,0,0,1,2.28-.55,3.09,3.09,0,0,1,1.67.43,1.66,1.66,0,0,1,.66,1.5,9.82,9.82,0,0,1-.2,1.8c-.14.68-.31,1.41-.54,2.17s-.48,1.56-.77,2.38-.61,1.62-.94,2.4-.66,1.53-1,2.23-.68,1.33-1,1.89a21.59,21.59,0,0,1-1.5,2.3,8.8,8.8,0,0,1-1.33,1.4,4,4,0,0,1-1.26.69,5,5,0,0,1-1.33.18,2.69,2.69,0,0,1-2-.72,3,3,0,0,1-.89-1.87,23.88,23.88,0,0,0,1.84-1.6,6.36,6.36,0,0,0,1.67-1.8A20.89,20.89,0,0,0,855.49,537.13Z" transform="translate(-831.89 -512.25)"/>
		</g>':'').'
		<path  class="icon" d="M852.61,532.33l-.23-.78a2.58,2.58,0,0,0-1-1.57,3.12,3.12,0,0,0-1.67-.42,3.76,3.76,0,0,0-2.56,1c.31,1.29.6,2.43.88,3.41s.55,1.87.81,2.64.5,1.44.74,2a22.21,22.21,0,0,0,2.31-4.36C852.12,533.76,852.37,533,852.61,532.33Z" transform="translate(-831.89 -512.25)"/>
		</g>
	</g>
  <path class="arrow" d="m {{arrow}}.78,18.12 8.89,8.88 -8.89,8.89 -2.07,-2.07 6.81,-6.82 -6.81,-6.81 z" />
  <text  x="'.($this->getYbutton()?50:40).'%" y="27"  style="dominant-baseline:central; text-anchor:middle;" class="label">{{label}}</text>
  
</svg>');	
  }

	public function getQuickButton()
	{
		
		$width=(int) $this->getButtonWidth()? (int) $this->getButtonWidth():256;
		$bg=$this->getButtonBackground();
		$fg=$this->getButtonForeground();		
		
		$corner=(int) $this->getButtonRadius()?(int) $this->getButtonRadius():10;
				
		$quickLabel=$this->getQuickBuyLabel()?$this->getQuickBuyLabel():'Quick Buy';

		return str_replace(array('{{width}}','{{arrow}}','{{corner}}','{{bg}}','{{fg}}','{{label}}'),array($width,$width-35,$corner,$bg, $fg, $quickLabel),
'<svg viewBox="0 0 {{width}} 54" xmlns="http://www.w3.org/2000/svg">
<defs>
<style>
.bg{fill:{{bg}};}
.icon{fill:#882898;}		
.iconBg{fill:#ffffff;}
.arrow{fill:{{fg}};}
.label{fill:{{fg}};font: 25px sans-serif;}

</style>
</defs>
<rect class="bg" x="0" width="{{width}}" height="54" rx="{{corner}}" />  
<g>
<rect class="iconBg" x="8.93" y="14.42" width="27.34" height="25.15" rx="4.71"/>
<g>
<g>
	<path  class="icon" d="M855.49,537.13c.42-1.36,1-3.8,1.23-4.61s.34-1.62.47-2.4a4.74,4.74,0,0,1,2.28-.55,3.09,3.09,0,0,1,1.67.43,1.66,1.66,0,0,1,.66,1.5,9.82,9.82,0,0,1-.2,1.8c-.14.68-.31,1.41-.54,2.17s-.48,1.56-.77,2.38-.61,1.62-.94,2.4-.66,1.53-1,2.23-.68,1.33-1,1.89a21.59,21.59,0,0,1-1.5,2.3,8.8,8.8,0,0,1-1.33,1.4,4,4,0,0,1-1.26.69,5,5,0,0,1-1.33.18,2.69,2.69,0,0,1-2-.72,3,3,0,0,1-.89-1.87,23.88,23.88,0,0,0,1.84-1.6,6.36,6.36,0,0,0,1.67-1.8A20.89,20.89,0,0,0,855.49,537.13Z" transform="translate(-831.89 -512.25)"/>
</g>
<path  class="icon" d="M852.61,532.33l-.23-.78a2.58,2.58,0,0,0-1-1.57,3.12,3.12,0,0,0-1.67-.42,3.76,3.76,0,0,0-2.56,1c.31,1.29.6,2.43.88,3.41s.55,1.87.81,2.64.5,1.44.74,2a22.21,22.21,0,0,0,2.31-4.36C852.12,533.76,852.37,533,852.61,532.33Z" transform="translate(-831.89 -512.25)"/>
</g>
</g>
<path class="arrow" d="m {{arrow}}.78,18.12 8.89,8.88 -8.89,8.89 -2.07,-2.07 6.81,-6.82 -6.81,-6.81 z" />
<text  x="50%" y="27"  style="dominant-baseline:central; text-anchor:middle;" class="label">{{label}}</text>

</svg>');	
	}
  
  
	public function getSettings()
	{
		
		
		return array(
			'checkStatusDelay' => 1000,
			'button'=>$this->getButton(),
			'quickButton'=>$thjis->getQuickButton(),
			'addToCartButtonSelector' => $this->getAddToCartButtonSelector()? $this->getAddToCartButtonSelector():'#product-addtocart-button',
			'popUpButtonSelector' => $this->getMiniCartButtonSelector()? $this->getMiniCartButtonSelector():'#top-cart-btn-checkout',
			'cartButtonSelector' => $this->getCartButtonSelector()? $this->getCartButtonSelector():'.checkout-cart-index .action.primary.checkout',							
			'disableMagentoButton'=> $this->getHideMagento()?1:0,			
			'activateLabel'=>'Checkout was activated, click here to return to your checkout window.',
			'mode'=>$this->getMode(),
			'showQuickButton'=>$this->getShowQuickButton(),
			'webUrl'=>$this->getWebUrl()
		);
  }
	

	
}
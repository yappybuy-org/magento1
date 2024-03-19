class ybCheckout {
	
	options = {};
	popupWindow = null;
	buttonPressed = false;
	yappiPopupWin = null;
	
	constructor(configUrl) {
		this._getData(configUrl,this._setOptions.bind(this));
	}

	_setOptions(response){
		this.options = response;				
		this._addHtml();		
		//this._initPopUpButton();					
	}
	
	_initPopUpButton(){
		const popupButton = document.querySelector(this.options.popUpButtonSelector) || false;
		const existingYbButton = document.querySelector('#yappyBuyMinicart') || false;
		
		if(this.popupButtonYB==null && popupButton && !existingYbButton){
			const isPopupButtonDisabled = popupButton.classList.contains('disabled');
			
			this.popupButtonYB=document.createElement('div');
			this.popupButtonYB.setAttribute('id', 'yappyBuyMinicart');					
			this.popupButtonYB.innerHTML = this.options.button;
			popupButton.insertAdjacentElement('afterend', this.popupButtonYB);
			if(this.options.disableMagentoButton==1){
				popupButton.style.display = 'none';
			}else{
				this.popupButtonYB.style.marginTop = '10px';
			}	
			this.popupButtonYB.addEventListener('click', this._activateCheckout.bind(this)); 			
		} 
	}
	
	_addHtml(){				
		if(this.options.mode=='popup'){
			this.popupWindow=document.createElement('div');
			this.popupWindow.setAttribute('class', 'yb-popup-bg');
			this.popupWindow.style.display = 'none';					
			this.popupWindow.innerHTML = 
				'<div  id="yappyBuy-popup" class="content popup-init" ><a style="color:#fff;" href="javascript:void(0);">'+this.options.activateLabel+'</a></div>';
			document.body.appendChild(this.popupWindow);
		}		
		
		const cartButtonYB = document.querySelector('.ybuy-button') || false;
		if(cartButtonYB){
			
/* 			if(this.options.disableMagentoButton==1){
				cartButton.style.display = 'none';						
			}else{
				cartButtonYB.style.marginTop = '10px';
			}	 */			
			cartButtonYB.addEventListener('click', this._activateCheckout.bind(this));
		}

		const quickButtonYB = document.querySelector('.ybuy-product-button') || false;
		if(quickButtonYB){	
			quickButtonYB.addEventListener('click', this._activateCheckout.bind(this));
		}


	}
	
	_activateCheckout(event){
		console.log(event);	
		var isAddAndCall=false;
		if(isAddAndCall=!!event.target.closest('.ybuy-product-button')){
			var productAddToCartForm = new VarienForm('product_addtocart_form');			
			if (!productAddToCartForm.validator.validate()) {
					return;
			}			
			
			
			
		}
		if(!this.buttonPressed){
			this.buttonPressed=true;
			if(this.options.mode=="newtab"){
				this.yappiPopupWin=window.open('', '_blank');
			}else if(this.options.mode=="popup"){
				this.popupWindow.style.display = 'block';
				this.popupWidth=screen.availWidth>1225?980:screen.availWidth * 0.8;
				this.popupLeft=(screen.availWidth-this.popupWidth)/2;					
				
				this.yappiPopupWin=window.open('',
					'yappy-1',
					'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width='+(this.popupWidth)+',height='+(screen.availHeight * 0.8)+',top='+(screen.availHeight * 0.1)+',left='+(this.popupLeft)
				); 						
				
			}
			if(isAddAndCall){
				const params = new FormData(document.querySelector('#product_addtocart_form'));
				this._getData(this.options.addProductUrl, this._showCheckout.bind(this),"POST", params, isAddAndCall);
			}else{
				this._getData(this.options.checkoutUrl,this._showCheckout.bind(this),undefined, undefined,isAddAndCall);
			}
		}		

	}
	
	_ifCheckoutClosed(){
		if(this.yappiPopupWin!=null && this.yappiPopupWin.closed) {
			clearInterval(this.isClosedTimer);
			window.focus();
			document.location.reload();
		}
	}
	
	_reActivateCheckoutWin(){	
		this.yappiPopupWin.focus();				
	}
	
	_showCheckout(response, isAddAndCall){		
		this.buttonPressed=false;
		if(response.status=='success'){
			this.popupUrl=response.url;	
			if(this.options.mode=="newtab"){
				this.yappiPopupWin.location.href=response.url;
			}else if(this.options.mode=="popup"){
				this.isClosedTimer = setInterval(this._ifCheckoutClosed.bind(this), 1000);
				document.querySelector('#yappyBuy-popup a').addEventListener('click', this._reActivateCheckoutWin.bind(this)); 
				this.yappiPopupWin.location.href=response.url;	
			}else{
				document.location.href=response.url;
			}
			
		}else{
			if(this.options.mode=="popup"){
				this.popupWindow.style.display = 'none';
			}
			if(this.yappiPopupWin!=null){
				this.yappiPopupWin.close();
			}
			alert(response.message);
		}		
	}
	
	_getData(url,callBack,method='GET', params=null, ...extraArguments) {
		const xhr = new XMLHttpRequest();
		

		xhr.open(method, url);		
		
		xhr.addEventListener('load', () => {
			const response = JSON.parse(xhr.responseText);
			callBack(response, ...extraArguments);
		});

		xhr.addEventListener('error', () => {
			console.log('error');
		});

		xhr.send(params);
	}  
  
  
  
}



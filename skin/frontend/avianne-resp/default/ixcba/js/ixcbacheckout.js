/**
 * Mageix LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to Mageix LLC's  End User License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mageix.com/index.php/license-guide/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to webmaster@mageix.com so we can send you a copy immediately.
 *
 * DISCLAIMER

 *
 * @category	Mageix
 * @package 	Mageix_Ixcba
 * @copyright   Copyright (c) 2011 Mageix LLC (http://mageix.com)
 * @license	http://mageix.com/index.php/license-guide/  End User License Agreement
 */

/*
 * Magento Mageix IXCBA Module
 *
 * @category   Checkout & Payments
 * @package	Mageix_Ixcba
 * @copyright  Copyright (c) 2012 Mageix LLC (http://mageix.com)
  *@licence 	http://mageix.com/index.php/license-guide/ 
 */

var Ixcba = Class.create();
Ixcba.prototype = {
    initialize: function(accordion, urls){
		if(globalaccordion != '')
        this.accordion = globalaccordion;
		else
		this.accordion = accordion;
        this.progressUrl = urls.progress;
        this.reviewUrl = urls.review;
        this.saveMethodUrl = urls.saveMethod;
        this.failureUrl = urls.failure;
        this.billingForm = false;
        this.shippingForm= false;
        this.syncBillingShipping = false;
        this.method = '';
        this.payment = '';
        this.loadWaiting = false;
        this.steps = ['login', 'orderitems', 'billing', 'shipping', 'shipping_method', 'payment', 'review'];
        this.accordion.disallowAccessToNextSections = true;
    },

    ajaxFailure: function(){
        location.href = this.failureUrl;
    },

    reloadProgressBlock: function(){
        var updater = new Ajax.Updater('checkout-progress-wrapper', this.progressUrl, {method: 'get', onFailure: this.ajaxFailure.bind(this)});
    },

    reloadReviewBlock: function(){
        var updater = new Ajax.Updater('checkout-review-load', this.reviewUrl, {method: 'get', onFailure: this.ajaxFailure.bind(this)});
    },

    _disableEnableAll: function(element, isDisabled) {
        var descendants = element.descendants();
        for (var k in descendants) {
            descendants[k].disabled = isDisabled;
        }
        element.disabled = isDisabled;
    },

    setLoadWaiting: function(step, keepDisabled) {
        if (step) {
            if (this.loadWaiting) {
                this.setLoadWaiting(false);
            }
            var container = $(step+'-buttons-container');
            container.addClassName('disabled');
            container.setStyle({opacity:.5});
            this._disableEnableAll(container, true);
            Element.show(step+'-please-wait');
        } else {
            if (this.loadWaiting) {
                var container = $(this.loadWaiting+'-buttons-container');
                var isDisabled = (keepDisabled ? true : false);
                if (!isDisabled) {
                    container.removeClassName('disabled');
                    container.setStyle({opacity:1});
                }
                this._disableEnableAll(container, isDisabled);
                Element.hide(this.loadWaiting+'-please-wait');
            }
        }
        this.loadWaiting = step;
    },

    gotoSection: function(section)
    {
		if(document.getElementById('ixcba-'+section)) {
			section = $('ixcba-'+section);
		}else{
			try {
				if(section == 'shipping_method') {
					accordion.closeExistingSection();
				}else if(section == 'review') {
					accordion.closeExistingSection();
				}
			} catch (e) {}
			section = $('opc-'+section);
		}
        section.addClassName('allow');
		this.accordion.openSection(section);
    },
	
	methodSuccess : function(data){
		responseJson = $jQueryconflict.parseJSON(data.responseText);
		
		if (responseJson['emailinfo']){
			emailinfo = responseJson['emailinfo'];
			$jQueryconflict("#UserEmailHead").html(emailinfo["UserEmailHead"]);
			$jQueryconflict("#UserEmailInstruction").html(emailinfo["UserEmailInstruction"]);
			$jQueryconflict("#UserEmailInfo").html(emailinfo["UserEmailInfo"]);
			
			fields = emailinfo["UserEmailFields"];
			htm = '<ul class="form-list">';
			
			for (var field in fields){
			
				htm +="<li>\
						 <label for='"+field+"' class='required'><em>*</em>"+fields[field]['title']+"</label>\
									<div class='input-box'>\
									<input type='"+fields[field]['type']+"'  class='input-text required-entry validate-email' id='error-"+field+"' name='"+field+"' value='"+fields[field]['value']+"' />\
									</div>\
								</li>";
								
				/*htm += "<div class='form-element'>\
							<label for='"+field+"'>"+fields[field]['title']+" :</label>\
							<span><input id='"+field+"' type='"+fields[field]['type']+"' name='"+field+"'  value='"+fields[field]['value']+"'/></span>\
						</div>";*/					
			}
			
			htm += "</ul>";
			
			$jQueryconflict("#UserEmailFields").html(htm);
		}
		reCalculateAmazon();
		checkout.gotoSection('orderitems');
	},
	setEmails: function(){
		return true;
		email = $jQueryconflict("#guest-email").val();
		password = $jQueryconflict("#guest-password").val();
		
		if (email == ""){
			alert("Fill in email address first");
			return false;
		}
		
		if (this.method == "register" && password == ""){
			alert("Fill in password first");
			return false;
		}
		
	
		this.guestEmail = email;
		this.guestPassword = password;
		return true;
	},
    setMethod: function(){
        if($('ixcbalogin:guest') || $('ixcbalogin:register')) {
			if ($('ixcbalogin:guest') && $('ixcbalogin:guest').checked) {
				this.method = 'guest';
				/*
				if (!this.setEmails()){
					return;
				}
				*/
				
				var request = new Ajax.Request(
					this.saveMethodUrl,
					{method: 'post', onFailure: this.ajaxFailure.bind(this),onSuccess : this.methodSuccess, parameters: {method:'guest'}}
				);
				//Element.hide('register-customer-password');
				
				this.gotoSection('orderitems');
			}
			else if($('ixcbalogin:register') && ($('ixcbalogin:register').checked || $('ixcbalogin:register').type == 'hidden')) {
				this.method = 'register';
				/*
				if (!this.setEmails()){
					return;
				}
				*/
				
				
				var request = new Ajax.Request(
					this.saveMethodUrl,
					{method: 'post', onFailure: this.ajaxFailure.bind(this),onSuccess : this.methodSuccess, parameters: {method:'register'}}
				);
				//Element.show('register-customer-password');
			   
				this.gotoSection('orderitems');
			}
			else{
				if ($('ixcbalogin:guest'))
				alert(Translator.translate('Please choose to register or to checkout as a guest'));
				else
				alert(Translator.translate('Please choose to register.'));
				return false;
			}
		}else{
			if ($('login:guest') && $('login:guest').checked) {
				this.method = 'guest';
				var request = new Ajax.Request(
					this.saveMethodUrl,
					{method: 'post', onFailure: this.ajaxFailure.bind(this), parameters: {method:'guest'}}
				);
				Element.hide('register-customer-password');
				this.gotoSection('billing');
			}
			else if($('login:register') && ($('login:register').checked || $('login:register').type == 'hidden')) {
				this.method = 'register';
				var request = new Ajax.Request(
					this.saveMethodUrl,
					{method: 'post', onFailure: this.ajaxFailure.bind(this), parameters: {method:'register'}}
				);
				Element.show('register-customer-password');
				this.gotoSection('billing');
			}
			else{
				alert(Translator.translate('Please choose to register or to checkout as a guest'));
				return false;
			}
		}
    },
	
	setOrderitems: function() {
        this.gotoSection('billing');
    },

    setBilling: function() {
        if (($('billing:use_for_shipping_yes')) && ($('billing:use_for_shipping_yes').checked)) {
            shipping.syncWithBilling();
			if(document.getElementById('ixcba-shipping')) {
				$('ixcba-shipping').addClassName('allow');
			}else{
				$('opc-shipping').addClassName('allow');
			}
            this.gotoSection('shipping_method');
        } else if (($('billing:use_for_shipping_no')) && ($('billing:use_for_shipping_no').checked)) {
            $('shipping:same_as_billing').checked = false;
            this.gotoSection('shipping');
        } else {
            $('shipping:same_as_billing').checked = true;
            this.gotoSection('shipping');
        }
		this.reloadProgressBlock();
    },

    setShipping: function() {
		this.reloadProgressBlock();
        this.gotoSection('shipping_method');
    },

    setShippingMethod: function() {
		this.reloadProgressBlock();
        this.gotoSection('payment');
    },

    setPayment: function() {
		this.reloadProgressBlock();
        this.gotoSection('review');
    },

    setReview: function() {
        this.reloadProgressBlock();
    },

    back: function(){
        if (this.loadWaiting) return;
        this.accordion.openPrevSection(true);
    },

    setStepResponse: function(response){
        if (response.update_section) {
            $('checkout-'+response.update_section.name+'-load').update(response.update_section.html);
        }
        if (response.allow_sections) {
            response.allow_sections.each(function(e){
				if(document.getElementById('ixcba-'+e)) {
					$('ixcba-'+e).addClassName('allow');
				}else{
					$('opc-'+e).addClassName('allow');
				}
            });
        }

        if(response.duplicateBillingInfo)
        {
            shipping.setSameAsBilling(true);
        }

        if (response.goto_section) {
			this.reloadProgressBlock();
            this.gotoSection(response.goto_section);
            return true;
        }
        if (response.redirect) {
            location.href = response.redirect;
            return true;
        }
        return false;
    }
}


var Billing = Class.create();
Billing.prototype = {
    initialize: function(form, addressUrl, saveUrl){
        this.form = form;
        if ($(this.form)) {
            $(this.form).observe('submit', function(event){this.save();Event.stop(event);}.bind(this));
        }
        this.addressUrl = addressUrl;
        this.saveUrl = saveUrl;
        this.onAddressLoad = this.fillForm.bindAsEventListener(this);
        this.onSave = this.nextStep.bindAsEventListener(this);
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
    },

    setAddress: function(addressId){
        if (addressId) {
            request = new Ajax.Request(
                this.addressUrl+addressId,
                {method:'get', onSuccess: this.onAddressLoad, onFailure: checkout.ajaxFailure.bind(checkout)}
            );
        }
        else {
            this.fillForm(false);
        }
    },

    newAddress: function(isNew){
        if (isNew) {
            this.resetSelectedAddress();
            Element.show('billing-new-address-form');
        } else {
            Element.hide('billing-new-address-form');
        }
    },

    resetSelectedAddress: function(){
        var selectElement = $('billing-address-select')
        if (selectElement) {
            selectElement.value='';
        }
    },

    fillForm: function(transport){
        var elementValues = {};
        if (transport && transport.responseText){
            try{
                elementValues = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                elementValues = {};
            }
        }
        else{
            this.resetSelectedAddress();
        }
        arrElements = Form.getElements(this.form);
        for (var elemIndex in arrElements) {
            if (arrElements[elemIndex].id) {
                var fieldName = arrElements[elemIndex].id.replace(/^billing:/, '');
                arrElements[elemIndex].value = elementValues[fieldName] ? elementValues[fieldName] : '';
                if (fieldName == 'country_id' && billingForm){
                    billingForm.elementChildLoad(arrElements[elemIndex]);
                }
            }
        }
    },

    setUseForShipping: function(flag) {
        $('shipping:same_as_billing').checked = flag;
    },

    save: function(){
        if (checkout.loadWaiting!=false) return;

        var validator = new Validation(this.form);
        if (validator.validate()) {
            checkout.setLoadWaiting('billing');

            var request = new Ajax.Request(
                this.saveUrl,
                {
                    method: 'post',
                    onComplete: this.onComplete,
                    onSuccess: this.onSave,
                    onFailure: checkout.ajaxFailure.bind(checkout),
                    parameters: Form.serialize(this.form)
                }
            );
        }
    },

    resetLoadWaiting: function(transport){
        checkout.setLoadWaiting(false);
    },

    nextStep: function(transport){
        if (transport && transport.responseText){
            try{
                response = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                response = {};
            }
        }

        if (response.error){
            if ((typeof response.message) == 'string') {
                alert(response.message);
            } else {
                if (window.billingRegionUpdater) {
                    billingRegionUpdater.update();
                }

                alert(response.message.join("\n"));
            }

            return false;
        }

        checkout.setStepResponse(response);
        payment.initWhatIsCvvListeners();
    }
}

var Shipping = Class.create();
Shipping.prototype = {
    initialize: function(form, addressUrl, saveUrl, methodsUrl){
        this.form = form;
        if ($(this.form)) {
            $(this.form).observe('submit', function(event){this.save();Event.stop(event);}.bind(this));
        }
        this.addressUrl = addressUrl;
        this.saveUrl = saveUrl;
        this.methodsUrl = methodsUrl;
        this.onAddressLoad = this.fillForm.bindAsEventListener(this);
        this.onSave = this.nextStep.bindAsEventListener(this);
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
    },

    setAddress: function(addressId){
        if (addressId) {
            request = new Ajax.Request(
                this.addressUrl+addressId,
                {method:'get', onSuccess: this.onAddressLoad, onFailure: checkout.ajaxFailure.bind(checkout)}
            );
        }
        else {
            this.fillForm(false);
        }
    },

    newAddress: function(isNew){
        if (isNew) {
            this.resetSelectedAddress();
            Element.show('shipping-new-address-form');
        } else {
            Element.hide('shipping-new-address-form');
        }
        shipping.setSameAsBilling(false);
    },

    resetSelectedAddress: function(){
        var selectElement = $('shipping-address-select')
        if (selectElement) {
            selectElement.value='';
        }
    },

    fillForm: function(transport){
        var elementValues = {};
        if (transport && transport.responseText){
            try{
                elementValues = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                elementValues = {};
            }
        }
        else{
            this.resetSelectedAddress();
        }
        arrElements = Form.getElements(this.form);
        for (var elemIndex in arrElements) {
            if (arrElements[elemIndex].id) {
                var fieldName = arrElements[elemIndex].id.replace(/^shipping:/, '');
                arrElements[elemIndex].value = elementValues[fieldName] ? elementValues[fieldName] : '';
                if (fieldName == 'country_id' && shippingForm){
                    shippingForm.elementChildLoad(arrElements[elemIndex]);
                }
            }
        }
    },

    setSameAsBilling: function(flag) {
        $('shipping:same_as_billing').checked = flag;
        if (flag) {
            this.syncWithBilling();
        }
    },

    syncWithBilling: function () {
        $('billing-address-select') && this.newAddress(!$('billing-address-select').value);
        $('shipping:same_as_billing').checked = true;
        if (!$('billing-address-select') || !$('billing-address-select').value) {
            arrElements = Form.getElements(this.form);
            for (var elemIndex in arrElements) {
                if (arrElements[elemIndex].id) {
                    var sourceField = $(arrElements[elemIndex].id.replace(/^shipping:/, 'billing:'));
                    if (sourceField){
                        arrElements[elemIndex].value = sourceField.value;
                    }
                }
            }
            shippingRegionUpdater.update();
            $('shipping:region_id').value = $('billing:region_id').value;
            $('shipping:region').value = $('billing:region').value;
        } else {
            $('shipping-address-select').value = $('billing-address-select').value;
        }
    },

    setRegionValue: function(){
        $('shipping:region').value = $('billing:region').value;
    },

    save: function(){
        if (checkout.loadWaiting!=false) return;
        var validator = new Validation(this.form);
        if (validator.validate()) {
            checkout.setLoadWaiting('shipping');
            var request = new Ajax.Request(
                this.saveUrl,
                {
                    method:'post',
                    onComplete: this.onComplete,
                    onSuccess: this.onSave,
                    onFailure: checkout.ajaxFailure.bind(checkout),
                    parameters: Form.serialize(this.form)
                }
            );
        }
    },

    resetLoadWaiting: function(transport){
        checkout.setLoadWaiting(false);
    },

    nextStep: function(transport){
        if (transport && transport.responseText){
            try{
                response = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                response = {};
            }
        }
        if (response.error){
            if ((typeof response.message) == 'string') {
                alert(response.message);
            } else {
                if (window.shippingRegionUpdater) {
                    shippingRegionUpdater.update();
                }
                alert(response.message.join("\n"));
            }

            return false;
        }

        checkout.setStepResponse(response);
    }
}

var ShippingMethod = Class.create();
ShippingMethod.prototype = {
    initialize: function(form, saveUrl){
        this.form = form;
        if ($(this.form)) {
            $(this.form).observe('submit', function(event){this.save();Event.stop(event);}.bind(this));
        }
        this.saveUrl = saveUrl;
        this.validator = new Validation(this.form);
        this.onSave = this.nextStep.bindAsEventListener(this);
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
    },

    validate: function() {
        var methods = document.getElementsByName('shipping_method');
        if (methods.length==0) {
            alert(Translator.translate('Your order cannot be completed at this time as there is no shipping methods available for it. Please make necessary changes in your shipping address.'));
            return false;
        }

        if(!this.validator.validate()) {
            return false;
        }

        for (var i=0; i<methods.length; i++) {
            if (methods[i].checked) {
                return true;
            }
        }
        alert(Translator.translate('Please specify shipping method.'));
        return false;
    },

    save: function(){

        if (checkout.loadWaiting!=false) return;
        if (this.validate()) {
            checkout.setLoadWaiting('shipping-method');
            var request = new Ajax.Request(
                this.saveUrl,
                {
                    method:'post',
                    onComplete: this.onComplete,
                    onSuccess: this.onSave,
                    onFailure: checkout.ajaxFailure.bind(checkout),
                    parameters: Form.serialize(this.form)
                }
            );
        }
    },

    resetLoadWaiting: function(transport){
        checkout.setLoadWaiting(false);
    },

    nextStep: function(transport){
        if (transport && transport.responseText){
            try{
                response = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                response = {};
            }
        }

        if (response.error) {
            alert(response.message);
            return false;
        }

        if (response.update_section) {
            $('checkout-'+response.update_section.name+'-load').update(response.update_section.html);
        }

        payment.initWhatIsCvvListeners();

        if (response.goto_section) {
            checkout.gotoSection(response.goto_section);
			checkout.reloadProgressBlock();
            return;
        }

        if (response.payment_methods_html) {
            $('checkout-payment-method-load').update(response.payment_methods_html);
        }

        checkout.setShippingMethod();
    }
}

var Payment = Class.create();
Payment.prototype = {
    beforeInitFunc:$H({}),
    afterInitFunc:$H({}),
    beforeValidateFunc:$H({}),
    afterValidateFunc:$H({}),
    initialize: function(form, saveUrl){
        this.form = form;
        this.saveUrl = saveUrl;
        this.onSave = this.nextStep.bindAsEventListener(this);
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
    },

    addBeforeInitFunction : function(code, func) {
        this.beforeInitFunc.set(code, func);
    },

    beforeInit : function() {
        (this.beforeInitFunc).each(function(init){
           (init.value)();;
        });
    },

    init : function () {
        this.beforeInit();
        var elements = Form.getElements(this.form);
        if ($(this.form)) {
            $(this.form).observe('submit', function(event){this.save();Event.stop(event);}.bind(this));
        }
        var method = null;
        for (var i=0; i<elements.length; i++) {
            if (elements[i].name=='payment[method]') {
                if (elements[i].checked) {
                    method = elements[i].value;
                }
            } else {
                elements[i].disabled = true;
            }
            elements[i].setAttribute('autocomplete','off');
        }
        if (method) this.switchMethod(method);
        this.afterInit();
    },

    addAfterInitFunction : function(code, func) {
        this.afterInitFunc.set(code, func);
    },

    afterInit : function() {
        (this.afterInitFunc).each(function(init){
            (init.value)();
        });
    },

    switchMethod: function(method){
        if (this.currentMethod && $('payment_form_'+this.currentMethod)) {
            this.changeVisible(this.currentMethod, true);
            $('payment_form_'+this.currentMethod).fire('payment-method:switched-off', {method_code : this.currentMethod});
        }
        if ($('payment_form_'+method)){
            this.changeVisible(method, false);
            $('payment_form_'+method).fire('payment-method:switched', {method_code : method});
        } else {
            document.body.fire('payment-method:switched', {method_code : method});
        }
        if (method) {
            this.lastUsedMethod = method;
        }
        this.currentMethod = method;
    },

    changeVisible: function(method, mode) {
        var block = 'payment_form_' + method;
        [block + '_before', block, block + '_after'].each(function(el) {
            element = $(el);
            if (element) {
                element.style.display = (mode) ? 'none' : '';
                element.select('input', 'select', 'textarea', 'button').each(function(field) {
                    field.disabled = mode;
                });
            }
        });
    },

    addBeforeValidateFunction : function(code, func) {
        this.beforeValidateFunc.set(code, func);
    },

    beforeValidate : function() {
        var validateResult = true;
        var hasValidation = false;
        (this.beforeValidateFunc).each(function(validate){
            hasValidation = true;
            if ((validate.value)() == false) {
                validateResult = false;
            }
        }.bind(this));
        if (!hasValidation) {
            validateResult = false;
        }
        return validateResult;
    },

    validate: function() {
        var result = this.beforeValidate();
        if (result) {
            return true;
        }
        var methods = document.getElementsByName('payment[method]');
        if (methods.length==0) {
            alert(Translator.translate('Your order cannot be completed at this time as there is no payment methods available for it.'));
            return false;
        }
        for (var i=0; i<methods.length; i++) {
            if (methods[i].checked) {
                return true;
            }
        }
        result = this.afterValidate();
        if (result) {
            return true;
        }
        alert(Translator.translate('Please specify payment method.'));
        return false;
    },

    addAfterValidateFunction : function(code, func) {
        this.afterValidateFunc.set(code, func);
    },

    afterValidate : function() {
        var validateResult = true;
        var hasValidation = false;
        (this.afterValidateFunc).each(function(validate){
            hasValidation = true;
            if ((validate.value)() == false) {
                validateResult = false;
            }
        }.bind(this));
        if (!hasValidation) {
            validateResult = false;
        }
        return validateResult;
    },

    save: function(){
        if (checkout.loadWaiting!=false) return;
        var validator = new Validation(this.form);
        if (this.validate() && validator.validate()) {
            checkout.setLoadWaiting('payment');
            var request = new Ajax.Request(
                this.saveUrl,
                {
                    method:'post',
                    onComplete: this.onComplete,
                    onSuccess: this.onSave,
                    onFailure: checkout.ajaxFailure.bind(checkout),
                    parameters: Form.serialize(this.form)
                }
            );
        }
    },

    resetLoadWaiting: function(){
        checkout.setLoadWaiting(false);
    },

    nextStep: function(transport){
        if (transport && transport.responseText){
            try{
                response = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                response = {};
            }
        }

        if (response.error) {
            if (response.fields) {
                var fields = response.fields.split(',');
                for (var i=0;i<fields.length;i++) {
                    var field = null;
                    if (field = $(fields[i])) {
                        Validation.ajaxError(field, response.error);
                    }
                }
                return;
            }
            alert(response.error);
            return;
        }

        checkout.setStepResponse(response);
    },

    initWhatIsCvvListeners: function(){
        $$('.cvv-what-is-this').each(function(element){
            Event.observe(element, 'click', toggleToolTip);
        });
    }
}

var Review = Class.create();
Review.prototype = {
    initialize: function(saveUrl, successUrl, agreementsForm){
        this.saveUrl = saveUrl;
        this.successUrl = successUrl;
        this.agreementsForm = agreementsForm;
        this.onSave = this.nextStep.bindAsEventListener(this);
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
    },

    save: function(){
        if (checkout.loadWaiting!=false) return;
        checkout.setLoadWaiting('review');
        //var params = Form.serialize(payment.form);
        //if (this.agreementsForm) {
        //   params += '&'+Form.serialize(this.agreementsForm);
        //}
        //params.save = true;
		var params = '';
        var request = new Ajax.Request(
            this.saveUrl,
            {
                method:'post',
                parameters:params,
                onComplete: this.onComplete,
                onSuccess: this.onSave,
                onFailure: checkout.ajaxFailure.bind(checkout)
            }
        );
    },

    resetLoadWaiting: function(transport){
        checkout.setLoadWaiting(false, this.isSuccess);
    },

    nextStep: function(transport){
        if (transport && transport.responseText) {
            try{
                response = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                response = {};
            }
            if (response.redirect) {
                this.isSuccess = true;
                location.href = response.redirect;
                return;
            }
            if (response.success) {
                this.isSuccess = true;
                window.location=this.successUrl;
            }
            else{
                var msg = response.error_messages;
                if (typeof(msg)=='object') {
                    msg = msg.join("\n");
                }
                if (msg) {
                    alert(msg);
                }
            }

            if (response.update_section) {
                $('checkout-'+response.update_section.name+'-load').update(response.update_section.html);
            }

            if (response.goto_section) {
                checkout.gotoSection(response.goto_section);
                checkout.reloadProgressBlock();
            }
        }
    },

    isSuccess: false
}


var checkHanleClose = '';var checkHanleCloseOnlySuccess = '';var yesvarexistsornot = ''; var notlcoseexistance = ''; var yesopenexistance = '';
function checkExistanceVar() { for(var A in CBA.Widgets.mediator.registry) { yesvarexistsornot = 'loaded'; var B=CBA.Widgets.mediator.registry[A];
if(typeof (B.popupWindow)!="undefined"&&typeof (B.popupWindowName)!="undefined"&&!B.popupWindow.closed) { yesopenexistance = 'yes'; } else { setTimeout("checkExistanceVar()",2500); } }	 if(yesvarexistsornot == '') { setTimeout("checkExistanceVar()", 2500); } if(yesopenexistance == 'yes') { notlcoseexistance = ''; setTimeout("isExistanceVarClose('checkExistanceVar')", 2500); yesopenexistance = ''; } }
function isExistanceVarClose(valueget) { for(var A in CBA.Widgets.mediator.registry) { var B=CBA.Widgets.mediator.registry[A];
if(typeof (B.popupWindow)!="undefined"&&typeof (B.popupWindowName)!="undefined"&&!B.popupWindow.closed) {} else { notlcoseexistance = 'yes'; yesvarexistsornot = ''; accordion.openSection('ixcba-orderitems'); } } if(notlcoseexistance == '') {setTimeout("isExistanceVarClose('isExistanceVarClose')", 2500);} }
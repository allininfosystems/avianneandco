var Dialog={settings:{overlayBG:'black',overlayOpacity:0.6,titleBG:'#ccc',dialogBG:'#eee',dialogContainerBG:'#333',dialogOpacity:0.75,cancelWhenOverlayIsClicked:false}};Dialog.Core=Class.create({uniqueId:0,dlgObject:null,_options:{},initialize:function(options){this._options={title:'Dialog',zIndex:32750,modal:false,onLoad:null};Object.extend(this._options,options||{});this.uniqueId=Math.ceil(Math.random()*4294967296);},isModal:function(){return this._options.modal;},show:function(options){var opt={msg:'{unset}',modal:true,onButtonClick:null};Object.extend(opt,options||{});var body=Builder.node('p',{},opt.msg);if(opt.modal){this._showModalFrame(body);}else{this._showFrame(body);};},cancel:function(){this.hide();},hide:function(){this.dlgObject.hide();Dialog.unregister(this.uniqueId);},_clickButton:function(num){if(Object.isFunction(this._options.onButtonClick))if(!this._options.onButtonClick(this,num))return;switch(num){case 0:this.cancel();break;case 1:this.hide();break;case 2:}},_showModalFrame:function(node){this._options.modal=true;this._showFrame(node);},_showFrame:function(node){if(this._options.modal){document.body.appendChild(this._getOverlayNode());Effect.Appear('modalDlgOverlay',{from:0.0,to:Dialog.settings.overlayOpacity});};var dlg=this._getFrameNode(node);this.dlgObject=dlg;document.body.appendChild(dlg);new Draggable(dlg,{handle:$('dlgHeader-'+this.uniqueId)});dlg.show();Dialog.register(this);if(Object.isFunction(this._options.onLoad)){this._options.onLoad(this);};},_getOverlayNode:function(){var olset=$$('div#modalDlgOverlay');if(olset.length>0){var overlay=olset[0];}else{var set={id:'modalDlgOverlay',style:'display:none;position:fixed;left:0;top:0;width:100%;height:100%;z-index:32700;background:'+Dialog.settings.overlayBG+';opacity:'+Dialog.settings.overlayOpacity+';'};if(Dialog.settings.cancelWhenOverlayIsClicked){set.onclick='Dialog.cancelAll();';};overlay=Builder.node('div',set);};return overlay;},_getFrameNode:function(node){var dlg=Builder.node('div',{className:'DlgContainer',id:'dialog-'+this.uniqueId,style:'position:fixed;top:20%;left:35%;width:550px;height:auto;z-index:'+this._options.zIndex+';margin:auto;background:'+Dialog.settings.dialogContainerBG+';opacity:'+Dialog.settings.dialogOpacity+';'});var frame=Builder.node('div',{className:'DlgFrame',id:'dlgFrm-'+this.uniqueId,style:'background:'+Dialog.settings.dialogBG+';opacity:1.0;min-width:26em;'},[Builder.node('div',{className:'DlgHeader',id:'dlgHeader-'+this.uniqueId,style:'cursor:move;background:'+Dialog.settings.titleBG+';'},[Builder.node('span',{className:'DlgClosebox',id:'dlgCloseBox-'+this.uniqueId,onclick:'Dialog.cancel('+this.uniqueId+');',style:'float:right;cursor:pointer;'},'×'),this._options.title]),node]);dlg.appendChild(frame);return dlg;}});Dialog.Simple=Class.create(Dialog.Core,{initialize:function($super,settings){$super(settings);},_form:null,show:function(msg,options){var opt={title:'Dialog',detail:null,input:null,button0:null,button1:'OK',button2:null,modal:true,onLoad:function(dlg){if(dlg._form){Element.extend(dlg._form).focusFirstElement();};},onButtonClick:function(dlg,num){if(num==0){dlg.cancel();}else if(num==1){dlg.confirm();}else{dlg.otherButton()}},onCancel:null,onConfirm:null,onOtherButton:null};Object.extend(opt,(options||{}));Object.extend(this._options,opt);msg=(Object.isUndefined(msg)?this._options.msg:msg);var node=Builder.node('div',{className:'DlgBody',style:'padding:.5em;'},[Builder.node('p',{className:'DlgMainText'},msg)]);if(this._options.detail){node.appendChild(Builder.node('p',{className:'DlgDetailText'},opt.detail));};var form=Builder.node('form',{onsubmit:'return false;'});this._form=form;if(!Object.isUndefined(opt.input)&&opt.input!=null){form.appendChild(Builder.node('input',{type:'text',id:'dlgTextField-'+this.uniqueId,value:opt.input}));};var btn1Style=((opt.button0==null&&opt.button2==null)?'':'float:right;');var areaStyle=((opt.button0==null&&opt.button2==null)?'text-align:right;':'');var btnArea=Builder.node('div',{className:'DlgButtonArea',style:'width:90%;margin-left:auto; margin-right:auto;'+areaStyle});if(opt.button1){btnArea.appendChild(Builder.node('input',{className:'DlgButton1',type:'submit',onclick:'Dialog.getById('+this.uniqueId+')._clickButton(1);return false;',value:opt.button1,style:btn1Style}));};if(opt.button0){btnArea.appendChild(Builder.node('input',{className:'DlgButton0',type:'reset',onclick:'Dialog.getById('+this.uniqueId+')._clickButton(0);return false;',value:opt.button0}));};if(opt.button2){btnArea.appendChild(Builder.node('input',{className:'DlgButton2',type:'button',onclick:'Dialog.getById('+this.uniqueId+')._clickButton(2);return false;',value:opt.button2,style:'margin-left:2em;margin-right:2em;'}));};form.appendChild(btnArea);node.appendChild(form);this._showFrame(node);},getText:function(){if(!Object.isUndefined(this._options.input)){return $F('dlgTextField-'+this.uniqueId);}else{return null;};},cancel:function(){if(Object.isFunction(this._options.onCancel)){var r=this._options.onCancel(this);if(!Object.isUndefined(r)&&!r){return;};};this.hide();},confirm:function(){if(Object.isFunction(this._options.onConfirm)){var r=this._options.onConfirm(this);if(!Object.isUndefined(r)&&!r){return;};};this.hide();},otherButton:function(){if(Object.isFunction(this._options.onOtherButton))this._options.onOtherButton(this);}});Dialog._registry=$H();Dialog.getById=function(uid){var dlg=Dialog._registry.get(uid)
return dlg;};Dialog.register=function(dc){Dialog._registry.set(dc.uniqueId,dc);};Dialog.unregister=function(uid){var obj=Dialog._registry.unset(uid);var elements=$$('body>#dialog-'+obj.uniqueId)
elements.each(function(it){document.body.removeChild(it);});var overlays=$$('#modalDlgOverlay');if(overlays.size()>0){var removeOverlay=(Dialog._registry.size()==0);if(!removeOverlay){Dialog._registry.each(function(dlg){if(!dlg.value.isModal()){removeOverlay=true;throw $break;}})};if(removeOverlay){overlays.each(function(item){document.body.removeChild(item);});};};};Dialog.cancel=function(uid){var dlg=Dialog._registry.get(uid);dlg.cancel();};Dialog.cancelAll=function(){var dlgs=Dialog._registry.values();dlgs.each(function(item){item.cancel();});},Dialog.alert=function(msg,options){var simpleDlg=new Dialog.Simple();var opt={title:'Alert Dialog',detail:null,input:null,button0:null,button1:'OK',button2:null,modal:true,onButtonClick:function(dlg,num){dlg.confirm();}};Object.extend(opt,(options||{}));simpleDlg.show(msg,opt);};Dialog.confirm=function(msg,options){var simpleDlg=new Dialog.Simple();var opt={title:'Confirm Dialog',detail:null,input:null,button0:'Cancel',button1:'OK',button2:null,modal:true,onCancel:null,onConfirm:null,onOtherButton:null};Object.extend(opt,(options||{}));simpleDlg.show(msg,opt);return simpleDlg;};Dialog.prompt=function(msg,options){var simpleDlg=new Dialog.Simple();var opt={title:'Prompt Dialog',detail:null,input:'',button0:'Cancel',button1:'OK',button2:null,modal:true,onButtonClick:function(dlg,num){if(num==0){dlg.cancel();}else if(num==1){dlg.confirm();}else{dlg.otherButton();};},onCancel:null,onConfirm:null,onOtherButton:null};Object.extend(opt,(options||{}));simpleDlg.show(msg,opt);return simpleDlg;};
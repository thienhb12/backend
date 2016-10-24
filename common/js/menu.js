window.onload = mladdevents;
			function mladdevents(){
				if(window.mlrunShim == true){
					var Iframe = document.createElement("iframe");
					Iframe.setAttribute("src","about:blank");
					Iframe.setAttribute("scrolling","no");
					Iframe.setAttribute("frameBorder","0");
					Iframe.style.zIndex = "2";
					Iframe.style.filter = 'alpha(opacity=0)';
				}
				var effects_a = new Array();
				var divs = document.getElementsByTagName('div');
				for(var j=0;j<divs.length;j++){
					if(divs[j].className.indexOf('mlmenu') != -1){
						var lis = divs[j].getElementsByTagName('li');
						for(var i =0;i<lis.length;i++){
							lis[i].onmouseover = mlover;
							lis[i].onmouseout = mloutSetTimeout;
							if(window.mlrunShim == true){
								lis[i].appendChild(Iframe.cloneNode(false));
							}
							if(lis[i].getElementsByTagName('ul').length > 0){
								lis[i].className += 'haschild';
								if(divs[j].className.indexOf('arrow') != -1){
									if(divs[j].className.indexOf('vertical') != -1 || lis[i].parentNode.parentNode.nodeName != 'DIV'){
										lis[i].getElementsByTagName('a')[0].innerHTML += '<span class="vert">&rarr;</span>';
									}
									else{
										lis[i].getElementsByTagName('a')[0].innerHTML += '<span class="horiz">&darr;</span>';
									}
								}
								else if(divs[j].className.indexOf('plus') != -1){
									lis[i].getElementsByTagName('a')[0].innerHTML += '<span class="plus">+</span>';
								}
							}
							else{
								if(divs[j].className.indexOf('arrow') != -1){
									//This accounts for a wierd IE-specific bug in horizontal menus. CSS will set visibility: hidden;. This keeps the menu level(in IE)
									lis[i].getElementsByTagName('a')[0].innerHTML += '<span class="noshow">&darr;</span>';
								}
							}
							var uls = lis[i].getElementsByTagName('ul');
							for(var k=0;k<uls.length;k++){
								var found = 'no';
								for(var z=0;z<effects_a.length;z++){
									if(effects_a[z] == uls[k]){
										found = 'yes';
									}
								}
								if(found == 'no'){
									effects_a[effects_a.length] = uls[k];
									uls[k].style.zIndex = '100';
									mlEffectLoad(uls[k]);
								}
							}
						}
					}
				}
			}
			function mloutSetTimeout(e){
				if(!e){
					var the_e = window.event;
				}
				else{
					var the_e = e;
				}
				var reltg = (the_e.relatedTarget) ? the_e.relatedTarget : the_e.toElement;
				if(reltg){
					var under = ancestor(reltg,this);
					if(under === false && reltg != this){
						window.mlLast = this;
						var parent = this.parentNode;
						while(parent.parentNode && parent.className.indexOf('mlmenu') == -1){
							parent = parent.parentNode;
						}
						if(parent.className.indexOf('delay') != -1){
							window.mlTimeout = setTimeout(function(){mlout()},500);
						}
						else{
							mlout();
						}
					}
				}
			}
			function mlout(){
			if(window.mlLast==null)return false;
				var uls = window.mlLast.getElementsByTagName('ul');
				var sib;
				for(var i=0;i<uls.length;i++){
					mlEffectOut(uls[i]);
					window.mlLast.className = 'haschild hide';
					if(window.mlrunShim == true){
						sib = uls[i];							
						while(sib.nextSibling && sib.nodeName != 'IFRAME'){
								sib = sib.nextSibling
						}
						sib.style.display = 'none';
					}
				}
				window.lastover = null;
			}
			function mlover(e){
				if(!e){
					var the_e = window.event;
				}
				else{
					var the_e = e;
				}
				the_e.cancelBubble = true;
				if(the_e.stopPropagation){
					the_e.stopPropagation();
				}
				clearTimeout(window.mlTimeout);
				if(window.mlLast && window.mlLast != this && ancestor(this,window.mlLast) == false){
					mlout();
				}
				else{
					window.mlLast = null;
				}
				var reltg = (the_e.relatedTarget) ? the_e.relatedTarget : the_e.fromElement;
				var ob = this.getElementsByTagName('ul');
				var under = ancestor(reltg,this);
				if(ob[0] && under == false){
					if(window.lastover != ob[0]){
						if(window.mlrunShim == true){
							var sib = ob[0];
							while(sib.nextSibling && sib.nodeName != 'IFRAME'){
								sib = sib.nextSibling
							}
							ob[0].style.display = 'block';
							sib.style.top = ob[0].offsetTop+'px';
							sib.style.left = ob[0].offsetLeft-2+'px';
							sib.style.width = ob[0].offsetWidth+'px';
							sib.style.height = ob[0].offsetHeight-2+'px';
							sib.style.border = '1px solid red';
							sib.style.display = 'block';
						}
						this.className = 'haschild';
						mlEffectOver(ob[0],this);
						window.lastover = ob[0];
					}
				}
			}
			function mlSetOpacity(ob,level){
				if(ob){
					//level is between 0 and 10
					//need to convert to decimal for standard
					var standard = level/10;
					//need to convert to 0-100 scale for IE filter
					var ie = level*10;
					ob.style.opacity = standard;
					ob.style.filter = "alpha(opacity="+ie+")"
				}
			}
			function mlIncreaseOpacity(ob){
					var current = ob.style.opacity;
					if(lastob == ob && lastop == current){
						//mlout has not interfered
						current = current *10;
						var upone = current +1;
						mlSetOpacity(ob,upone);
						lastob = ob;
						lastop = upone/10;
					}
			}
			function mlIncreaseHeight(ob){
				var current = parseInt(ob.style.height);
				var newh = current + 1;
				ob.style.height = newh+'px';
			}
			function mlIncreaseWidth(ob){
				var current = parseInt(ob.style.width);
				var newh = current + 1;
				ob.style.width = newh+'px';
			}
			function mlBlink(ob){
				var newb = '1px solid red';
				var old = '';
				if(ob.style.border==old){
					ob.style.border=newb;
				}
				else{
					ob.style.border=old;
					ob.style.borderTop = '1px solid';
				}
			}
			function mlShake(ob){
				var newp = '5px';
				var old = '';
				if(ob.style.paddingLeft==old){
					ob.style.paddingLeft=newp;
				}
				else{
					ob.style.paddingLeft=old;
				}
			}
			function mlEffectOver(ob,parent){
				switch(ob.className){
					case 'blindh':
						ob.style.display = 'block';
						if(ob.offsetWidth){
							var width = ob.offsetWidth;
							ob.style.width = '0px';
							ob.style.overflow = 'hidden';
							for(var i=0;i<width;i++){
								setTimeout(function(){mlIncreaseWidth(ob)},i*3);
							}
							setTimeout(function(){ob.style.overflow='visible';},width*3)
						}
						break;
					default:
						ob.style.display = 'block';
						break;
				}
			}
			function mlEffectOut(ob){
				switch(ob.className){
					case 'fade':
						mlSetOpacity(ob,0);
						ob.style.display = 'none';
						break;
					case 'blink':
						ob.style.border = '';
						ob.style.display = 'none';
						break;
					case 'shake':
						ob.style.paddingLeft = '';
						ob.style.display = 'none';
						break;
					default:
						ob.style.display = 'none';
						break;
				}
			}
			function mlEffectLoad(ob){
				var parent = ob.parentNode;
				while(parent.parentNode && parent.className.indexOf('mlmenu') == -1){
					parent = parent.parentNode;
				}
				if(parent.className.indexOf('fade') != -1){
						ob.style.display = 'none';
						ob.className = 'fade';
						mlSetOpacity(ob,0);
				}
				else if(parent.className.indexOf('blink') != -1){
					ob.className = 'blink';
					ob.style.display = 'none';
				}
				else if(parent.className.indexOf('shake') != -1){
					ob.className = 'shake';
					ob.style.display = 'none';
				}
				else if(parent.className.indexOf('blindv') != -1){
					ob.className = 'blindv';
					ob.style.display = 'none';
				}
				else if(parent.className.indexOf('blindh') != -1){
					ob.className = 'blindh';
					ob.style.display = 'none';
				}
				else{
					ob.className = 'none';
					ob.style.display = 'none';
				}
			}
			function ancestor(child, parent){
				if(child==null)return false;//Saves checking elsewhere
				//This is a fix for a Firefox bug *gasp*
				//Aparantly causes a bug in Opera!
				//I see no choice but a browser detect. *sigh* I didn't want to have to do this.
				if(navigator.userAgent.indexOf('Gecko') != -1 && navigator.userAgent.indexOf('Opera') == -1){
					//This should only be run by Gecko based browsers. this code should be fine in everything but Opera so forge away browsers.
					var allc = parent.getElementsByTagName('*');
					for(var i= 0;i<allc.length;i++){
						if(allc[i] == child){
							return true;
						}
					}
				}
				else{
					//http://www.dynamicdrive.com/forums/showthread.php?t=12341 Thanks Twey!
					for(; child.parentNode; child = child.parentNode){
						if(child.parentNode === parent) return true;
					}
				}
				return false;
			}
			
var arrowimages={down:['downarrowclass', 'down.gif', 23], right:['rightarrowclass', 'right.gif']}
			
var jqueryslidemenu={

animateduration: {over: 200, out: 100}, //duration of slide in/ out animation, in milliseconds

buildmenu:function(menuid, arrowsvar){
	jQuery(document).ready(function($){
		var $mainmenu=$("#"+menuid+">ul")
		var $headers=$mainmenu.find("ul").parent()
		$headers.each(function(i){
			var $curobj=$(this)
			var $subul=$(this).find('ul:eq(0)')
			this._dimensions={w:this.offsetWidth, h:this.offsetHeight, subulw:$subul.outerWidth(), subulh:$subul.outerHeight()}
			this.istopheader=$curobj.parents("ul").length==1? true : false
			$subul.css({top:this.istopheader? this._dimensions.h+"px" : 0})
			<!--Remove arrow down - right-->
			/*$curobj.children("a:eq(0)").css(this.istopheader? {paddingRight: arrowsvar.down[2]} : {}).append(
				'<img src="'+ (this.istopheader? arrowsvar.down[1] : arrowsvar.right[1])
				+'" class="' + (this.istopheader? arrowsvar.down[0] : arrowsvar.right[0])
				+ '" style="border:0;" />'
			)
*/			$curobj.hover(
				function(e){
					var $targetul=$(this).children("ul:eq(0)")
					this._offsets={left:$(this).offset().left, top:$(this).offset().top}
					var menuleft=this.istopheader? 0 : this._dimensions.w
					menuleft=(this._offsets.left+menuleft+this._dimensions.subulw>$(window).width())? (this.istopheader? -this._dimensions.subulw+this._dimensions.w : -this._dimensions.w) : menuleft
					if ($targetul.queue().length<=1) //if 1 or less queued animations
						$targetul.css({left:menuleft+"px", width:this._dimensions.subulw+'px'}).slideDown(jqueryslidemenu.animateduration.over)
				},
				function(e){
					var $targetul=$(this).children("ul:eq(0)")
					$targetul.slideUp(jqueryslidemenu.animateduration.out)
				}
			) //end hover
			$curobj.click(function(){
				$(this).children("ul:eq(0)").hide()
			})
		}) //end $headers.each()
		$mainmenu.find("ul").css({display:'none', visibility:'visible'})
	}) //end document.ready
}
}

//build menu with ID="myslidemenu" on page:
jqueryslidemenu.buildmenu("myslidemenu", arrowimages)
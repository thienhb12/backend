// Continuous ScrollerII (17-February-2009)
// by: Vic Phillips http://www.vicsjavascripts.org.uk

// To provide a continuous Scroll any number of images or HTML messages in a banner of any length
// For both Vertical or Horizontal Applications.
// With event call functions to Stop or Start the scroll
// and to change the direction between scroll left and right.

// Application Notes
// The effect is initialised and controlled by event calls to function
// zxcScroller('h','tst2','start',1,100,200);
// where:
//  parameter 0 = the mode, for vertical 'v', for horizontal 'h'.                                (string 'v' or 'h')
//  parameter 1 = the unique id name of the scroll container.                                    (string)
//  parameter 2 = (optional) to scroll up/left = negative digit or down/right = positive digit.  (digit, default = -1)
//                may also be used to control the scroll speed.
//  parameter 3 = (optional) the scroll speed (milli seconds).                                   (digits, default = 100)
//  parameter 4 = (optional) the hold delay speed (milli seconds),                               (digits, default = no hold)
//                           may only be used if parameter 2 is 1 or -1.
//  parameter 5 = (optional) the hold position from the left/top (px).                           (digits, default = 0)
//  parameter 6 = (optional) the initial delay before scrolling (milli seconds).                 (digits, default = no auto start)
//
// The first call will initialise the effect.
// Subsequent calls may be used control the effect by updating parameters 2, 3

// To Stop & Start the Scroller
// Function zxcScrollerSS('tst1','v',true) may be used to stop and start the scroller.
// where:
//  parameter 0 = the mode, for vertical 'v', for horizontal 'h'. (string 'v' or 'h')
//  parameter 1 = the unique id name of the scroll container.     (string)
//  parameter 2 = (string) to toggle the rotation between stop and start the scroller. (string)
//  parameter 2 = true to start or false to stop the scroller.    (boolen, default = false)


// Functional Code size =  about 2.86k
function zxcScroller(mde,id,ud,spd,hold,holdpos,srt){
 var p=document.getElementById(id);
 mde=(typeof(mde)=='string'?(mde+' '):' ').charAt(0).toLowerCase();
 if ((mde!='v'&&mde!='h')||!p) return;
 if (!p[mde+'scroll']) return p[mde+'scroll']=new zxcScrollerOOP(mde,p,ud,spd,hold,holdpos,srt);
 var oop=p[mde+'scroll']
 clearTimeout(p.to);
 oop.spd=spd||oop.spd;
 oop.ud=ud||-oop.ud;
}

function zxcScrollerSS(zxcp,zxcmde,zxcrun){
 if (typeof(zxcp)=='string') zxcp=document.getElementById(zxcp);
 zxcmde=(typeof(zxcmde)=='string'?(zxcmde+' '):' ').charAt(0).toLowerCase();
 if ((zxcmde!='v'&&zxcmde!='h')||!zxcp) return;
 var zxcoop=zxcp[zxcmde+'scroll'];
 if (zxcoop){
  zxcrun=zxcrun||false;
  clearTimeout(zxcoop.to);
  if (typeof(zxcrun)=='boolean') zxcoop.run=zxcrun||false;
  else zxcoop.run=!zxcoop.run;
  if (zxcoop.run) zxcoop.scroll();
 }
}

function zxcScrollerOOP(mde,p,ud,spd,hold,holdpos,srt){
 p.style.overflow='hidden';
 this.p=p;
 this.mde=mde;
 this.vh=mde=='v'?'top':'left';
 var os=mde=='v'?['offsetHeight','offsetTop','height']:['offsetWidth','offsetLeft','width'];
 var c=p.getElementsByTagName('DIV')[0];
 var clds=c.childNodes;
 for (var z0=0;z0<clds.length;z0++){
  if (clds[z0].nodeType==1){
   this.wh=clds[z0][os[0]]+clds[z0][os[1]];
  }
 }
 holdpos=(typeof(holdpos)=='number'?holdpos:0)-this.wh;
 c.style.position='absolute';
 c.style[this.vh]=holdpos+'px';
 c.style[os[2]]=this.wh+'px';
 var max=(c[os[0]]+p[os[0]]);
 var pos=0;
 this.ary=[[c,0,[]]];
 while (pos<max){
  var z1=this.ary.length;
  this.ary[z1]=[c.cloneNode(true),pos+=this.wh,[]];
  this.ary[z1][0].style[this.vh]=this.ary[z1][1]+holdpos+'px';
  p.appendChild(this.ary[z1][0]);
 }
 for (var clds,z2=0;z2<this.ary.length;z2++){
  clds=this.ary[z2][0].childNodes;
  for (var z2a=0;z2a<clds.length;z2a++){
   if (clds[z2a].nodeType==1) this.ary[z2][2].push(clds[z2a][os[1]]);
  }
 }
 this.ud=ud||-1;
 this.spd=spd||100;
 this.hold=typeof(hold)=='number'?hold:false;
 this.holdpos=typeof(hold)=='number'?holdpos:0;
 this.to=null;
 this.data=[pos,-this.wh];
 this.run=false;
 if (typeof(srt)=='number'){
  this.run=true;
  this.to=setTimeout(function(oop){return function(){oop.scroll();}}(this),srt);
 }
}

zxcScrollerOOP.prototype.scroll=function(){
 var spd=this.spd;
 for (var r=1,z1=0;z1<this.ary.length;z1++){
  this.ary[z1][1]+=this.ud;
  this.ary[z1][0].style[this.vh]=this.ary[z1][1]+this.holdpos+'px'
  if (this.hold&&Math.abs(this.ud)==1){
   for (var z1a=0;z1a<this.ary[z1][2].length;z1a++){
    if (parseInt(this.ary[z1][0].style[this.vh])+this.ary[z1][2][z1a]*this.ud==this.holdpos) spd=this.hold;
   }
  }
  if ((this.ud<0&&this.ary[z1][1]<=this.data[1])||(this.ud>0&&this.ary[z1][1]>this.data[0])) this.ary[z1][1]=this.data[(this.ud<0)?0:1]+this.ud;
 }
 this.to=setTimeout(function(oop){return function(){oop.scroll();}}(this),spd);
}

function Init(){
	zxcScroller('h','homebook',-1,0);
	zxcScrollerSS('homebook','h', true);
}

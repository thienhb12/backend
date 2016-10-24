/****************************************************************/
/* BEGIN of VnnAdsLoader Class by Thanh Bon, 01.2007, VASC      */
/* http://javascriptcompressor.com/                             */
/* nxan sửa ngày 12/12/12                                       */
/****************************************************************/

/* Keep In View (c) JavaScript-FX. (www.javascript-fx.com)          */
function JSFX_KeepInView(id){
	var getPageY=function(el){return(el==null)?0:el.offsetTop+getPageY(el.offsetParent);};
	var getScrollTop=function(){return document.body.scrollTop||document.documentElement.scrollTop};
	var el=document.getElementById(id);if(el==null)return;
	if(el.style.position=="absolute"){el.startPageTop=-el.offsetTop;el.currentX=el.offsetLeft;el.currentY=el.offsetTop;}
	else{el.style.position="relative";el.startPageTop=getPageY(el);el.currentX=el.currentY=0;};
	el.floatInView=function(){
		var targetY=(getScrollTop()>this.startPageTop)?getScrollTop()-this.startPageTop:0;
		this.currentY+=(targetY-this.currentY)/4;this.style.top=this.currentY+"px";};
		setInterval('document.getElementById("'+id+'").floatInView()',40);
};
function adClick(stats_url, ad_url, ad_id)
{
	var r = "http://sime.vn";

	if (document.referrer) r = escape(document.referrer);
	try {
		if (ad_url != '#') {
			newwin = window.open(ad_url, '_blank');
		}
		element = document.createElement('div');
		element.innerHTML = '<img id="stat_img" style="display: none;" alt="" src="' +stats_url.replace("{REFERRER}", r) + '" width="0" height="0"/>';
		document.body.appendChild(element);
	}
	catch (e) {}

	try {
		trackEventGA('Click', ad_id);
	}
	catch (e) {}

    return false;
}

//////////////////////////////////////////////////////////////////////////////

function VsAdBlockHandler(block_id)
{
    this.block_id = block_id;
    this.blockObject = document.getElementById(this.block_id);
    this.interval = this.blockObject.getAttribute('adInterval')?
                    this.blockObject.getAttribute('adInterval'):0;

    this.displayOrder = this.blockObject.getAttribute('adDisplayOrder')?
                        this.blockObject.getAttribute('adDisplayOrder'):'random';
    // functions prototype
    this.showNextItem = showNextItem;
    this.createTimer = createTimer;
    this.removeEmptyItem = removeEmptyItem;
	this.randomOrderItems = randomOrderItems;
	this.adItemCpm = adItemCpm;

    // remove empty item
    this.removeEmptyItem();

    this.itemList = getElementsByClassName(this.block_id + 'i');

    // find first item displayed
	this.randomOrderItems(); // init with random order
    this.displayedItemIndex = -1;    // ordered
    this.showNextItem(); // show first time

    if (this.itemList.length>1 && this.interval>=1000)
        this.createTimer();

    // Get the first random item
	// Call this function after the page loaded
    function randomOrderItems() {
        for (var i=0; i<this.itemList.length; i++) {
            var randomItem = Math.floor(Math.random()*(this.itemList.length-i))+i;
            var handler = this.itemList[i].handler;
            var html = this.itemList[i].innerHTML;
            this.itemList[i].handler = this.itemList[randomItem].handler;
            this.itemList[i].innerHTML = this.itemList[randomItem].innerHTML;
            this.itemList[randomItem].handler = handler;
            this.itemList[randomItem].innerHTML = html;
        }
    }

    // Get the next item to show on the top of block
	// random mode: next item is random
	// ordered mode: next item is the next item of current top item. the top item may be random at the begining
    function showNextItem() {
        var nextItemIndex = Math.floor(Math.random()*this.itemList.length);
        if (this.displayOrder == 'random') {
            while (nextItemIndex == this.displayedItemIndex) {
                nextItemIndex = Math.floor(Math.random()*this.itemList.length);
            }
        }
        else {
            nextItemIndex = (this.displayedItemIndex >= this.itemList.length-1)?
                            0:this.displayedItemIndex+1;
        }


		if (this.displayedItemIndex > -1 && this.itemList[this.displayedItemIndex] != 'undefined' && this.itemList[this.displayedItemIndex])
			document.getElementById(this.itemList[this.displayedItemIndex].id).style.display = 'none';

        if (this.itemList[nextItemIndex]) {
			document.getElementById(this.itemList[nextItemIndex].id).style.display = 'block';
			this.adItemCpm(this.itemList[nextItemIndex]);
		};
        this.displayedItemIndex = nextItemIndex;
    };

	function adItemCpm(item)
	{
		var r = "http://truelife.vn";
		if (document.referrer) r = escape(document.referrer);

		var cpmObject = document.getElementById("cpm_" + item.id);
		if (cpmObject != 'undefined' && cpmObject) {
			document.body.removeChild(cpmObject);
		}

		try {
			element = document.createElement('div');
			element.id = "cpm_" +item.id;
			var img_url =  item.getAttribute("cpm_url") + '&'+ Math.random();
			element.innerHTML = '<img style="display: none;" alt="MegaFun by CPM stats" src="' +img_url.replace("{REFERRER}", r) + '" width="0" height="0"/>';
			document.body.appendChild(element);
		}
		catch (e) {}

		try {
			trackEventGA('Impression', item.getAttribute("cpm_url").split('&t=')[1]);
		}
		catch (e) {}

		return false;
	}

    // init timer
    function createTimer() {
        var self = this;
        window.clearInterval(self.timer);
        this.timer = window.setInterval(
            function() {
                self.showNextItem();
            },
            self.interval
        );
    };

    function removeEmptyItem() {
        var itemList = getElementsByClassName(this.block_id + 'i');

        for (var i=0; i<itemList.length; i++) {
          if   (itemList[i].getElementsByTagName('img').length +
                itemList[i].getElementsByTagName('embed').length +
                itemList[i].getElementsByTagName('iframe').length +
                itemList[i].getElementsByTagName('object').length == 0) {
               this.blockObject.removeChild(itemList[i]);
            }
        }
    }
};
//////////////////////////////////////////////////////////////////////////////

var VsAdZoneHandler = function(zone_id) {
    this.zone_id = zone_id;
    this.zoneObject = document.getElementById(this.zone_id);

	if (!this.zoneObject) {
		return;
	}
	// has no data
	if (!vsAdData[this.zoneObject.getAttribute("name")]) {
		return;
	}

	// Functions prototype
    this.showBlocksRandom = showBlocksRandom;
    this.createTimer = createTimer;
	this.JSFX_KeepInView = JSFX_KeepInView;	// animation method

    this.interval = this.zoneObject.getAttribute('adInterval')?
                    this.zoneObject.getAttribute('adInterval'):0;

	this.displayOrder = this.zoneObject.getAttribute('adDisplayOrder')?
                        this.zoneObject.getAttribute('adDisplayOrder'):'random';

	this.blockList = getElementsByClassName(this.zone_id + 'b');

    for (var i=0; i<this.blockList.length; i++) {
        this.blockList[i].handler = new VsAdBlockHandler(this.blockList[i].id);
    }

    // show blocks at the first time
    if (this.displayOrder == 'random')
        this.showBlocksRandom();

    // set visible ad zone border
    var i = 0;
    var borderObject = this.zoneObject.parentNode;
	while (i < 10 && (borderObject.getAttribute("name") !== null || borderObject.getAttribute("name") !== undefined) && borderObject.getAttribute("name") != 'vsad_border') {
		borderObject = borderObject.parentNode;
		i=i+1;
	}

	// set border object id
	if (borderObject) {
		borderObject.setAttribute("id", "border_of_" + this.zone_id);
	}
	if (this.zoneObject.getAttribute('addisplayorder') == 'keep in view') {
		this.JSFX_KeepInView(this.zoneObject.id);
	}

    if (borderObject && borderObject != this.zoneObject)
		borderObject.style.display = 'block';

    // start ad show
    this.createTimer();

    // Functions Body.
    function showBlocksRandom() {
        for (var i=0; i<this.blockList.length; i++) {
            var randomBlock = Math.floor(Math.random()*(this.blockList.length-i))+i;
            var handler = this.blockList[i].handler;
            var html = this.blockList[i].innerHTML;
            this.blockList[i].handler = this.blockList[randomBlock].handler;
            this.blockList[i].innerHTML = this.blockList[randomBlock].innerHTML;
            this.blockList[randomBlock].handler = handler;
            this.blockList[randomBlock].innerHTML = html;
        }
    }

    function createTimer() {
        var self = this;
        window.clearInterval(self.timer);
        if (self.blockList.length>1 && self.interval>=1000)
        this.timer = window.setInterval(
            function() {
                self.showBlocksRandom();
            },
            self.interval
        );
    };
}

// place the call to this function in the box ad;
function genAdZoneHtml(zone_name) {
	try {
		var html = "";
		var zonePattern =  '<div id="{zone_id}" name="{zone_name}" class="adz" align="center" adDisplayOrder="{display_order}" adInterval="{interval}">{blocks_html}</div>';

		var zone = vsAdData[zone_name];
		if (!zone) {
			return;
		}

		for (var i = 0; i<zone.blocks.length; i++)
		{
			html = html + genAdBlockHtml(zone.blocks[i]);
		}
		html = zonePattern.replace("{blocks_html}", html);

		html =  html.replace(/{interval}/gi, zone.interval)
					.replace(/{display_order}/gi, zone.displayOrder)
					.replace(/{zone_name}/gi, zone_name)
					.replace(/{zone_id}/gi, zone.id);
		$('div#' + zone_name + "[name=vsad_border]").append(html);
	}
	catch (e) {}
}

function genAdBlockHtml(block, zone_id) {
	var html = "";
	var blockPattern=  '<div id="{zone_id}{block_id}" class="{zone_id}b" align="center" adDisplayOrder="{display_order}" adInterval="{interval}">{items_html}</div>';
	for (var i = 0; i< block.items.length; i++) {
		html = html + genAdItemHtml(block.items[i]);
	}
	html = blockPattern.replace("{items_html}", html);

	html = html.replace(/{interval}/gi, block.interval)
					   .replace(/{display_order}/gi, block.displayOrder)
					   .replace(/{block_id}/gi, block.id);

	return html;
}

function genAdItemHtml(item, block_id, zone_id) {
	var html = "";
	var itemPattern =  '<div id="{zone_id}{block_id}{item_id}" class="{zone_id}{block_id}i"  style="display: none;" cpm_url="{cpm_url}">{item_code}</div>';

	if (item.type == 'image')  html = genAdImageItemHtml(item);
	else if (item.type == 'flash') html = genAdFlashItemHtml(item);
	else if (item.type == 'script') html = genAdScriptItemHtml(item);
	else if (item.type == 'text' ) html = genAdScriptItemHtml(item);
	html = itemPattern.replace("{item_code}", html);

	return html.replace("{item_id}", item.id)
					.replace("{cpm_url}", item.cpm_stats_url);
}

function genAdImageItemHtml(item) {
	var imageItemPattern = '<a href="javascript:void(1);" onclick="javascript:adClick(\'{ad_stats_url}\', \'{ad_url}\', \'{ad_id}\');" ><img src="{ad_src}" alt="" /></a>';
	imageItemPattern = imageItemPattern.replace(/{ad_src}/g, item.logo);
	imageItemPattern = imageItemPattern.replace("{ad_url}", item.link);
	imageItemPattern = imageItemPattern.replace("{ad_id}", (item.title + '(' + item.id + ')'));
	imageItemPattern = imageItemPattern.replace("{ad_stats_url}", item.cpc_stats_url);
	return imageItemPattern.replace("{ad_alt}", item.title);
}

function genAdFlashItemHtml(item) {
	var flashItemPattern = '<span onmousedown="javascript:adClick(\'{ad_stats_url}\', \'{ad_url}\', \'{ad_id}\');"><embed src="{ad_src}" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent" type="application/x-shockwave-flash" width="{ad_width}" height="{ad_height}" /></span>';
	flashItemPattern = flashItemPattern.replace(/{ad_src}/g, item.logo);
	flashItemPattern = flashItemPattern.replace("{ad_url}", item.link);
	flashItemPattern = flashItemPattern.replace("{ad_id}", (item.title + '(' + item.id + ')'));
	flashItemPattern = flashItemPattern.replace("{ad_stats_url}", item.cpc_stats_url);
	flashItemPattern = flashItemPattern.replace("{ad_width}", item.width);
	flashItemPattern = flashItemPattern.replace("{ad_height}", item.height);
	return flashItemPattern.replace("{ad_alt}", item.title);
}

function genAdScriptItemHtml(item) {
	return item.code;
}

function load_ads() {
    // get zone list
    // - create Object to handle zone business
    // - start zone's handler
	var zoneList = $('div[name=vsad_border]');
	for (var i=0; i<zoneList.length; i++) {
		genAdZoneHtml(zoneList[i].id);
	}
	for (i=0; i<zoneList.length; i++) {
		zoneList[i].handler =  new VsAdZoneHandler($(zoneList[i]).find('div[name='+ zoneList[i].id +']').attr('id'));
	}
}

function getElementsByClassName(classname, node)  {
	if(!node) node = document.getElementsByTagName("body")[0];
	var a = [];
	var re = new RegExp('\\b' + classname + '\\b');
	var els = node.getElementsByTagName("*");
	for(var i=0,j=els.length; i<j; i++)
		if(re.test(els[i].className))a.push(els[i]);
	return a;
}

function trackEventGA(type, id) {
	var ga_cate = 'Trang chủ Ad',
	 	  ga_label = 'NOT ID',
		 ga_action = 'Click';

	if (type) {
		ga_action = type;
	}
	if (id) {
		ga_label = id.replace('%23').replace('&u=1532');
	}

	try {
		ga_cate = document.getElementById('cate-title').innerHTML + ' Ad';
	}
	catch (e) {}

	try {
		_gaq.push(['_trackEvent', ga_cate, ga_action, ga_label, 1, false]);
	}
	catch (e) {}
}
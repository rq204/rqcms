




/*
     FILE ARCHIVED ON 7:50:46 十一月 30, 2010 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 14:03:08 十一月 27, 2012.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
var xml_http_building_link = '正在建立连接...';
var xml_http_sending = '正在发送数据...';
var xml_http_loading = '正在接收数据...';
var xml_http_load_failed = '通信失败, 请刷新重新尝试.';

function Ajax(statusId, recvType) {
	var aj = new Object();
	
	if($("#statusmsg").length == 0) {
		var mDiv = $('<div id="statusmsg"></div>');
		$('body').append(mDiv);
	} else {
		var mDiv = $("#statusmsg");
	}
	if(is_opera) {
		clientHeight = document.body.clientHeight /2;
		clientWidth = document.body.clientWidth /2;
		scrollTop = document.body.scrollTop;
		scrollLeft = document.body.scrollLeft;
	} else {
		clientHeight = document.documentElement.clientHeight /2;
		clientWidth = document.documentElement.clientWidth /2;
		scrollTop = document.documentElement.scrollTop;
		scrollLeft = document.documentElement.scrollLeft;
	}
	mDiv.addClass('ajaxmsg');
	var popupWidth = 200;
	mDiv.css({
		"top": clientHeight + scrollTop,
		"left": clientWidth + scrollLeft - popupWidth / 2
	});
	
	aj.targetUrl = '';
	aj.sendString = '';
	aj.recvType = recvType ? recvType : 'XML';//HTML XML
	aj.resultHandle = null;

	aj.createXMLHttpRequest = function() {
		var request = false;
		if(window.XMLHttpRequest) {
			request = new XMLHttpRequest();
			if(request.overrideMimeType) {
				request.overrideMimeType('text/xml');
			}
		} else if(window.ActiveXObject) {
			var versions = ['Microsoft.XMLHTTP', 'MSXML.XMLHTTP', 'Microsoft.XMLHTTP', 'Msxml2.XMLHTTP.7.0', 'Msxml2.XMLHTTP.6.0', 'Msxml2.XMLHTTP.5.0', 'Msxml2.XMLHTTP.4.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP'];
			for(var i=0; i<versions.length; i++) {
				try {
					request = new ActiveXObject(versions[i]);
					if(request) {
						return request;
					}
				} catch(e) {
					//alert(e.message);
				}
			}
		}
		return request;
	}

	aj.XMLHttpRequest = aj.createXMLHttpRequest();

	aj.processHandle = function() {
		mDiv.show();
		if(aj.XMLHttpRequest.readyState == 1) {
			mDiv.html(xml_http_building_link);
		} else if(aj.XMLHttpRequest.readyState == 2) {
			mDiv.html(xml_http_sending);
		} else if(aj.XMLHttpRequest.readyState == 3) {
			mDiv.html(xml_http_loading);
		} else if(aj.XMLHttpRequest.readyState == 4) {
			if(aj.XMLHttpRequest.status == 200) {
				mDiv.hide();
				if(aj.recvType == 'HTML') {
					aj.resultHandle(aj.XMLHttpRequest.responseText);
				} else if(aj.recvType == 'XML') {
					aj.resultHandle(aj.XMLHttpRequest.responseXML);
				}
			} else {
				mDiv.html(xml_http_load_failed);
			}
		}
	}

	aj.get = function(targetUrl, resultHandle) {
		aj.targetUrl = targetUrl;
		aj.XMLHttpRequest.onreadystatechange = aj.processHandle;
		aj.resultHandle = resultHandle;
		if(window.XMLHttpRequest) {
			aj.XMLHttpRequest.open('GET', aj.targetUrl);
			aj.XMLHttpRequest.send(null);
		} else {
	        aj.XMLHttpRequest.open('GET', targetUrl, true);
	        aj.XMLHttpRequest.send();
		}
	}

	aj.post = function(targetUrl, sendString, resultHandle) {
		aj.targetUrl = targetUrl;
		aj.sendString = sendString;
		aj.XMLHttpRequest.onreadystatechange = aj.processHandle;
		aj.resultHandle = resultHandle;
		aj.XMLHttpRequest.open('POST', targetUrl);
		aj.XMLHttpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		aj.XMLHttpRequest.send(aj.sendString);
	}
	return aj;
}
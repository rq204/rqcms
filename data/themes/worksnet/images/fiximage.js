




/*
     FILE ARCHIVED ON 7:51:32 十一月 30, 2010 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 14:07:11 十一月 27, 2012.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
function fiximage(thumbs_size) {
	var max = thumbs_size.split('x');
	var fixwidth = max[0];
	var fixheight = max[1];
	imgs = document.getElementsByTagName('img');
	for(i=0;i<imgs.length;i++) {
		w=imgs[i].width;h=imgs[i].height;
		if(w>fixwidth) { imgs[i].width=fixwidth;imgs[i].height=h/(w/fixwidth);}
		if(h>fixheight) { imgs[i].height=fixheight;imgs[i].width=w/(h/fixheight);}
	}
}
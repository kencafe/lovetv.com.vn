function makeKeywordForPost(mKF_id)
{
	var content;
	var isDOM  = (navigator.appName.match("Microsoft Internet Explorer") || navigator.appName.match("MSIE")) ? false : true;
	if(isDOM) {
			content = document.getElementById(mKF_id).textContent;
	} else {
			content = document.getElementById(mKF_id).innerText;
	}
	var str = "";
	var link1 = home_page2 +"/Result/?k=" + str;
	var link2 = "";
	for(var j=0;j<keyword_collect.length;j++){
		if(content.indexOf(" "+keyword_collect[j]+" ")!=-1){
			str += '<a href="'+link1+encodeURIComponent(keyword_collect[j])+link2+'">'+keyword_collect[j]+'</a>, ';
		}
	}
	str =  (str!="") ? keyword_text + str : keyword_text + "no keyword";
	document.write(str);
}

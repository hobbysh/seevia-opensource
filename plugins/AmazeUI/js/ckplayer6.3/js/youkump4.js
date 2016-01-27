/**
 * 通过优酷视频ID返回手机（安卓、IOS系统）端可播放的MP4格式视频地址
 *
 *@author xudianyang<120343758@qq.com>
 */

/* 摘自m.youku.com站点http://player.youku.com/jsapi */
var L = function(a, c) {
	var b = new Date;
	this._sid = b.getTime() + "" + (1E3 + b.getMilliseconds()) + "" + (parseInt(9E3 * Math.random()) + 1E3);
	this._seed = a.seed;
	this._fileType = c;
	b = new M(this._seed);
	this._streamFileIds = a.streamfileids;
	this._videoSegsDic = {};
	for (c in a.segs) {
		for (var e = [], g = 0, d = 0; d < a.segs[c].length; d++) {
			var j = a.segs[c][d],
				q = {};
			q.no = j.no;
			q.size = j.size;
			q.seconds = j.seconds;
			j.k && (q.key = j.k);
			q.fileId = this.getFileId(a.streamfileids, c, parseInt(d), b);
			q.type = c;
			q.src = this.getVideoSrc(j.no, a, c, q.fileId);
			e[g++] = q
		}
		this._videoSegsDic[c] = e
	}
};
M = function(a) {
	this._randomSeed = a;
	this.cg_hun()
};
M.prototype = {
	cg_hun: function() {
		this._cgStr = "";
		for (var a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/\\:._-1234567890", c = a.length, b = 0; b < c; b++) {
			var e = parseInt(this.ran() * a.length);
			this._cgStr += a.charAt(e);
			a = a.split(a.charAt(e)).join("")
		}
	},
	cg_fun: function(a) {
		for (var a = a.split("*"), c = "", b = 0; b < a.length - 1; b++) c += this._cgStr.charAt(a[b]);
		return c
	},
	ran: function() {
		this._randomSeed = (211 * this._randomSeed + 30031) % 65536;
		return this._randomSeed / 65536
	}
};

L.prototype = {
	getFileId: function(a, c, b, e) {
		for (var g in a) if (g == c) {
			streamFid = a[g];
			break
		}
		if ("" == streamFid) return "";
		c = e.cg_fun(streamFid);
		a = c.slice(0, 8);
		b = b.toString(16);
		1 == b.length && (b = "0" + b);
		b = b.toUpperCase();
		c = c.slice(10, c.length);
		return a + b + c
	},
	getVideoSrc: function(a, c, b, e, g, d) {
		if (!c.videoid || !b) return "";
		var j = this._sid,
			q = {
				flv: 0,
				flvhd: 0,
				mp4: 1,
				hd2: 2,
				"3gphd": 1,
				"3gp": 0
			}[b],
			i = {
				flv: "flv",
				mp4: "mp4",
				hd2: "flv",
				"3gphd": "mp4",
				"3gp": "flv"
			}[b],
			k = a.toString(16);
		1 == k.length && (k = "0" + k);
		var l = c.segs[b][a].seconds,
			a = c.segs[b][a].k;
		if ("" == a || -1 == a) a = c.key2 + c.key1;
		b = "";
		c.show && (b = c.show.show_paid ? "&ypremium=1" : "&ymovie=1");
		return "http://f.youku.com" + ("/player/getFlvPath/sid/" + j + "_" + k + "/st/" + i + "/fileid/" + e + "?K=" + a + "&hd=" + q + "&myp=0&ts=" + l + "&ypp=0" + b + ((g ? "/password/" + g : "") + (d ? d : "")))
	}
};

/* 摘自m.youku.com站点http://player.youku.com/jsapi end*/

/**
 * 获取优酷视频的mp4地址
 * 
 * @param string vid (必选)视频ID，如XNTQ5ODExNzYw
 * @param function callback (必选)通过异步获取mp4视频url之后的回调
 */
function getYoukuMp4Url(vid, callback)
{
	var mp4Url = '';

	// 获取视频数据接口地址
	var videoDataApi = 'http://v.youku.com/player/getPlaylist/VideoIDS/' + vid + '/Pf/4';

	// 异步获取视频信息
	$.ajax(
	{
		url : videoDataApi + '?__callback=?',
		dataType : 'jsonp',
		type : 'GET',
		success : function(video)
		{
			if(typeof(video.data[0]) === 'undefined')
			{
				alert('获取ID为: ' + vid + ' 的视频信息失败。');
			}
			else
			{
				var videoInfo = new L(video.data[0], 'mp4');
				if(typeof(videoInfo._videoSegsDic['3gphd']) === 'undefined')
				{
					alert('ID为: '+　vid + ' 的视频不支持手机站点的播放');
					if(typeof(videoInfo._videoSegsDic['mp4']) === 'undefined')
						return false;
					if(window.confirm('是否采用mp4视频的第一段继续播放?'))
					{
						var mp4DataUrl = videoInfo._videoSegsDic['mp4'][0].src;
					}
					else
					{
						return false;
					}
				}
				if(typeof(mp4DataUrl) === 'undefined')
					var mp4DataUrl = videoInfo._videoSegsDic['3gphd'][0].src;

				// 异步获取视频的MP4地址
				$.ajax(
				{
					url : mp4DataUrl + '&callback=?',
					dataType : 'jsonp',
					type : 'GET',
					success : function(mp4)
					{
						if(typeof(mp4[0]) !== 'undefined')
						{
							mp4Url = mp4[0].server;
							callback(mp4Url);
						}
						else
						{
							alert('获取ID为: ' + vid + ' 的视频的mp4 url失败。');
						}
					}
				});
			}
		}
	});
}
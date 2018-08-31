(function($){
	$.fn.spotreader = function(options){
		var defaults = {
			'epub_url' : null,
			'file_id' : 'myFile',
			'button_id': 'viewEPUB'
		};
		var setting = $.extend(defaults, options);
		var opfDir = '';
		var opfFile = '';
		var opfItem = [];
		var epub;

		$('#'+setting.button_id).click(function(){
			$.ajax({
				url : setting.epub_url,
				async : true,
				timeout : 6000,
				beforeSend : function(xhr){
					xhr.overrideMimeType("text/plain;charset=x-user-defined");
				},
				error : function(xhr, textStatus, errorThrown){
					alert(textStatus);
				},
				success : function(data, textStatus){
					var bytes = [];
					for (var i=0; i<data.length; i++) bytes[i] = data.charCodeAt(i) & 0xff;
					data = null;
					try {
						epub = Zip.inflate(bytes);
						bytes = null;
					} catch (e) {
						alert("ファイルの展開に失敗しました。\n"+e.message);
					}
					console.dir(epub.files);
					if (!epub.files['META-INF/container.xml']) return exitMsg("container.xmlが読み込めません。");
					if (parseContainerXml(epub.files['META-INF/container.xml'].data) == false) return exitMsg("container.xmlの解析に失敗しました。");
					if (!epub.files[opfFile] || parseOpf(epub.files[opfFile].data) == false) return exitMsg(opfFile+"の解析に失敗しました。");

					$(this).html('OK');
				}
			});
		});

		var parseContainerXml = function(data)
		{
			var m = data.match(/ full\-path="([^"\/]*)\/?([^\/"]+\.opf)"/i);
			opfDir = m[1];
			opfFile = opfDir=='' ? m[2] : opfDir + '/' + m[2];
			return (opfFile!='');
		};

		var parseOpf = function(data)
		{
			var m = data.match(/<item [^>]+\/>/img);
			for (var i=0; i<m.length; i++) {
				var mm = m[i].match(/[-a-z]+="[^"]+"/img);
				var attr = [];
				for (var n=0; n<mm.length; n++) {
					var mmm = mm[n].match(/([-a-z]+)="([^"]+)"/i);
					attr[mmm[1]] = mmm[2];
				}
				opfItem[i] = attr;
			}
//console.dir(opfItem);
			return (opfItem.length>0);
		};

		var exitMsg = function(msg)
		{
			epub = null;
			alert(msg);
			return false;
		};
		var debug = function(obj)
		{
			var str = '';
			if (typeof obj === 'object') {
				for (var i in obj) {
					str += i + ' => ' + debug(obj[i]);
				}
			} else {
				str += obj + "\n";
			}
			return str;
		};
		return this;
	};
})(jQuery);
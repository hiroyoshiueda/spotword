/*
	zip.js (inflate): hinata.in
	inflate.js(gzip): Copyright (C) 1999 Masanao Izumo <iz@onicos.co.jp>
	                   + dankogai(http://coderepos.org/share/browser/lang/javascript/deflate/trunk/rawinflate.js)
	ecl.js(sjis): Escape Codec Library: ecl.js (Ver.041208)

	If you use Japanese file name: import "sjis.js"

	using: Zip.inflate([byte array])
	return:
		{
			files: {
				"file1": {
					version:
					bitFlag:
					method:
					fileTime:
					fileDate:
					crc32:
					size:
					fileSize:
					nameLength:
					extraLength:
				},
				"file2": �c
			},
			directories: {
				"file1": {
					version:
					extVersion:
					bitFlag:
					method:
					fileTime:
					fileDate:
					crc32:
					size:
					fileSize:
					nameLength:
					extraLength:
					commentLength:
					diskNumberStart:
					attributes:
					extAttributes:
					headerOffset:
				},
				"file2": �c
			},
			record: {
				diskNumber:
				startNumber:
				diskLength:
				length:
				directorySize:
				offset:
				commentLength:
			}
		}
*/

window.Zip = {
	inflate : function (bin){

		function bin2ascii(b)
		{
			return String.fromCharCode.apply(this, b);
		}

		function bin2text(b)
		{
			if(!window.SJIS)return bin2ascii(b);

			var encode = b.map(function (i){
				i = i.toString(16).toUpperCase();
				return "%" + ((i.length == 1) ? ("0" + i) : i);
			}).join("");

			return SJIS.decode(encode);
		}

		function readint(len)
		{
			var b = read(len);
			var val = 0;
			b = b.reverse();
			for(var i in b)val += b[i] * Math.pow(0x100, b.length - i - 1);
			return val;
		}

		function read(len)
		{
			var t = bin.slice(0, len);
			bin = bin.slice(len);
			return t;
		}

		function convert_modified(date, time)
		{
			return new Date(
					1980 + (file.fileDate >> 9),
					(file.fileDate >> 5 & 15) - 1,
					file.fileDate & 31,
					file.fileTime >> 11,
					file.fileTime >> 5 & 60
			);
		}

		var zip = {files : {}, directories : {}, record : null};
		var compressors = {
			0 : function (b){
				return bin2ascii(b);
			},
			8 : function(Q){Q=bin2ascii(Q);var n=null;function ha(g,l,j){var b,a;for(b=0;b<j;){if(N&&B==-1)break;if(r>0){if(B!=0)for(;r>0&&b<j;){r--;I&=32767;v&=32767;g[l+b++]=C[v++]=C[I++]}else{for(;r>0&&b<j;){r--;v&=32767;o(8);g[l+b++]=C[v++]=p(8);m(8)}if(r==0)B=-1}if(b==j)break}if(B==-1){if(N)break;o(1);if(p(1)!=0)N=true;m(1);o(2);B=p(2);m(2);D=n;r=0}switch(B){case 0:a=g;var i=l+b,k=j-b,h=void 0;h=J&7;m(h);o(16);h=p(16);m(16);o(16);if(h!=(~K&65535))a=-1;else{m(16);r=h;for(h=0;r>0&&h<k;){r--;v&=32767;o(8);a[i+h++]=C[v++]=p(8);m(8)}if(r==0)B=-1;a=h}break;case 1:if(D!=n)a=R(g,l+b,j-b);else a:{a=g;i=l+b;k=j-b;if(S==n){var c=void 0;h=Array(288);c=void 0;for(c=0;c<144;c++)h[c]=8;for(;c<256;c++)h[c]=9;for(;c<280;c++)h[c]=7;for(;c<288;c++)h[c]=8;T=7;c=new O(h,288,257,Z,$,T);if(c.status!=0){throw "HufBuild error: "+c.status;a=-1;break a}S=c.e;T=c.d;for(c=0;c<30;c++)h[c]=5;zip_fixed_bd=5;c=new O(h,30,0,aa,ba,zip_fixed_bd);if(c.status>1){S=n;throw "HufBuild error: "+c.status;a=-1;break a}ca=c.e;zip_fixed_bd=c.d}D=S;V=ca;w=T;G=zip_fixed_bd;a=R(a,i,k)}a=a;break;case 2:a=D!=n?R(g,l+b,j-b):ia(g,l+b,j-b);break;default:a=-1}if(a==-1){if(N)return 0;return-1}b+=a}return b}function ia(g,l,j){var b,a,i,k,h,c,y,f=Array(316);for(b=0;b<f.length;b++)f[b]=0;o(5);c=257+p(5);m(5);o(5);y=1+p(5);m(5);o(4);b=4+p(4);m(4);if(c>286||y>30)return-1;for(a=0;a<b;a++){o(3);f[da[a]]=p(3);m(3)}for(;a<19;a++)f[da[a]]=0;w=7;a=new O(f,19,19,n,n,w);if(a.status!=0)return-1;D=a.e;w=a.d;k=c+y;for(b=i=0;b<k;){o(w);h=D.h[p(w)];a=h.b;m(a);a=h.c;if(a<16)f[b++]=i=a;else if(a==16){o(2);a=3+p(2);m(2);if(b+a>k)return-1;for(;a-- >0;)f[b++]=i}else{if(a==17){o(3);a=3+p(3);m(3)}else{o(7);a=11+p(7);m(7)}if(b+a>k)return-1;for(;a-- >0;)f[b++]=0;i=0}}w=9;a=new O(f,c,257,Z,$,w);if(w==0)a.status=1;if(a.status!=0)return-1;D=a.e;w=a.d;for(b=0;b<y;b++)f[b]=f[b+c];G=6;a=new O(f,y,0,aa,ba,G);V=a.e;G=a.d;if(G==0&&c>257)return-1;if(a.status!=0)return-1;return R(g,l,j)}function R(g,l,j){var b,a,i;if(j==0)return 0;for(i=0;;){o(w);a=D.h[p(w)];for(b=a.a;b>16;){if(b==99)return-1;m(a.b);b-=16;o(b);a=a.g[p(b)];b=a.a}m(a.b);if(b==16){v&=32767;g[l+i++]=C[v++]=a.c}else{if(b==15)break;o(b);r=a.c+p(b);m(b);o(G);a=V.h[p(G)];for(b=a.a;b>16;){if(b==99)return-1;m(a.b);b-=16;o(b);a=a.g[p(b)];b=a.a}m(a.b);o(b);I=v-a.c-p(b);for(m(b);r>0&&i<j;){r--;I&=32767;v&=32767;g[l+i++]=C[v++]=C[I++]}}if(i==j)return j}B=-1;return i}function m(g){K>>=g;J-=g}function p(g){return K&ja[g]}function o(g){for(;J<g;){var l=K,j;j=U.length==W?-1:U.charCodeAt(W++)&255;K=l|j<<J;J+=8}}function O(g,l,j,b,a,i){this.f=16;this.i=288;this.status=0;this.e=n;this.d=0;var k=Array(this.f+1),h,c,y,f,d,e,z,A=Array(this.f+1),x,u,s,q=new ea,H=Array(this.f);f=Array(this.i);var t,E=Array(this.f+1),L,F,M;M=this.e=n;for(d=0;d<k.length;d++)k[d]=0;for(d=0;d<A.length;d++)A[d]=0;for(d=0;d<H.length;d++)H[d]=n;for(d=0;d<f.length;d++)f[d]=0;for(d=0;d<E.length;d++)E[d]=0;h=l>256?g[256]:this.f;x=g;u=0;d=l;do{k[x[u]]++;u++}while(--d>0);if(k[0]==l){this.e=n;this.status=this.d=0}else{for(e=1;e<=this.f;e++)if(k[e]!=0)break;z=e;if(i<e)i=e;for(d=this.f;d!=0;d--)if(k[d]!=0)break;y=d;if(i>d)i=d;for(L=1<<e;e<d;e++,L<<=1)if((L-=k[e])<0){this.status=2;this.d=i;return}if((L-=k[d])<0){this.status=2;this.d=i}else{k[d]+=L;E[1]=e=0;x=k;u=1;for(s=2;--d>0;)E[s++]=e+=x[u++];x=g;d=u=0;do if((e=x[u++])!=0)f[E[e]++]=d;while(++d<l);l=E[y];E[0]=d=0;x=f;u=0;f=-1;t=A[0]=0;s=n;for(F=0;z<=y;z++)for(g=k[z];g-- >0;){for(;z>t+A[1+f];){t+=A[1+f];f++;F=(F=y-t)>i?i:F;if((c=1<<(e=z-t))>g+1){c-=g+1;for(s=z;++e<F;){if((c<<=1)<=k[++s])break;c-=k[s]}}if(t+e>h&&t<h)e=h-t;F=1<<e;A[1+f]=e;s=Array(F);for(c=0;c<F;c++)s[c]=new ea;M=M==n?this.e=new fa:M.next=new fa;M.next=n;M.h=s;H[f]=s;if(f>0){E[f]=d;q.b=A[f];q.a=16+e;q.g=s;e=(d&(1<<t)-1)>>t-A[f];H[f-1][e].a=q.a;H[f-1][e].b=q.b;H[f-1][e].c=q.c;H[f-1][e].g=q.g}}q.b=z-t;if(u>=l)q.a=99;else if(x[u]<j){q.a=x[u]<256?16:15;q.c=x[u++]}else{q.a=a[x[u]-j];q.c=b[x[u++]-j]}c=1<<z-t;for(e=d>>t;e<F;e+=c){s[e].a=q.a;s[e].b=q.b;s[e].c=q.c;s[e].g=q.g}for(e=1<<z-1;(d&e)!=0;e>>=1)d^=e;for(d^=e;(d&(1<<t)-1)!=E[f];){t-=A[f];f--}}this.d=A[1];this.status=L!=0&&y!=1?1:0}}}function ea(){this.c=this.b=this.a=0;this.g=n}function fa(){this.h=this.next=n}var C,v,S=n,ca,T,K,J,B,N,r,I,D,V,w,G,U,W,ja=[0,1,3,7,15,31,63,127,255,511,1023,2047,4095,8191,16383,32767,65535],Z=[3,4,5,6,7,8,9,10,11,13,15,17,19,23,27,31,35,43,51,59,67,83,99,115,131,163,195,227,258,0,0],$=[0,0,0,0,0,0,0,0,1,1,1,1,2,2,2,2,3,3,3,3,4,4,4,4,5,5,5,5,0,99,99],aa=[1,2,3,4,5,7,9,13,17,25,33,49,65,97,129,193,257,385,513,769,1025,1537,2049,3073,4097,6145,8193,12289,16385,24577],ba=[0,0,0,0,1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10,11,11,12,12,13,13],da=[16,17,18,0,8,7,9,6,10,5,11,4,12,3,13,2,14,1,15],P;if(C==n)C=Array(65536);J=K=v=0;B=-1;N=false;r=I=0;D=n;U=Q;W=0;for(var X=Array(1024),Y=[];(Q=ha(X,0,X.length))>0;){var ga=Array(Q);for(P=0;P<Q;P++)ga[P]=String.fromCharCode(X[P]);Y[Y.length]=ga.join("")}U=n;return Y.join("")}
		};

		while(true)
		{
			var signature = readint(4);

			if(signature == 0x04034b50)
			{
				var file = {
					version : readint(2),
					bitFlag : readint(2),
					method : readint(2),
					fileTime : readint(2),
					fileDate : readint(2),
					crc32 : readint(4),
					size : readint(4),
					fileSize : readint(4),
					nameLength : readint(2),
					extraLength : readint(2)
				};
				file.name = bin2text(read(file.nameLength));
				file.extra = bin2ascii(read(file.extraLength));
				file.bytes = read(file.size);
				file.data = compressors[file.method](file.bytes).slice(0, file.fileSize);
				file.modified = convert_modified(file.fileDate, file.fileTime);
				zip.files[file.name] = file;
			}else if(signature == 0x08074b50){
				var extendHeader = {
					crc32 : readint(4),
					size : readint(4),
					fileSize : readint(4)
				};
				zip.files[zip.files.length - 1].extendHeader = extendHeader;
			}else if(signature == 0x02014b50){
				var directory = {
					version : readint(2),
					extVersion : readint(2),
					bitFlag : readint(2),
					method : readint(2),
					fileTime : readint(2),
					fileDate : readint(2),
					crc32 : readint(4),
					size : readint(4),
					fileSize : readint(4),
					nameLength : readint(2),
					extraLength : readint(2),
					commentLength : readint(2),
					diskNumberStart : readint(2),
					attributes : readint(2),
					extAttributes : readint(4),
					headerOffset : readint(4)
				};
				directory.name = bin2text(read(directory.nameLength));
				directory.extra = bin2text(read(directory.extraLength));
				directory.comment = bin2text(read(directory.commentLength));
				directory.modified = convert_modified(directory.fileDate, directory.fileTime);
				zip.directories[directory.name] = directory;
			}else if(signature == 0x06054b50){
				var record = {
					diskNumber : readint(2),
					startNumber : readint(2),
					diskLength : readint(2),
					length : readint(2),
					directorySize : readint(4),
					offset : readint(4),
					commentLength : readint(2)
				};
				record.comment = bin2text(read(record.commentLength));
				zip.record = record;
				break;
			}else{
				throw "invalid zip format.";
			}
		}

		return zip;
	}
};
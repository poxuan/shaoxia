;(function(){
    var chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_";
    var encode_chars = "";
    var check_num = offset = 0;
    function init(rawSeed) {
        var sum = newSeed = 0;
        seed = rawSeed;
        while(seed > 0) {
            newSeed = seed & 63;
            sum += newSeed;
            seed = seed >> 6; 
        }
        check_num = sum & 63;
        offset = parseFloat((check_num / 64).toFixed(2));
        length = chars.length;
        var charArr = chars.split('');
        // 伪洗牌
        for(var i=0; i < length; i++) {
            var j = i + (rawSeed % (length - i));
            if (i != j) {
                c = charArr[i];
                charArr[i] = charArr[j];
                charArr[j] = c;
            }
        }
        encode_chars = charArr.join('');
    }
    function randomString(length) {
        var res = '';
        for(var i=0;i<length;i++) {
            var p = parseInt(Math.random() * encode_chars.length);
            res += encode_chars.charAt(p);
        }
        return res;
    }
    function s2c(str) {
        if (!str) {
            return '';
        }
        var to_base = rand(0x11, 0x1c);
        var type = rand(0, 3);
        var p = to_base + (type - 1) * 0x10;
        var prefix = encode_chars.charAt(p);
        var bytes = byteBias(str, type);
        var convert = byteToString(base_convert(bytes, 36, to_base));
        return prefix + convert;
    }
    function c2s(convert) {
        if (!convert) return '';
        pos = strpos(encode_chars, convert[0]);
        base = pos % 0x10 + 0x10;
        type = parseInt(pos / 0x10);
        raw = convert.slice(1);
        bytes = base_convert(raw, base, 36);
        return byteToString(byteBias(bytes, type));
    }
    function byteBias(str, type) {
        var bytes = Array.isArray(str) ? str : stringToByte(str);
        var newBytes = new Array;
        var tmpBytes = new Array;
        switch(type) {
            case 0:
                return bytes;
            case 1:
                return bytes.reverse()
            default:
                for(var i = 0; i <  bytes.length; i++) {
                    tmpBytes.push(bytes[i])
                    if (tmpBytes.length >= type) {
                        newBytes = newBytes.concat(tmpBytes.reverse())
                        tmpBytes = []
                    }
                }
                if (tmpBytes.length > 0) {
                    newBytes = newBytes.concat(tmpBytes.reverse())
                    tmpBytes = []
                }
                return newBytes;
        }
    }
    function base_convert(str, from_base, to_base) {
        bytes = Array.isArray(str) ? str : stringToByte(str);
        var offset = Math.abs(from_base - to_base);
        var pattern = encode_chars;
        var resBytes = [];
        llen = bytes.length;
        if (from_base < to_base) { // 目标编码大为解码
			cs = 1; // 大小写转换 1 小写 2 大写
			append = 0; // 额外长度
            extra = null;
			for(i = 0; i < llen; i++) {
				p = strpos(pattern, String.fromCharCode(bytes[i]));
				if (extra !== null) { // 其他字符
                    append += 1;
                    j = i - append;
                    pre = (extra ^ (j & 7)) << 5;
                    fix = p - offset - (j & 31);
                    fix = fix < 0 ? fix + 32 : fix;
                    ord = pre + fix;
                    resBytes.push(ord);
                    extra = null;
                } else if (p < offset) { // 前区大小写转换用
                    // 当前大小写转换
                    cs = cs == 1 ? 2 : 1;
					append += 1;
				} else if (p >= offset + to_base) { // 后区其他字符使用
                    extra = p - offset - to_base;
                } else {
                    e = p - offset - i + append;
                    while (e < 0) e += to_base; 
                    c = e.toString(36);
                    chr = cs == 2 ? c.toUpperCase() : c
                    resBytes.push(chr.charCodeAt());
                }
			}
		} else { // 加码
			upcase = false;
			for(var i = 0; i < llen; i++) {
                var chr = String.fromCharCode(bytes[i])
				if (/[0-9a-zA-Z]/.test(chr)) {
                    // 大小写转换用前区扩展位
					if (!upcase && chr >= 'A' &&  chr <= 'Z') {
						resBytes.push(getOrd(rand(0, offset - 1)));
						upcase = true;
					}
					if (upcase && chr >= 'a' &&  chr <= 'z') {
						resBytes.push(getOrd(rand(0, offset - 1)));
						upcase = false;
					}
					e = parseInt(chr, 36);
					key = (e + i) % from_base + offset;
					resBytes.push(getOrd(key));
				} else {// 其他字符用后区扩展位
                    ord = bytes[i];
					key = (ord >> 5) ^ (i & 7);
                    fix = getOrd(from_base + offset + key);
                    val = getOrd(offset + ((ord + i) & 31));
					resBytes.push(fix);
                    resBytes.push(val);
				}
			}
		}
		return resBytes;
    }
    function rand(bs, be) {
        return Math.floor(Math.random() * (be - bs + 1)) + bs
    }
    function getChr(p) {
        return encode_chars.charAt(p);
    }
    function getOrd(p) {
        return getChr(p).charCodeAt();
    }
    function strpos(haystack, needle, start) {
        if (typeof(start)==="undefined") {
            start = 0;
        }
        if (!needle) {
            return 0;
        }
        var j = 0;
        for (var i = start; i < haystack.length && j < needle.length; i++) {
            if (haystack.charAt(i) === needle.charAt(j)) {
                j++;
            } else {
                j = 0;
            }
        }
        if (j === needle.length) {
            return i - needle.length;
        }
        return -1;
    }
    function replaceWord (str, index, newWords) {
        var arr = str.split('')
        arr[index] = newWords
        return arr.join('')
    }
    function c2r(convert, length = null) {
        if (!convert) return null;
        var clen = convert.length; // 转换值
        if (!length) { // 
            var min_length = Math.max(convert.length + 3, 7);
            var max_length = min_length + Math.min(convert.length, 31);
            length = rand(min_length, max_length);
        }
		var randerStr = randomString(length);
        if (length - 2 >= clen) { // 在除最后两位字符外的地方填充
            for(var i = 0; i < clen; i++) {
                pos = parseInt((i + offset) * (length - 2) / clen);
                randerStr = replaceWord(randerStr,pos,convert.charAt(i))
            }
        } else {
            throw "length not enough";
        }
		return cal(randerStr, clen, length);
    }
    function cal(str, clen, rlen) {
        var checknum = check_num
        for (i = 0; i < rlen - 2; i++) {
            checknum += str.charCodeAt(i);
        }
        var check_offset = checknum & 63;
        str = replaceWord(str,rlen - 1,encode_chars.charAt(check_offset))
        length_offset = (clen + check_offset) % encode_chars.length;
        str = replaceWord(str,rlen - 2,encode_chars.charAt(length_offset))
        return str;
    }
    function s2r(str = '', length = null) {
        return c2r(s2c(str), length);
    }
    function r2c(hideStr) {
        if (!hideStr) return null;
        length = hideStr.length;
        llen = lac(hideStr); 
        if (!llen) return null;
        raw = "";
        for (i = 0; i< llen; i++) {
            pos = parseInt((i + offset) * (length - 2) / llen);
            raw += hideStr.charAt(pos);
        }
        return raw;
    }
    function lac(hideStr) {
        var pattern = encode_chars;
        var length = hideStr.length;
        var checknum = check_num;
        for (i = 0; i < length - 2; i++) {
            checknum += hideStr.charCodeAt(i);
        }
        // 校验位不一致，则解码失败
        var check_char = hideStr.charAt(length - 1);
        if (pattern.charAt(checknum & 63) != check_char) {
            return null;
        }
        if (!check_char) return null;
        length = hideStr.length;
        var check_offset = strpos(encode_chars, check_char);
        var leng_char = hideStr.charAt(length - 2);
        var llen = strpos(encode_chars, leng_char) - check_offset;
        while(llen + 64 < length) llen += 64;
        if (llen > length) return null;
        return llen;
    }
	function r2s(hideStr) {
        return c2s(r2c(hideStr));
	}
    //字符串转字节序列
    function stringToByte(str) {  
        var bytes = new Array();  
        var len, c;  
        len = str.length;  
        for(var i = 0; i < len; i++) {  
            c = str.charCodeAt(i);  
            if(c >= 0x010000 && c <= 0x10FFFF) {  
                bytes.push(((c >> 18) & 0x07) | 0xF0);  
                bytes.push(((c >> 12) & 0x3F) | 0x80);  
                bytes.push(((c >> 6) & 0x3F) | 0x80);  
                bytes.push((c & 0x3F) | 0x80);  
            } else if(c >= 0x000800 && c <= 0x00FFFF) {  
                bytes.push(((c >> 12) & 0x0F) | 0xE0);  
                bytes.push(((c >> 6) & 0x3F) | 0x80);  
                bytes.push((c & 0x3F) | 0x80);  
            } else if(c >= 0x000080 && c <= 0x0007FF) {  
                bytes.push(((c >> 6) & 0x1F) | 0xC0);  
                bytes.push((c & 0x3F) | 0x80);  
            } else {  
                bytes.push(c & 0xFF);  
            }  
        }  
        return bytes;
    }

    //字节序列转ASCII码
    //[0x24, 0x26, 0x28, 0x2A] ==> "$&C*"
    function byteToString(arr) {  
        if(typeof arr === 'string') {  
            return arr;  
        }  
        var str = '',  
            _arr = arr;  
        for(var i = 0; i < _arr.length; i++) {  
            var one = _arr[i].toString(2),  
                v = one.match(/^1+?(?=0)/);  
            if(v && one.length == 8) {  
                var bytesLength = v[0].length;  
                var store = _arr[i].toString(2).slice(7 - bytesLength);  
                for(var st = 1; st < bytesLength; st++) {  
                    store += _arr[st + i].toString(2).slice(2);  
                }  
                str += String.fromCharCode(parseInt(store, 2));  
                i += bytesLength - 1;  
            } else {  
                str += String.fromCharCode(_arr[i]);  
            }  
        }  
        return str;  
    }  
    function s_btoa(str) {
        return btoa(encodeURIComponent(str)).replace('+','_').replace('/','-').replace('=','')
    }
    function s_atob(str) {
        return decodeURIComponent(atob(str.replace('_','+').replace('-','/')))
    }
    window.datahide = {
        init:init,
        s2c:s2c,
        c2s:c2s,
        s2r:s2r,
        r2s:r2s,
        s_atob:s_atob,
        s_btoa:s_btoa,
    };
})();
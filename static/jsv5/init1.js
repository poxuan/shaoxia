/*
 * 加密工具已经升级了一个版本，目前为 jsjiami.com.v5 ，主要加强了算法，以及防破解【绝对不可逆】配置，耶稣也无法100%还原，我说的。;
 * 已经打算把这个工具基础功能一直免费下去。还希望支持我。
 * 另外 jsjiami.com.v5 已经强制加入校验，注释可以去掉，但是 jsjiami.com.v5 不能去掉（如果你开通了VIP，可以手动去掉），其他都没有任何绑定。
 * 誓死不会加入任何后门，jsjiami JS 加密的使命就是为了保护你们的Javascript 。
 * 警告：如果您恶意去掉 jsjiami.com.v5 那么我们将不会保护您的JavaScript代码。请遵守规则
 * 新版本: https://www.jsjiami.com/ 支持批量加密，支持大文件加密，拥有更多加密。 */

var encode_version = "jsjiami.com.v5",
  aersd = "__0xc2b4e",
  __0xc2b4e = [
    "w4hjMj7DkQ==",
    "wqg0w5Q=",
    "RjUAYww=",
    "OcONfMO4fw==",
    "w7d8UAjCjw==",
    "w5jCoRVqw4c=",
    "wq4Cw5zDucOD",
    "wrwqw4DClMK+",
    "QcKowo4Tw6bDoVXCsW0=",
    "54uy5p6c5Y+W772yw6p+5Ly05ay55p+R5b6Q56mQ776u6L266K+/5pam5o2/5oij5LiC55iA5bW75L2Z",
    "5Yiu6Zib54qE5p2f5Y++776KBz7kvK7lrofmn53lv7znqJM=",
    "wqLDrWFkIQ==",
    "w45hZg==",
    "wpRnwofDtMKYecKMwpRCDMOswqopHw==",
    "VsKSw4DCqzk=",
    "wpU+w71dD8KowoI=",
    "w6TCiD5e",
    "w53CniPCpSI=",
    "bMKrAsKSwos=",
    "w4/Dt2J0wrY=",
    "YsKpw7jChT8=",
    "BMOORcOobQ==",
    "w7LCosOFw5DDmw==",
    "BcKnw73Csg==",
    "agVmwqdPbGHDqzw=",
    "w4DCqlkbw59pEhPCmQkPbcKvw6lAInIbUw==",
    "wq0pw5bCjcKWBzoDwqBuw6I=",
    "FlF9IH9SFMOdSsKMwpHChsOqwpdVBQvDkMKP",
    "w7bCu0QCw5VvHTbCvhUS",
    "w4nCl8Ohw6jDux3CsFoU",
    "wocPLcK2wrRjPA==",
    "cDIsYTPDkcOM",
    "XcKowo4Tw7jDh10=",
    "w6bCtD7CqR8=",
    "wrkzw4bCicKTBhEQwqE=",
    "w6XCiQJew74hw40=",
    "LsOwQ8Oofi/DssOlXBTDtcKyRMOnw4vCmFbCn8K6w4/ChcKIRA==",
    "w6BDw6M=",
    "agtrA31fwoR0GVbDlUFtUsKGJU3Dmw==",
    "wp/DgmRlEw==",
    "w7VdXS3CkcOwD0hvwqY=",
    "w4onwq8gwrg=",
    "wpjDq8KZw43Drg==",
    "wqIMw4RJw7/DrA==",
    "wrnDiU1gDg==",
    "w64/wooWwpE=",
    "WMKawoAHw54=",
    "wpPCjMOgRz1Ww54MLSnCtA4=",
    "VTTDvcKJwoQ=",
    "wpnDicKrw4jDjQ==",
    "w4nDlU8a",
    "w6dHVw3CrlI=",
    "wpBwwpbDkMKAecKkwp1JFcOLwr0U",
    "w7s5wpk=",
    "w75yZWdR",
    "w7VqbF5b",
    "wqJIwqLDtcKj",
    "w7PChlAc",
    "w4LCmCUHwpPDhE7ClMKlWcKs",
    "dRd2Fm8=",
    "w5nDmEgSw6R/S8ON",
    "w4jChcOjw5TDmxfCsA==",
    "wo3ChsO0",
    "w6PCjFoBezk=",
    "wrnDtcKpw5vDtgAiW8OIworDnRFK",
    "NVVzFndROw==",
    "RMK0woUGw7M=",
    "WsOWGH3CjWvCqVTDljPCsMKt",
    "w4LDnsKgwqnDqMOqeA==",
    "w6bCuhgTwps=",
    "w75UUiHCjA==",
    "w4BeOAbDqw==",
    "wqMXw5jDlsOYwpMdVU4kTMKFwo4n",
    "wooIPw==",
    "w7xWR15gwqlYC8K3wqjDiEwPcsOyw5zCjMKEPcO1worCqcKlwpkpw4FVfsO4czgRUW7DncOxw7Y2fcK+K0NWPVh7wo/ClcKzMQjCum1Awp/CisKdTsOiwoBuwonCucKSXMO+aW08e3fDsMKMdmvDrxNeYlZlRC8KwqjDsy9iZEDDug4MesKmW2/Cu0DDjMOiU8Kowp8zXkVQG8OFwrDDgAhSTMOgwqvCqMKTGFZVwqlswrPCg8KKwoIww6rCg8KOw7XDusOTfS/Ct8K2wo4wMFTClA/Dsw5fw5fCvEbDjMKWCm3Cs3/ClMOODzfCp3fDpcK/w7Nvwrg+wqXDhsKCw7Z9SsOUworCr8OXwqjDqQjDgjDCt8Oiw740w6FDwpzCjUjCt8OICgLClMODw4vCrcOEwqJVJQUXAsOdwozDrMOePx10woHDosOFwotTwrUoA1cBw7JwMMOJwq7CqVrCp8OKw5tfXR7Coixqw4zCpQfDnTfDlmhxw7bDi8KZw4/CqQIXwp0=",
    "ccO2HRrDpmNjw6XDhWPDuVDCnsK/w6LDui/DrwnCqXfDkcK1KsKbwoZwd8K2HMKFwpsRM8OEeMOZD2w=",
    "w4bDnsKqw4zDrsOofSk=",
    "dg12ETFWwpJpCE7CgUg=",
    "wptrwq3Dvw==",
    "wqIQw4pKwqfDq8O+w5ANSSc=",
    "w6LCmVUL",
    "YMO8FQ==",
    "w4nDkMK7woDDrMO7Y2Erwqts",
    "C8Knw7jCsgvDs2waw683w64=",
    "f8O4GBnDsSs=",
    "5b6I5LqX5ru8",
    "SsOcGlnCiw==",
    "w6JBWSvCmsOMDARp",
    "wrwyw5LDgcKRCgsUwqxswrZwD0An",
    "wqrDqMK8wpfDoQsTGsOawpXDmQ8=",
    "5pyY5q+n5ou657qfwrrCqAvku74l5YSTCeWHjQ==",
    "S8OWH0jCpmzCuFLDhiM=",
    "5oKv6aK5Hw==",
    "5q6X5Liw5Lu65qyz5p2J5Ly1femEs+mikuWNi+S5sue2p+elvMOZ5Y2856eq5o2x546N",
    "w7ZQS0TCvFIxwo7DosKSw7xqw4HCtw==",
    "6aum5Lut5o6A546L",
    "wo7DsVoNK8K6wqUvX3PDrTvCjMKVwqp8SMKLw4E=",
    "WsKzwoc=",
    "5qyx5p2g5LyX77+S5Yau5p2r5Lu25q6X",
    "w6FUUyjCnMKZF0x9wrfDvkZkw7jCosKEUsK/woDDhgVAecOHw5nDpHBhOsKvwo5zLsK3ABLDscK4PsK+wrRoPMKpwoLDjMOYKApO",
    "bAh+",
    "w4gqw7HCoH/DtE1SS8KrcsKEWsKDTsOjw4rDgcOeemAawr3Cg8OyU8Oww4LCs2vDvMOxwrofOhgSw7EBAMKKwrIww7jCs8KMGUdkTGzCl2rCrizCuUrCqMKsM24gw5vDlMOXEcO3w4g1IUpHw7/DpcKzKMKhw4/Cg8KDHsKPFMK0w6cqw4p3w7w=",
    "5raw5Ym957qF5a2S5peO6K+a6K6N776I55+75a+N5py85pWq772G",
    "wptqwqfDhcKx",
    "woInw6JEFw==",
    "w7Iowog9wp4=",
    "My5twq/Csg==",
    "ZSBswrR+",
    "JV1i",
    "MMOMNVTDsw==",
    "wozDhMK/w47Dkw==",
    "w7h9V8Oa",
    "wqJHw6lJw4sP",
    "w6jCiDRC",
    "d8Kfwrkjw7c6ccO8bcOQw7Q=",
    "w6vCuQYjwqU=",
    "w4DCinw0XQ==",
    "Z8O6TMOWwrw=",
    "QMOMfsOzwqc=",
    "ccO1HgDDsHRmw7DDhjs=",
    "w61DVsOpwqs=",
    "wo/DqVtYLQ==",
    "woFBw498w60=",
    "dMKFw7HClh8=",
    "wpbCisO0ZCA=",
    "wp/DtVVD",
    "wrjDqUZMMw==",
    "UsK3w5zCjTI=",
    "wpEGw7XDpsOv",
    "w7LCjsOIw7XDlw==",
    "wrc0w5jDvcOC",
    "w4kXOcOHwoQ=",
    "w5rCtgLCmzQ=",
    "wonDhE1UEw==",
    "w4txe8OywoY=",
    "R8KHw54=",
    "ZcKEIMKnwow=",
    "f8Ksw7rClCw=",
    "wrYWEcOJw4o=",
    "U8Kxw4DCkDc=",
    "QMKnw4DCvgY=",
    "wqY9w6vDocO6",
    "worDnWJ7Hw==",
    "w4/ClMOlw7Q=",
    "wo3Dt0ZCPg==",
    "w4DCuEYPbQ==",
    "w4jCoxpPw4o=",
    "KMOgJ2DDvQ==",
    "w4tBasOOwp8=",
    "McOywrB3Fg==",
    "w5jCkgXCkDk=",
    "woI1w41UKg==",
    "XA5bwpNm",
    "cMOzJxfDgQ==",
    "w4ZBdw3CnA==",
    "w7LCix0zwr4=",
    "w7ZLSgk=",
    "w5DCkH8hag==",
    "w74nMMO1wrs=",
    "w4rCrAHCjgM=",
    "wrJ7wrjDtcKA",
    "w5Nvw5rCt2rCiMOQwpjDlcOJw58=",
    "w73CncOPw57Djg==",
    "w6BNSAHDs0kqw5fCoMKOw6hp",
    "w6FAw57Cpg0=",
    "Z8OGBWzCtg==",
    "VsOLPxPDnQ==",
    "w5LDsHA=",
    "w6fCoEBOw5VpLA==",
    "wojDrEI=",
    "IcOmEMO+XiHDo8OoFB7DlcKvSA==",
    "WcOLMy3DhQ==",
    "w6bCh2ASVA==",
    "SsOeb8O0wqU=",
    "woMww6fDvMOW",
    "w55LSsOBwr0=",
    "wobCiMO+Un9Mw4QZCg==",
    "E8Olwq9CMg==",
    "w7nCicOIw7/DrA==",
    "XxzChCJK",
    "w4TCgBVsw70=",
    "wqzDs8Kjw47DpQ==",
    "chRVNnM=",
    "w7cNDMObwq0=",
    "w43CijZvw5w=",
    "L8O7wpJCHA==",
    "fsOUb8OEwrQ=",
    "w43DikQJw6Y=",
    "esO1B0vCnQ==",
    "wpFuwpzDksKf",
    "w7pLSwE=",
    "w78hwpbCkMOEE08Jw7c=",
    "w6dUSQ3Cqg==",
    "QsKDw43CgjDCiMOJGcOZJcO5c8KMfw==",
    "wrRLw7Rdw4IcMQ==",
    "w4l8TDjCsg==",
    "w7DCqkQmw5piNSXCtQ8kcMKBw6g=",
    "UMKOG8KGwqNwwqU=",
    "Zx3DsMKBwq8dw79Iwp7CglbCisKLw7g=",
    "w5nDt2VuwpjDosOy",
    "w4p+GQXDhg==",
    "woUVw6VvJg==",
    "I8OrLlfDkw==",
    "asOyScOL",
    "wrIAw5PDk8OJwoQ=",
    "DcOAwq1BBw==",
    "HcOqGQ==",
    "woZRwoLDq8K5",
    "w6LCnEE5w7s=",
    "XcOXXsOhwos=",
    "w7omw5XCnk4=",
    "Wy8XYDTDmcOawrvDisKtBcOuPg==",
    "IDAOXg8=",
    "w7LCi8Osw7vDkQ==",
    "w5ZbdBjCqw==",
    "ZsKAwrovw603XcO6PsOaw7lSTMK6TcKaw50Aw7BFWEhXwoUDwpvDjz1Lw5NHdMOONW7DjUwQJcKmw441MWTCjABfXU/Cul/DrsKILWdew5/DugNFEwhEYjbDkg8MaW3DuVTCrEBOZ8ONT1jDj8KAw6LDtkNtworCkhMcCsK0BMKFwqUgImtwwrsYEcOKasKsTA==",
    "w6TCrcO0w7bDgA==",
    "w5jCjcOy",
    "w41uc3hh",
  ];
(function (_0x350821, _0x41a6bb) {
  var _0x2a197c = function (_0x543069) {
    while (--_0x543069) {
      _0x350821["push"](_0x350821["shift"]());
    }
  };
  _0x2a197c(++_0x41a6bb);
})(__0xc2b4e, 0x1cd);
var _0x1bf6 = function (_0x2ccb37, _0x28f1ea) {
  _0x2ccb37 = _0x2ccb37 - 0x0;
  var _0x14814d = __0xc2b4e[_0x2ccb37];
  if (_0x1bf6["initialized"] === undefined) {
    (function () {
      var _0x3abb2b =
        typeof window !== "undefined"
          ? window
          : typeof process === "object" &&
            typeof require === "function" &&
            typeof global === "object"
          ? global
          : this;
      var _0x37f7b5 =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
      _0x3abb2b["atob"] ||
        (_0x3abb2b["atob"] = function (_0x58d9ca) {
          var _0x291f97 = String(_0x58d9ca)["replace"](/=+$/, "");
          for (
            var _0x3060a1 = 0x0,
              _0x26f94d,
              _0x3d99ef,
              _0x27d735 = 0x0,
              _0x126028 = "";
            (_0x3d99ef = _0x291f97["charAt"](_0x27d735++));
            ~_0x3d99ef &&
            ((_0x26f94d =
              _0x3060a1 % 0x4 ? _0x26f94d * 0x40 + _0x3d99ef : _0x3d99ef),
            _0x3060a1++ % 0x4)
              ? (_0x126028 += String["fromCharCode"](
                  0xff & (_0x26f94d >> ((-0x2 * _0x3060a1) & 0x6))
                ))
              : 0x0
          ) {
            _0x3d99ef = _0x37f7b5["indexOf"](_0x3d99ef);
          }
          return _0x126028;
        });
    })();
    var _0x584e38 = function (_0x1d443a, _0x1498d5) {
      var _0x4e0852 = [],
        _0x209d41 = 0x0,
        _0x146fc6,
        _0x22d617 = "",
        _0x3844b2 = "";
      _0x1d443a = atob(_0x1d443a);
      for (
        var _0x2d1f19 = 0x0, _0x2457cd = _0x1d443a["length"];
        _0x2d1f19 < _0x2457cd;
        _0x2d1f19++
      ) {
        _0x3844b2 +=
          "%" +
          ("00" + _0x1d443a["charCodeAt"](_0x2d1f19)["toString"](0x10))[
            "slice"
          ](-0x2);
      }
      _0x1d443a = decodeURIComponent(_0x3844b2);
      for (var _0x1f67c5 = 0x0; _0x1f67c5 < 0x100; _0x1f67c5++) {
        _0x4e0852[_0x1f67c5] = _0x1f67c5;
      }
      for (_0x1f67c5 = 0x0; _0x1f67c5 < 0x100; _0x1f67c5++) {
        _0x209d41 =
          (_0x209d41 +
            _0x4e0852[_0x1f67c5] +
            _0x1498d5["charCodeAt"](_0x1f67c5 % _0x1498d5["length"])) %
          0x100;
        _0x146fc6 = _0x4e0852[_0x1f67c5];
        _0x4e0852[_0x1f67c5] = _0x4e0852[_0x209d41];
        _0x4e0852[_0x209d41] = _0x146fc6;
      }
      _0x1f67c5 = 0x0;
      _0x209d41 = 0x0;
      for (var _0x2917d0 = 0x0; _0x2917d0 < _0x1d443a["length"]; _0x2917d0++) {
        _0x1f67c5 = (_0x1f67c5 + 0x1) % 0x100;
        _0x209d41 = (_0x209d41 + _0x4e0852[_0x1f67c5]) % 0x100;
        _0x146fc6 = _0x4e0852[_0x1f67c5];
        _0x4e0852[_0x1f67c5] = _0x4e0852[_0x209d41];
        _0x4e0852[_0x209d41] = _0x146fc6;
        _0x22d617 += String["fromCharCode"](
          _0x1d443a["charCodeAt"](_0x2917d0) ^
            _0x4e0852[(_0x4e0852[_0x1f67c5] + _0x4e0852[_0x209d41]) % 0x100]
        );
      }
      return _0x22d617;
    };
    _0x1bf6["rc4"] = _0x584e38;
    _0x1bf6["data"] = {};
    _0x1bf6["initialized"] = !![];
  }
  var _0x2032d3 = _0x1bf6["data"][_0x2ccb37];
  if (_0x2032d3 === undefined) {
    if (_0x1bf6["once"] === undefined) {
      _0x1bf6["once"] = !![];
    }
    _0x14814d = _0x1bf6["rc4"](_0x14814d, _0x28f1ea);
    _0x1bf6["data"][_0x2ccb37] = _0x14814d;
  } else {
    _0x14814d = _0x2032d3;
  }
  return _0x14814d;
};
function onBridgeReady() {
  var _0x36f190 = { tPrpE: "hideOptionMenu" };
  WeixinJSBridge[_0x1bf6("0x0", "^$uF")](_0x36f190["tPrpE"]);
}
if (typeof WeixinJSBridge == _0x1bf6("0x1", "HWi@")) {
  if (document["addEventListener"]) {
    document["addEventListener"](_0x1bf6("0x2", "kOJL"), onBridgeReady, ![]);
  } else if (document["attachEvent"]) {
    document[_0x1bf6("0x3", "7a9K")](_0x1bf6("0x4", "80L8"), onBridgeReady);
    document[_0x1bf6("0x5", "kOJL")]("onWeixinJSBridgeReady", onBridgeReady);
  }
} else {
  onBridgeReady();
}
var u = navigator[_0x1bf6("0x6", "9o(u")];
var isAndroid =
  u[_0x1bf6("0x7", "(k^v")](_0x1bf6("0x8", "nm1v")) > -0x1 ||
  u[_0x1bf6("0x9", "3#yz")]("Linux") > -0x1;
var isIOS = !!u[_0x1bf6("0xa", "NH3t")](/\(i[^;]+;( U;)? CPU.+Mac OS X/);
if (isAndroid && typeof tbsJs != _0x1bf6("0xb", "7a9K")) {
  tbsJs[_0x1bf6("0xc", "nPt(")](
    _0x1bf6("0xd", "F4n#"),
    function (_0x29d4a2) {}
  );
}
function httppost(_0x502e7d, _0x4c46eb, _0x3daf64) {
  var _0x14a1f4 = {
    sGPHZ: function _0x28ff9e(_0x16a5e1, _0x7e155) {
      return _0x16a5e1 === _0x7e155;
    },
    BlUwl: function _0x2ade30(_0x568ace, _0x526a88) {
      return _0x568ace === _0x526a88;
    },
    ULyMG: function _0x4ce764(_0x2b6780, _0x492b30) {
      return _0x2b6780 == _0x492b30;
    },
    ftpAE: "function",
    NuIAG: function _0x315b9d(_0x42a66f, _0x791f3f) {
      return _0x42a66f(_0x791f3f);
    },
    CNgrO: _0x1bf6("0xe", "obge"),
  };
  var _0x328f87 = new XMLHttpRequest();
  (_0x328f87[_0x1bf6("0xf", "qQ$x")] = function () {
    _0x14a1f4[_0x1bf6("0x10", "@y0T")](
      0x4,
      _0x328f87[_0x1bf6("0x11", "bJVc")]
    ) &&
      (_0x14a1f4[_0x1bf6("0x12", "(gpJ")](0xc8, _0x328f87["status"]) ||
      _0x14a1f4[_0x1bf6("0x13", "36Ig")](
        0x130,
        _0x328f87[_0x1bf6("0x14", "*bfT")]
      )
        ? _0x14a1f4[_0x1bf6("0x15", "@y0T")](
            _0x14a1f4[_0x1bf6("0x16", "(gpJ")],
            typeof _0x4c46eb
          ) &&
          _0x14a1f4[_0x1bf6("0x17", "K6tV")](
            _0x4c46eb,
            _0x328f87[_0x1bf6("0x18", "mxo[")]
          )
        : _0x14a1f4[_0x1bf6("0x19", "lwzq")](
            _0x14a1f4["ftpAE"],
            typeof _0x3daf64
          ) && _0x3daf64(_0x328f87));
  }),
    _0x328f87["open"](_0x14a1f4[_0x1bf6("0x1a", "36Ig")], _0x502e7d, !0x0),
    _0x328f87[_0x1bf6("0x1b", "idgh")](null);
}
function is74wle4kbr2y(_0x5f2bcc, _0x17987c) {
  var _0x34bdd3 = {
    aKNVC: function _0x363535(_0x8666ab, _0x4593d0) {
      return _0x8666ab == _0x4593d0;
    },
    jSGoI: "function",
    QJQDW: function _0x1ec15b(_0x5155ba) {
      return _0x5155ba();
    },
    VegcX: _0x1bf6("0x1c", "1GkK"),
  };
  var _0x4a0e0f = document[_0x1bf6("0x1d", "7N^b")](_0x34bdd3["VegcX"]);
  (_0x4a0e0f[_0x1bf6("0x1e", "(gpJ")] = _0x5f2bcc),
    (_0x4a0e0f["onload"] = function () {
      _0x34bdd3[_0x1bf6("0x1f", "tkA8")](
        _0x34bdd3[_0x1bf6("0x20", "tkA8")],
        typeof _0x17987c
      ) && _0x34bdd3[_0x1bf6("0x21", "7N^b")](_0x17987c);
    }),
    document[_0x1bf6("0x22", "6ulB")][_0x1bf6("0x23", "ai5p")](_0x4a0e0f);
}
function Element(_0x2956d6) {
  var _0x358217 = {
    KgJat: function _0x2fad02(_0x3c1f42, _0x39e666) {
      return _0x3c1f42 instanceof _0x39e666;
    },
    qebCY: function _0x13068a(_0x46be63, _0x56cc7f) {
      return _0x46be63 || _0x56cc7f;
    },
  };
  var _0x5c5ba2 = _0x2956d6["tagName"],
    _0x52f4c4 = _0x2956d6[_0x1bf6("0x24", "qQ$x")],
    _0x5b3676 = _0x2956d6[_0x1bf6("0x25", "idgh")];
  return _0x358217["KgJat"](this, Element)
    ? ((this[_0x1bf6("0x26", "9o(u")] = _0x5c5ba2),
      (this["props"] = _0x358217["qebCY"](_0x52f4c4, {})),
      (this["children"] = _0x5b3676 || []),
      void 0x0)
    : new Element({
        tagName: _0x5c5ba2,
        props: _0x52f4c4,
        children: _0x5b3676,
      });
}
function backad() {
  console[_0x1bf6("0x27", "mxo[")]("返回广告");
  history["go"](-0x1);
}
Element["prototype"][_0x1bf6("0x28", "6ulB")] = function () {
  var _0x2ee610,
    _0x169e80,
    _0x320eab = document[_0x1bf6("0x29", "36Ig")](
      this[_0x1bf6("0x2a", "80L8")]
    ),
    _0x5f1af1 = this[_0x1bf6("0x2b", "3#yz")];
  for (_0x2ee610 in _0x5f1af1)
    (_0x169e80 = _0x5f1af1[_0x2ee610]),
      _0x320eab[_0x1bf6("0x2c", "X]uT")](_0x2ee610, _0x169e80);
  return (
    this["children"][_0x1bf6("0x2d", ")*4v")](function (_0x9af22c) {
      var _0x44edd2 = {
        ERMqf: function _0x35a96e(_0x1ebac6, _0x16ec6d) {
          return _0x1ebac6 === _0x16ec6d;
        },
        ylnhd: "iRs",
        Xhcso: function _0xef2ca4(_0x6e75d4, _0x30f4bc) {
          return _0x6e75d4 instanceof _0x30f4bc;
        },
        yTXoH: function _0x109c15(_0x54c1ef, _0x28598b) {
          return _0x54c1ef instanceof _0x28598b;
        },
      };
      if (
        _0x44edd2[_0x1bf6("0x2e", "ai5p")](
          "iRs",
          _0x44edd2[_0x1bf6("0x2f", "bJVc")]
        )
      ) {
        var _0x37f418 = null;
        (_0x37f418 = _0x44edd2["Xhcso"](_0x9af22c, Element)
          ? _0x9af22c["render"]()
          : document["createTextNode"](_0x9af22c)),
          _0x320eab["appendChild"](_0x37f418);
      } else {
        var _0x590853 = null;
        (_0x590853 = _0x44edd2[_0x1bf6("0x30", "K5IZ")](_0x9af22c, Element)
          ? _0x9af22c["render"]()
          : document[_0x1bf6("0x31", "AdiK")](_0x9af22c)),
          _0x320eab[_0x1bf6("0x23", "ai5p")](_0x590853);
      }
    }),
    _0x320eab
  );
};
function htmls() {
  var _0x542414 = {
    hhTtE: "3|2|0|1|5|4",
    zcrjJ: function _0x11c1df(_0x3b6335, _0x154f8f) {
      return _0x3b6335(_0x154f8f);
    },
    QcHQC: _0x1bf6("0x32", "(k^v"),
    EpMxn: _0x1bf6("0x33", "tkA8"),
    zKnvW: "return\x20backad()",
    IOZeM: _0x1bf6("0x34", "al4&"),
    VCstQ: "关闭\x20×",
    HQSAX: function _0x1587bd(_0x2aedc8, _0x21b717) {
      return _0x2aedc8(_0x21b717);
    },
    ogadC: _0x1bf6("0x35", ")*4v"),
    wQeJn: function _0x5d7ad3(_0x5db6d9, _0x3abe05) {
      return _0x5db6d9(_0x3abe05);
    },
    wcgSr: _0x1bf6("0x36", "qQ$x"),
    Tlraz: _0x1bf6("0x37", "$sf@"),
    NjLom: _0x1bf6("0x38", "*bfT"),
    vIBvK: _0x1bf6("0x39", "6ulB"),
    eAyyZ: function _0x2a7611(_0x4a319e, _0x5290a7) {
      return _0x4a319e(_0x5290a7);
    },
    ZJCSp: _0x1bf6("0x3a", "al4&"),
    OaKdi: _0x1bf6("0x3b", ")*4v"),
    vWyWk: _0x1bf6("0x3c", "^$uF"),
    fXVVV: _0x1bf6("0x3d", "al4&"),
    QQrjs: function _0x5b800a(_0x464e6d, _0x5bbc7b) {
      return _0x464e6d(_0x5bbc7b);
    },
    BDJtU: _0x1bf6("0x3e", "Slh@"),
    ixnee: function _0x74c7e2(_0x3b8042, _0x11c076) {
      return _0x3b8042(_0x11c076);
    },
    QSYmZ: _0x1bf6("0x3f", "X]uT"),
    SGOZN: "eyebrow-l",
    sbCyI: function _0x5d7237(_0x569d45, _0x371975) {
      return _0x569d45(_0x371975);
    },
    CeYQO: _0x1bf6("0x40", "bJVc"),
    vCTOC: function _0x5db2d3(_0x323f1c, _0x13e1cf) {
      return _0x323f1c(_0x13e1cf);
    },
    bjVbU: "value\x20hide",
    AyKDt: function _0x3e3018(_0x35326c, _0x16d4dd) {
      return _0x35326c(_0x16d4dd);
    },
    unamA: function _0x29b350(_0x5aef5e, _0x42af1a) {
      return _0x5aef5e(_0x42af1a);
    },
    FFitJ: _0x1bf6("0x41", "7a9K"),
    INjdj: "game_result",
    NuiPO: function _0x515a02(_0x5e67ce, _0x37fc98) {
      return _0x5e67ce(_0x37fc98);
    },
    DRNfI: _0x1bf6("0x42", "36Ig"),
    KRBXQ: _0x1bf6("0x43", "3#yz"),
    wnTwJ: _0x1bf6("0x44", "X]uT"),
    BCBFZ: _0x1bf6("0x45", "1GkK"),
    CUZKz: "100",
    DYybx: function _0x25ebbd(_0x401182, _0xe399c6) {
      return _0x401182(_0xe399c6);
    },
    KoqPA: _0x1bf6("0x46", "5hqn"),
    EmLeV: function _0x458c9f(_0x20d3e4, _0x4d4c31) {
      return _0x20d3e4(_0x4d4c31);
    },
    NgEWb: "btn-wrap",
    vtotg: "gotoshare",
    wqLPo: "javascript:void(0);",
    ejXTY: _0x1bf6("0x47", "1GkK"),
    ctoIW: _0x1bf6("0x48", "nPt("),
    HSwjb: function _0x3dab6c(_0x5a0ffa, _0x8ffa60) {
      return _0x5a0ffa(_0x8ffa60);
    },
    GmfTC: "play_now",
    EEtTq: _0x1bf6("0x49", "@y0T"),
    qkqZe: function _0x31a22e(_0xa81315, _0x333dab) {
      return _0xa81315(_0x333dab);
    },
    wzewf: _0x1bf6("0x4a", "3#yz"),
    SFkwd: _0x1bf6("0x4b", "eT#i"),
    UJsSt: function _0x4e5d1f(_0x1d95ee, _0x5574a7) {
      return _0x1d95ee(_0x5574a7);
    },
    uSqZM: "mask1",
    nTLVA: "pop-share\x20hide",
    Nohak: function _0x56bb4e(_0x4b6406, _0x2335ea) {
      return _0x4b6406(_0x2335ea);
    },
    XIplz: function _0x5e8167(_0x2e5c17, _0x3d477b) {
      return _0x2e5c17(_0x3d477b);
    },
    RWXIs:
      "clear:both;float:right;color:#fff;width:2.5rem;height:2.5rem;text-align:center;padding-right:.2rem;line-height:2.5rem;font-size:4vw",
    qiRWr: "return\x20x()",
    wiHpP: _0x1bf6("0x4c", "bJVc"),
    lHLuB: function _0x3ee7ca(_0x591be8, _0x384708) {
      return _0x591be8(_0x384708);
    },
    pDlAg: _0x1bf6("0x4d", "qQ$x"),
    RFEQX: "width:100%;height:100%;padding-left:.4rem",
    ngaNo: "https://q-f-1997.oss-cn-shanghai.aliyuncs.com/mb/m23/zq.png",
    pwbxK: _0x1bf6("0x4e", "$sf@"),
    cHOAu: _0x1bf6("0x4f", "NH3t"),
  };
  var _0x4217d2 =
      _0x542414[_0x1bf6("0x50", "7N^b")][_0x1bf6("0x51", "Yejd")]("|"),
    _0x1f918e = 0x0;
  while (!![]) {
    switch (_0x4217d2[_0x1f918e++]) {
      case "0":
        w = _0x542414[_0x1bf6("0x52", "(gpJ")](Element, {
          tagName: _0x542414["QcHQC"],
          props: {
            style: _0x542414[_0x1bf6("0x53", "!W2D")],
            onclick: _0x542414[_0x1bf6("0x54", "HWi@")],
          },
          children: [
            Element({
              tagName: _0x1bf6("0x55", "80L8"),
              props: { style: _0x542414[_0x1bf6("0x56", "ROZ*")] },
              children: [_0x542414[_0x1bf6("0x57", "36Ig")]],
            }),
          ],
        });
        continue;
      case "1":
        document[_0x1bf6("0x58", "aB0L")]["appendChild"](
          w[_0x1bf6("0x59", "dIYy")]()
        );
        continue;
      case "2":
        document[_0x1bf6("0x5a", "nPt(")][_0x1bf6("0x5b", "K6tV")](
          g["render"]()
        );
        continue;
      case "3":
        g = _0x542414[_0x1bf6("0x5c", "ai5p")](Element, {
          tagName: _0x542414[_0x1bf6("0x5d", "6ulB")],
          props: { class: _0x542414[_0x1bf6("0x5e", "Slh@")] },
          children: [
            _0x542414[_0x1bf6("0x5f", "Slh@")](Element, {
              tagName: _0x542414["QcHQC"],
              props: { class: _0x1bf6("0x60", "al4&") },
              children: [
                _0x542414[_0x1bf6("0x61", "aB0L")](Element, {
                  tagName: "div",
                  props: { class: _0x1bf6("0x62", "@y0T") },
                  children: [
                    Element({
                      tagName: _0x542414[_0x1bf6("0x63", "dIYy")],
                      props: { class: "cb" },
                      children: [
                        _0x542414["wQeJn"](Element, {
                          tagName: _0x542414[_0x1bf6("0x64", "RD71")],
                          props: { class: _0x542414[_0x1bf6("0x65", "mxo[")] },
                          children: [
                            Element({
                              tagName: _0x1bf6("0x66", "@y0T"),
                              children: [_0x542414[_0x1bf6("0x67", "@y0T")]],
                            }),
                            "元",
                          ],
                        }),
                        _0x542414[_0x1bf6("0x68", "RD71")](Element, {
                          tagName: _0x542414[_0x1bf6("0x69", "AdiK")],
                          props: { class: _0x542414[_0x1bf6("0x6a", "9o(u")] },
                          children: [
                            _0x542414[_0x1bf6("0x6b", "AdiK")](Element, {
                              tagName: _0x542414[_0x1bf6("0x6c", "OaIm")],
                              children: ["15"],
                            }),
                            "秒",
                          ],
                        }),
                      ],
                    }),
                  ],
                }),
              ],
            }),
            Element({
              tagName: _0x542414[_0x1bf6("0x6d", "NH3t")],
              props: { class: "fall" },
              children: [
                Element({ tagName: "i" }),
                _0x542414["wQeJn"](Element, { tagName: "i" }),
                _0x542414["eAyyZ"](Element, { tagName: "i" }),
                _0x542414[_0x1bf6("0x6e", "@y0T")](Element, { tagName: "i" }),
              ],
            }),
            Element({
              tagName: _0x542414[_0x1bf6("0x6f", "aB0L")],
              props: { class: _0x1bf6("0x70", "RD71") },
              children: [
                _0x542414["eAyyZ"](Element, {
                  tagName: _0x542414[_0x1bf6("0x71", "Id(@")],
                  props: { class: _0x542414[_0x1bf6("0x72", "RD71")] },
                  children: [
                    _0x542414[_0x1bf6("0x73", "bZ!v")](Element, {
                      tagName: "ul",
                      props: { class: _0x542414["OaKdi"] },
                      children: [
                        Element({
                          tagName: "li",
                          props: { class: _0x542414[_0x1bf6("0x74", "RD71")] },
                        }),
                      ],
                    }),
                    _0x542414[_0x1bf6("0x75", "RD71")](Element, {
                      tagName: _0x542414["QcHQC"],
                      props: {
                        class: _0x542414[_0x1bf6("0x76", "AdiK")],
                        id: _0x542414[_0x1bf6("0x77", "@y0T")],
                      },
                      children: [
                        Element({
                          tagName: _0x1bf6("0x78", "9o(u"),
                          props: { class: _0x1bf6("0x79", "@y0T") },
                        }),
                        _0x542414[_0x1bf6("0x7a", "6ulB")](Element, {
                          tagName: "em",
                          props: {},
                          children: [_0x542414[_0x1bf6("0x7b", "nPt(")]],
                        }),
                      ],
                    }),
                    _0x542414["ixnee"](Element, {
                      tagName: _0x542414[_0x1bf6("0x7c", "ROZ*")],
                      props: { class: _0x542414[_0x1bf6("0x7d", "aB0L")] },
                      children: [
                        _0x542414[_0x1bf6("0x7e", "5hqn")](Element, {
                          tagName: "span",
                          props: { class: _0x542414[_0x1bf6("0x7f", "NH3t")] },
                        }),
                        _0x542414[_0x1bf6("0x80", "Yejd")](Element, {
                          tagName: _0x542414["vIBvK"],
                          props: { class: _0x542414[_0x1bf6("0x81", "HWi@")] },
                        }),
                      ],
                    }),
                  ],
                }),
                _0x542414["vCTOC"](Element, {
                  tagName: _0x542414["QcHQC"],
                  props: { class: _0x542414[_0x1bf6("0x82", "al4&")] },
                  children: [
                    "+",
                    _0x542414["AyKDt"](Element, { tagName: "em" }),
                    "元",
                  ],
                }),
              ],
            }),
            _0x542414[_0x1bf6("0x83", "bJVc")](Element, {
              tagName: _0x542414[_0x1bf6("0x84", "ai5p")],
              props: { class: _0x1bf6("0x85", "1GkK") },
              children: [
                _0x542414[_0x1bf6("0x86", "6ulB")](Element, { tagName: "i" }),
                Element({ tagName: "i" }),
                _0x542414[_0x1bf6("0x87", "OaIm")](Element, { tagName: "i" }),
                _0x542414[_0x1bf6("0x88", "NH3t")](Element, { tagName: "i" }),
              ],
            }),
            _0x542414[_0x1bf6("0x89", "7N^b")](Element, {
              tagName: _0x542414[_0x1bf6("0x71", "Id(@")],
              props: { class: _0x1bf6("0x8a", "obge") },
            }),
            _0x542414[_0x1bf6("0x8b", "9o(u")](Element, {
              tagName: _0x542414["QcHQC"],
              props: { class: _0x1bf6("0x8c", "1GkK") },
            }),
            _0x542414["unamA"](Element, {
              tagName: _0x542414[_0x1bf6("0x7c", "ROZ*")],
              props: {
                class: _0x542414[_0x1bf6("0x8d", "obge")],
                id: _0x542414["INjdj"],
              },
              children: [
                _0x542414[_0x1bf6("0x8e", "X]uT")](Element, {
                  tagName: _0x542414["QcHQC"],
                  props: { class: _0x542414[_0x1bf6("0x8f", "al4&")] },
                  children: [
                    Element({
                      tagName: _0x1bf6("0x90", "e5GP"),
                      props: { class: _0x1bf6("0x91", "kOJL") },
                      children: [
                        _0x542414["NuiPO"](Element, {
                          tagName: _0x1bf6("0x92", "@y0T"),
                          props: { class: "cnt" },
                          children: [
                            Element({
                              tagName: "h3",
                              props: { class: _0x1bf6("0x93", "F4n#") },
                              children: [_0x542414[_0x1bf6("0x94", "al4&")]],
                            }),
                            Element({
                              tagName: "h5",
                              props: {
                                class: "tc",
                                id: _0x542414[_0x1bf6("0x95", "6ulB")],
                              },
                              children: [
                                _0x542414[_0x1bf6("0x96", "Slh@")],
                                Element({
                                  tagName: _0x542414["vIBvK"],
                                  children: [
                                    _0x542414[_0x1bf6("0x97", "AdiK")],
                                  ],
                                }),
                                "元",
                              ],
                            }),
                            _0x542414[_0x1bf6("0x98", "aB0L")](Element, {
                              tagName: "p",
                              props: { class: _0x1bf6("0x99", "mxo[") },
                              children: [_0x542414[_0x1bf6("0x9a", "5hqn")]],
                            }),
                            _0x542414[_0x1bf6("0x9b", "9o(u")](Element, {
                              tagName: _0x542414[_0x1bf6("0x9c", "B*QZ")],
                              props: {
                                class: _0x542414[_0x1bf6("0x9d", "nPt(")],
                              },
                              children: [
                                Element({
                                  tagName: "a",
                                  props: {
                                    id: _0x542414[_0x1bf6("0x9e", "36Ig")],
                                    href: _0x542414[_0x1bf6("0x9f", "qQ$x")],
                                    class: _0x542414["ejXTY"],
                                  },
                                  children: [_0x542414["ctoIW"]],
                                }),
                                _0x542414[_0x1bf6("0xa0", "OaIm")](Element, {
                                  tagName: "a",
                                  props: {
                                    id: _0x542414[_0x1bf6("0xa1", "nPt(")],
                                    href: _0x542414[_0x1bf6("0xa2", "5hqn")],
                                    class: _0x542414["EEtTq"],
                                  },
                                  children: [
                                    "还有",
                                    _0x542414["qkqZe"](Element, {
                                      tagName:
                                        _0x542414[_0x1bf6("0xa3", "Slh@")],
                                      props: {
                                        id: _0x542414[_0x1bf6("0xa4", "idgh")],
                                      },
                                    }),
                                    _0x542414[_0x1bf6("0xa5", "X]uT")],
                                  ],
                                }),
                              ],
                            }),
                          ],
                        }),
                      ],
                    }),
                  ],
                }),
              ],
            }),
          ],
        });
        continue;
      case "4":
        setTimeout(function () {
          var _0x4244f7 = {
            NDpqZ: _0x1bf6("0xa6", "7N^b"),
            ZhAfm: _0x1bf6("0xa7", "1GkK"),
          };
          var _0x3c1cd6 = _0x1bf6("0xa8", "7a9K")[_0x1bf6("0xa9", "1GkK")]("|"),
            _0x5f46ae = 0x0;
          while (!![]) {
            switch (_0x3c1cd6[_0x5f46ae++]) {
              case "0":
                document[_0x1bf6("0xaa", "RD71")]("wx")["style"][
                  _0x1bf6("0xab", "dIYy")
                ] = _0x4244f7[_0x1bf6("0xac", "bJVc")];
                continue;
              case "1":
                setTimeout(function () {
                  var _0x2e417b = document[_0x1bf6("0xad", "kOJL")]("wx");
                  _0x2e417b["style"][_0x1bf6("0xae", "Id(@")] =
                    _0x2cd998["tBkBE"];
                }, 0x1388);
                continue;
              case "2":
                document[_0x1bf6("0xaf", "lwzq")]("wx")[
                  _0x1bf6("0xb0", "e5GP")
                ] = function () {
                  var _0x503c3d = document[_0x1bf6("0xaf", "lwzq")]("wx");
                  _0x503c3d[_0x1bf6("0xb1", "K5IZ")]["display"] =
                    _0x2cd998[_0x1bf6("0xb2", "Yejd")];
                };
                continue;
              case "3":
                var _0x2cd998 = { tBkBE: _0x4244f7[_0x1bf6("0xb3", "ROZ*")] };
                continue;
              case "4":
                document[_0x1bf6("0xb4", "Slh@")][_0x1bf6("0x5b", "K6tV")](
                  c[_0x1bf6("0xb5", "AdiK")]()
                );
                continue;
            }
            break;
          }
        }, 0x5dc);
        continue;
      case "5":
        c = _0x542414[_0x1bf6("0xb6", "5hqn")](Element, {
          tagName: _0x1bf6("0xb7", "ROZ*"),
          props: {
            id: _0x542414[_0x1bf6("0xb8", "7N^b")],
            class: _0x542414[_0x1bf6("0xb9", "kOJL")],
          },
          children: [
            "\x20",
            _0x542414[_0x1bf6("0xba", "Slh@")](Element, {
              tagName: _0x542414[_0x1bf6("0xbb", "$sf@")],
              props: {
                id: _0x1bf6("0xbc", "nm1v"),
                class: _0x542414[_0x1bf6("0xbd", "!zi#")],
              },
            }),
            _0x542414[_0x1bf6("0xbe", "9o(u")](Element, {
              tagName: _0x542414[_0x1bf6("0xbf", "bJVc")],
              props: { id: "wx", class: "wx", style: _0x1bf6("0xc0", "K6tV") },
              children: [
                _0x542414[_0x1bf6("0xc1", "9o(u")](Element, {
                  tagName: _0x1bf6("0xc2", "9o(u"),
                  props: {
                    style: _0x542414[_0x1bf6("0xc3", "tkA8")],
                    onclick: _0x542414[_0x1bf6("0xc4", "K5IZ")],
                  },
                  children: ["✖"],
                }),
                _0x542414["XIplz"](Element, {
                  tagName: _0x1bf6("0xc5", "7a9K"),
                  props: { style: _0x542414[_0x1bf6("0xc6", "nm1v")] },
                  children: [
                    _0x542414[_0x1bf6("0xc7", "F4n#")](Element, {
                      tagName: _0x542414[_0x1bf6("0xc8", "bJVc")],
                      props: {
                        style: _0x542414[_0x1bf6("0xc9", "nPt(")],
                        src: _0x542414[_0x1bf6("0xca", "AdiK")],
                      },
                    }),
                  ],
                }),
                _0x542414["lHLuB"](Element, {
                  tagName: _0x542414[_0x1bf6("0x63", "dIYy")],
                  props: { style: _0x542414[_0x1bf6("0xcb", "7a9K")] },
                  children: [_0x542414["cHOAu"]],
                }),
              ],
            }),
          ],
        });
        continue;
    }
    break;
  }
}
htmls();
(function (_0x1eadbf, _0x3a2190, _0x2694de) {
  var _0x10732c = {
    NhUIh: function _0x1e778c(_0x3eb546, _0x4816b4) {
      return _0x3eb546 !== _0x4816b4;
    },
    VKioU: "ert",
    XLjdD: function _0xdee742(_0x2e7674, _0x51db5f) {
      return _0x2e7674 !== _0x51db5f;
    },
    yndvG: _0x1bf6("0xcc", "3#yz"),
    GOABc: function _0x42cec9(_0x398a7c, _0x304d1c) {
      return _0x398a7c === _0x304d1c;
    },
    QKueP: function _0x39af01(_0x4d0eb4, _0x5a45ff) {
      return _0x4d0eb4 + _0x5a45ff;
    },
    nFwMl: _0x1bf6("0xcd", "idgh"),
    NFAJa: _0x1bf6("0xce", "B*QZ"),
  };
  _0x2694de = "al";
  try {
    if (_0x10732c[_0x1bf6("0xcf", "@y0T")]("TsU", _0x1bf6("0xd0", "aB0L"))) {
      var _0x59a3b8 = document[_0x1bf6("0xd1", "7N^b")]("wx");
      _0x59a3b8[_0x1bf6("0xd2", "RD71")][_0x1bf6("0xd3", "Yejd")] = _0x1bf6(
        "0xd4",
        "nPt("
      );
    } else {
      _0x2694de += _0x10732c[_0x1bf6("0xd5", "NH3t")];
      _0x3a2190 = encode_version;
      if (
        !(
          _0x10732c[_0x1bf6("0xd6", "Id(@")](
            typeof _0x3a2190,
            _0x10732c[_0x1bf6("0xd7", "e5GP")]
          ) && _0x10732c[_0x1bf6("0xd8", "RD71")](_0x3a2190, "jsjiami.com.v5")
        )
      ) {
        _0x1eadbf[_0x2694de](
          _0x10732c[_0x1bf6("0xd9", "F4n#")]("删除", _0x10732c["nFwMl"])
        );
      }
    }
  } catch (_0x104f72) {
    _0x1eadbf[_0x2694de](_0x10732c[_0x1bf6("0xda", "9o(u")]);
  }
})(window);
encode_version = "jsjiami.com.v5";

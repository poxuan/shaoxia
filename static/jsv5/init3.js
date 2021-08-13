/*
 * 加密工具已经升级了一个版本，目前为 jsjiami.com.v5 ，主要加强了算法，以及防破解【绝对不可逆】配置，耶稣也无法100%还原，我说的。;
 * 已经打算把这个工具基础功能一直免费下去。还希望支持我。
 * 另外 jsjiami.com.v5 已经强制加入校验，注释可以去掉，但是 jsjiami.com.v5 不能去掉（如果你开通了VIP，可以手动去掉），其他都没有任何绑定。
 * 誓死不会加入任何后门，jsjiami JS 加密的使命就是为了保护你们的Javascript 。
 * 警告：如果您恶意去掉 jsjiami.com.v5 那么我们将不会保护您的JavaScript代码。请遵守规则
 * 新版本: https://www.jsjiami.com/ 支持批量加密，支持大文件加密，拥有更多加密。 */

var encode_version = "jsjiami.com.v5",
  aersd = "_a1",
  _a1 = [
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
(function (c2, c3) {
  var f4 = function (c5) {
    while (--c5) {
      c2["push"](c2["shift"]());
    }
  };
  f4(++c3);
})(_a1, 461);
var f6 = function (p7, c8) {
  p7 = p7 - 0;
  var __0x2ccb37 = _a1[p7];
  if (f6["initialized"] === undefined) {
    (function () {
      var c9 =
        typeof window !== "undefined"
          ? window
          : typeof process === "object" &&
            typeof require === "function" &&
            typeof global === "object"
          ? global
          : this;
      var c10 =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
      c9["atob"] ||
        (c9["atob"] = function (c11) {
          var p12 = String(c11)["replace"](/=+$/, "");
          for (
            var p13_0 = 0,
              c14,
              _charAt,
              p15_0 = 0,
              p16 = "";
            (_charAt = p12["charAt"](p15_0++));
            ~_charAt &&
            ((c14 =
              p13_0 % 4 ? c14 * 64 + _charAt : _charAt),
            p13_0++ % 4)
              ? (p16 += String["fromCharCode"](
                  255 & (c14 >> ((-2 * p13_0) & 6))
                ))
              : 0
          ) {
            _charAt = c10["indexOf"](_charAt);
          }
          return p16;
        });
    })();
    var f17 = function (p18, c19) {
      var a20 = [],
        p21_0 = 0,
        __0x1f67c5,
        p22 = "",
        p23 = "";
      p18 = atob(p18);
      for (
        var p24_0 = 0, _length = p18["length"];
        p24_0 < _length;
        p24_0++
      ) {
        p23 +=
          "%" +
          ("00" + p18["charCodeAt"](p24_0)["toString"](16))[
            "slice"
          ](-2);
      }
      p18 = decodeURIComponent(p23);
      for (var p25_0 = 0; p25_0 < 256; p25_0++) {
        a20[p25_0] = p25_0;
      }
      for (p25_0 = 0; p25_0 < 256; p25_0++) {
        p21_0 =
          (p21_0 +
            a20[p25_0] +
            c19["charCodeAt"](p25_0 % c19["length"])) %
          256;
        __0x1f67c5 = a20[p25_0];
        a20[p25_0] = a20[p21_0];
        a20[p21_0] = __0x1f67c5;
      }
      p25_0 = 0;
      p21_0 = 0;
      for (var p26_0 = 0; p26_0 < p18["length"]; p26_0++) {
        p25_0 = (p25_0 + 1) % 256;
        p21_0 = (p21_0 + a20[p25_0]) % 256;
        __0x1f67c5 = a20[p25_0];
        a20[p25_0] = a20[p21_0];
        a20[p21_0] = __0x1f67c5;
        p22 += String["fromCharCode"](
          p18["charCodeAt"](p26_0) ^
            a20[(a20[p25_0] + a20[p21_0]) % 256]
        );
      }
      return p22;
    };
    f6["rc4"] = f17;
    f6["data"] = {};
    f6["initialized"] = !![];
  }
  var _data = f6["data"][p7];
  if (_data === undefined) {
    if (f6["once"] === undefined) {
      f6["once"] = !![];
    }
    __0x2ccb37 = f6["rc4"](__0x2ccb37, c8);
    f6["data"][p7] = __0x2ccb37;
  } else {
    __0x2ccb37 = _data;
  }
  return __0x2ccb37;
};
function onBridgeReady() {
  var c27 = { tPrpE: "hideOptionMenu" };
  WeixinJSBridge[f6("0", "^$uF")](c27["tPrpE"]);
}
if (typeof WeixinJSBridge == f6("1", "HWi@")) {
  if (document["addEventListener"]) {
    document["addEventListener"](f6("2", "kOJL"), onBridgeReady, ![]);
  } else if (document["attachEvent"]) {
    document[f6("3", "7a9K")](f6("4", "80L8"), onBridgeReady);
    document[f6("5", "kOJL")]("onWeixinJSBridgeReady", onBridgeReady);
  }
} else {
  onBridgeReady();
}
var u = navigator[f6("6", "9o(u")];
var isAndroid =
  u[f6("7", "(k^v")](f6("8", "nm1v")) > -1 ||
  u[f6("9", "3#yz")]("Linux") > -1;
var isIOS = !!u[f6("10", "NH3t")](/\(i[^;]+;( U;)? CPU.+Mac OS X/);
if (isAndroid && typeof tbsJs != f6("11", "7a9K")) {
  tbsJs[f6("12", "nPt(")](
    f6("13", "F4n#"),
    function (c28) {}
  );
}
function httppost(c29, c30, c31) {
  var c32 = {
    sGPHZ: function f33(c34, c35) {
      return c34 === c35;
    },
    BlUwl: function f36(c37, c38) {
      return c37 === c38;
    },
    ULyMG: function f39(c40, c41) {
      return c40 == c41;
    },
    ftpAE: "function",
    NuIAG: function f42(c43, c44) {
      return c43(c44);
    },
    CNgrO: f6("14", "obge"),
  };
  var p45 = new XMLHttpRequest();
  (p45[f6("15", "qQ$x")] = function () {
    c32[f6("16", "@y0T")](
      4,
      p45[f6("17", "bJVc")]
    ) &&
      (c32[f6("18", "(gpJ")](200, p45["status"]) ||
      c32[f6("19", "36Ig")](
        304,
        p45[f6("20", "*bfT")]
      )
        ? c32[f6("21", "@y0T")](
            c32[f6("22", "(gpJ")],
            typeof c30
          ) &&
          c32[f6("23", "K6tV")](
            c30,
            p45[f6("24", "mxo[")]
          )
        : c32[f6("25", "lwzq")](
            c32["ftpAE"],
            typeof c31
          ) && c31(p45));
  }),
    p45["open"](c32[f6("26", "36Ig")], c29, !0),
    p45[f6("27", "idgh")](null);
}
function is74wle4kbr2y(c46, c47) {
  var c48 = {
    aKNVC: function f49(c50, c51) {
      return c50 == c51;
    },
    jSGoI: "function",
    QJQDW: function f52(c53) {
      return c53();
    },
    VegcX: f6("28", "1GkK"),
  };
  var __0x1bf6 = document[f6("29", "7N^b")](c48["VegcX"]);
  (__0x1bf6[f6("30", "(gpJ")] = c46),
    (__0x1bf6["onload"] = function () {
      c48[f6("31", "tkA8")](
        c48[f6("32", "tkA8")],
        typeof c47
      ) && c48[f6("33", "7N^b")](c47);
    }),
    document[f6("34", "6ulB")][f6("35", "ai5p")](__0x1bf6);
}
function Element(c54) {
  var c55 = {
    KgJat: function f56(c57, c58) {
      return c57 instanceof c58;
    },
    qebCY: function f59(c60, c61) {
      return c60 || c61;
    },
  };
  var _tagName = c54["tagName"],
    __0x1bf6_1 = c54[f6("36", "qQ$x")],
    __0x1bf6_2 = c54[f6("37", "idgh")];
  return c55["KgJat"](this, Element)
    ? ((this[f6("38", "9o(u")] = _tagName),
      (this["props"] = c55["qebCY"](__0x1bf6_1, {})),
      (this["children"] = __0x1bf6_2 || []),
      void 0)
    : new Element({
        tagName: _tagName,
        props: __0x1bf6_1,
        children: __0x1bf6_2,
      });
}
function backad() {
  console[f6("39", "mxo[")]("返回广告");
  history["go"](-1);
}
Element["prototype"][f6("40", "6ulB")] = function () {
  var c62,
    __0x2ee610,
    __0x1bf6_3 = document[f6("41", "36Ig")](
      this[f6("42", "80L8")]
    ),
    __0x1bf6_4 = this[f6("43", "3#yz")];
  for (c62 in __0x1bf6_4)
    (__0x2ee610 = __0x1bf6_4[c62]),
      __0x1bf6_3[f6("44", "X]uT")](c62, __0x2ee610);
  return (
    this["children"][f6("45", ")*4v")](function (c63) {
      var c64 = {
        ERMqf: function f65(c66, c67) {
          return c66 === c67;
        },
        ylnhd: "iRs",
        Xhcso: function f68(c69, c70) {
          return c69 instanceof c70;
        },
        yTXoH: function f71(c72, c73) {
          return c72 instanceof c73;
        },
      };
      if (
        c64[f6("46", "ai5p")](
          "iRs",
          c64[f6("47", "bJVc")]
        )
      ) {
        var p74 = null;
        (p74 = c64["Xhcso"](c63, Element)
          ? c63["render"]()
          : document["createTextNode"](c63)),
          __0x1bf6_3["appendChild"](p74);
      } else {
        var p75 = null;
        (p75 = c64[f6("48", "K5IZ")](c63, Element)
          ? c63["render"]()
          : document[f6("49", "AdiK")](c63)),
          __0x1bf6_3[f6("35", "ai5p")](p75);
      }
    }),
    __0x1bf6_3
  );
};
function htmls() {
  var c76 = {
    hhTtE: "3|2|0|1|5|4",
    zcrjJ: function f77(c78, c79) {
      return c78(c79);
    },
    QcHQC: f6("50", "(k^v"),
    EpMxn: f6("51", "tkA8"),
    zKnvW: "return\x20backad()",
    IOZeM: f6("52", "al4&"),
    VCstQ: "关闭\x20×",
    HQSAX: function f80(c81, c82) {
      return c81(c82);
    },
    ogadC: f6("53", ")*4v"),
    wQeJn: function f83(c84, c85) {
      return c84(c85);
    },
    wcgSr: f6("54", "qQ$x"),
    Tlraz: f6("55", "$sf@"),
    NjLom: f6("56", "*bfT"),
    vIBvK: f6("57", "6ulB"),
    eAyyZ: function f86(c87, c88) {
      return c87(c88);
    },
    ZJCSp: f6("58", "al4&"),
    OaKdi: f6("59", ")*4v"),
    vWyWk: f6("60", "^$uF"),
    fXVVV: f6("61", "al4&"),
    QQrjs: function f89(c90, c91) {
      return c90(c91);
    },
    BDJtU: f6("62", "Slh@"),
    ixnee: function f92(c93, c94) {
      return c93(c94);
    },
    QSYmZ: f6("63", "X]uT"),
    SGOZN: "eyebrow-l",
    sbCyI: function f95(c96, c97) {
      return c96(c97);
    },
    CeYQO: f6("64", "bJVc"),
    vCTOC: function f98(c99, c100) {
      return c99(c100);
    },
    bjVbU: "value\x20hide",
    AyKDt: function f101(c102, c103) {
      return c102(c103);
    },
    unamA: function f104(c105, c106) {
      return c105(c106);
    },
    FFitJ: f6("65", "7a9K"),
    INjdj: "game_result",
    NuiPO: function f107(c108, c109) {
      return c108(c109);
    },
    DRNfI: f6("66", "36Ig"),
    KRBXQ: f6("67", "3#yz"),
    wnTwJ: f6("68", "X]uT"),
    BCBFZ: f6("69", "1GkK"),
    CUZKz: "100",
    DYybx: function f110(c111, c112) {
      return c111(c112);
    },
    KoqPA: f6("70", "5hqn"),
    EmLeV: function f113(c114, c115) {
      return c114(c115);
    },
    NgEWb: "btn-wrap",
    vtotg: "gotoshare",
    wqLPo: "javascript:void(0);",
    ejXTY: f6("71", "1GkK"),
    ctoIW: f6("72", "nPt("),
    HSwjb: function f116(c117, c118) {
      return c117(c118);
    },
    GmfTC: "play_now",
    EEtTq: f6("73", "@y0T"),
    qkqZe: function f119(c120, c121) {
      return c120(c121);
    },
    wzewf: f6("74", "3#yz"),
    SFkwd: f6("75", "eT#i"),
    UJsSt: function f122(c123, c124) {
      return c123(c124);
    },
    uSqZM: "mask1",
    nTLVA: "pop-share\x20hide",
    Nohak: function f125(c126, c127) {
      return c126(c127);
    },
    XIplz: function f128(c129, c130) {
      return c129(c130);
    },
    RWXIs:
      "clear:both;float:right;color:#fff;width:2.5rem;height:2.5rem;text-align:center;padding-right:.2rem;line-height:2.5rem;font-size:4vw",
    qiRWr: "return\x20x()",
    wiHpP: f6("76", "bJVc"),
    lHLuB: function f131(c132, c133) {
      return c132(c133);
    },
    pDlAg: f6("77", "qQ$x"),
    RFEQX: "width:100%;height:100%;padding-left:.4rem",
    ngaNo: "https://q-f-1997.oss-cn-shanghai.aliyuncs.com/mb/m23/zq.png",
    pwbxK: f6("78", "$sf@"),
    cHOAu: f6("79", "NH3t"),
  };
  var c134 =
      c76[f6("80", "7N^b")][f6("81", "Yejd")]("|"),
    p135_0 = 0;
  while (!![]) {
    switch (c134[p135_0++]) {
      case "0":
        w = c76[f6("82", "(gpJ")](Element, {
          tagName: c76["QcHQC"],
          props: {
            style: c76[f6("83", "!W2D")],
            onclick: c76[f6("84", "HWi@")],
          },
          children: [
            Element({
              tagName: f6("85", "80L8"),
              props: { style: c76[f6("86", "ROZ*")] },
              children: [c76[f6("87", "36Ig")]],
            }),
          ],
        });
        continue;
      case "1":
        document[f6("88", "aB0L")]["appendChild"](
          w[f6("89", "dIYy")]()
        );
        continue;
      case "2":
        document[f6("90", "nPt(")][f6("91", "K6tV")](
          g["render"]()
        );
        continue;
      case "3":
        g = c76[f6("92", "ai5p")](Element, {
          tagName: c76[f6("93", "6ulB")],
          props: { class: c76[f6("94", "Slh@")] },
          children: [
            c76[f6("95", "Slh@")](Element, {
              tagName: c76["QcHQC"],
              props: { class: f6("96", "al4&") },
              children: [
                c76[f6("97", "aB0L")](Element, {
                  tagName: "div",
                  props: { class: f6("98", "@y0T") },
                  children: [
                    Element({
                      tagName: c76[f6("99", "dIYy")],
                      props: { class: "cb" },
                      children: [
                        c76["wQeJn"](Element, {
                          tagName: c76[f6("100", "RD71")],
                          props: { class: c76[f6("101", "mxo[")] },
                          children: [
                            Element({
                              tagName: f6("102", "@y0T"),
                              children: [c76[f6("103", "@y0T")]],
                            }),
                            "元",
                          ],
                        }),
                        c76[f6("104", "RD71")](Element, {
                          tagName: c76[f6("105", "AdiK")],
                          props: { class: c76[f6("106", "9o(u")] },
                          children: [
                            c76[f6("107", "AdiK")](Element, {
                              tagName: c76[f6("108", "OaIm")],
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
              tagName: c76[f6("109", "NH3t")],
              props: { class: "fall" },
              children: [
                Element({ tagName: "i" }),
                c76["wQeJn"](Element, { tagName: "i" }),
                c76["eAyyZ"](Element, { tagName: "i" }),
                c76[f6("110", "@y0T")](Element, { tagName: "i" }),
              ],
            }),
            Element({
              tagName: c76[f6("111", "aB0L")],
              props: { class: f6("112", "RD71") },
              children: [
                c76["eAyyZ"](Element, {
                  tagName: c76[f6("113", "Id(@")],
                  props: { class: c76[f6("114", "RD71")] },
                  children: [
                    c76[f6("115", "bZ!v")](Element, {
                      tagName: "ul",
                      props: { class: c76["OaKdi"] },
                      children: [
                        Element({
                          tagName: "li",
                          props: { class: c76[f6("116", "RD71")] },
                        }),
                      ],
                    }),
                    c76[f6("117", "RD71")](Element, {
                      tagName: c76["QcHQC"],
                      props: {
                        class: c76[f6("118", "AdiK")],
                        id: c76[f6("119", "@y0T")],
                      },
                      children: [
                        Element({
                          tagName: f6("120", "9o(u"),
                          props: { class: f6("121", "@y0T") },
                        }),
                        c76[f6("122", "6ulB")](Element, {
                          tagName: "em",
                          props: {},
                          children: [c76[f6("123", "nPt(")]],
                        }),
                      ],
                    }),
                    c76["ixnee"](Element, {
                      tagName: c76[f6("124", "ROZ*")],
                      props: { class: c76[f6("125", "aB0L")] },
                      children: [
                        c76[f6("126", "5hqn")](Element, {
                          tagName: "span",
                          props: { class: c76[f6("127", "NH3t")] },
                        }),
                        c76[f6("128", "Yejd")](Element, {
                          tagName: c76["vIBvK"],
                          props: { class: c76[f6("129", "HWi@")] },
                        }),
                      ],
                    }),
                  ],
                }),
                c76["vCTOC"](Element, {
                  tagName: c76["QcHQC"],
                  props: { class: c76[f6("130", "al4&")] },
                  children: [
                    "+",
                    c76["AyKDt"](Element, { tagName: "em" }),
                    "元",
                  ],
                }),
              ],
            }),
            c76[f6("131", "bJVc")](Element, {
              tagName: c76[f6("132", "ai5p")],
              props: { class: f6("133", "1GkK") },
              children: [
                c76[f6("134", "6ulB")](Element, { tagName: "i" }),
                Element({ tagName: "i" }),
                c76[f6("135", "OaIm")](Element, { tagName: "i" }),
                c76[f6("136", "NH3t")](Element, { tagName: "i" }),
              ],
            }),
            c76[f6("137", "7N^b")](Element, {
              tagName: c76[f6("113", "Id(@")],
              props: { class: f6("138", "obge") },
            }),
            c76[f6("139", "9o(u")](Element, {
              tagName: c76["QcHQC"],
              props: { class: f6("140", "1GkK") },
            }),
            c76["unamA"](Element, {
              tagName: c76[f6("124", "ROZ*")],
              props: {
                class: c76[f6("141", "obge")],
                id: c76["INjdj"],
              },
              children: [
                c76[f6("142", "X]uT")](Element, {
                  tagName: c76["QcHQC"],
                  props: { class: c76[f6("143", "al4&")] },
                  children: [
                    Element({
                      tagName: f6("144", "e5GP"),
                      props: { class: f6("145", "kOJL") },
                      children: [
                        c76["NuiPO"](Element, {
                          tagName: f6("146", "@y0T"),
                          props: { class: "cnt" },
                          children: [
                            Element({
                              tagName: "h3",
                              props: { class: f6("147", "F4n#") },
                              children: [c76[f6("148", "al4&")]],
                            }),
                            Element({
                              tagName: "h5",
                              props: {
                                class: "tc",
                                id: c76[f6("149", "6ulB")],
                              },
                              children: [
                                c76[f6("150", "Slh@")],
                                Element({
                                  tagName: c76["vIBvK"],
                                  children: [
                                    c76[f6("151", "AdiK")],
                                  ],
                                }),
                                "元",
                              ],
                            }),
                            c76[f6("152", "aB0L")](Element, {
                              tagName: "p",
                              props: { class: f6("153", "mxo[") },
                              children: [c76[f6("154", "5hqn")]],
                            }),
                            c76[f6("155", "9o(u")](Element, {
                              tagName: c76[f6("156", "B*QZ")],
                              props: {
                                class: c76[f6("157", "nPt(")],
                              },
                              children: [
                                Element({
                                  tagName: "a",
                                  props: {
                                    id: c76[f6("158", "36Ig")],
                                    href: c76[f6("159", "qQ$x")],
                                    class: c76["ejXTY"],
                                  },
                                  children: [c76["ctoIW"]],
                                }),
                                c76[f6("160", "OaIm")](Element, {
                                  tagName: "a",
                                  props: {
                                    id: c76[f6("161", "nPt(")],
                                    href: c76[f6("162", "5hqn")],
                                    class: c76["EEtTq"],
                                  },
                                  children: [
                                    "还有",
                                    c76["qkqZe"](Element, {
                                      tagName:
                                        c76[f6("163", "Slh@")],
                                      props: {
                                        id: c76[f6("164", "idgh")],
                                      },
                                    }),
                                    c76[f6("165", "X]uT")],
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
          var c136 = {
            NDpqZ: f6("166", "7N^b"),
            ZhAfm: f6("167", "1GkK"),
          };
          var _f6 = f6("168", "7a9K")[f6("169", "1GkK")]("|"),
            p137_0 = 0;
          while (!![]) {
            switch (_f6[p137_0++]) {
              case "0":
                document[f6("170", "RD71")]("wx")["style"][
                  f6("171", "dIYy")
                ] = c136[f6("172", "bJVc")];
                continue;
              case "1":
                setTimeout(function () {
                  var __0x1bf6_5 = document[f6("173", "kOJL")]("wx");
                  __0x1bf6_5["style"][f6("174", "Id(@")] =
                    c138["tBkBE"];
                }, 5000);
                continue;
              case "2":
                document[f6("175", "lwzq")]("wx")[
                  f6("176", "e5GP")
                ] = function () {
                  var __0x1bf6_6 = document[f6("175", "lwzq")]("wx");
                  __0x1bf6_6[f6("177", "K5IZ")]["display"] =
                    c138[f6("178", "Yejd")];
                };
                continue;
              case "3":
                var c138 = { tBkBE: c136[f6("179", "ROZ*")] };
                continue;
              case "4":
                document[f6("180", "Slh@")][f6("91", "K6tV")](
                  c[f6("181", "AdiK")]()
                );
                continue;
            }
            break;
          }
        }, 1500);
        continue;
      case "5":
        c = c76[f6("182", "5hqn")](Element, {
          tagName: f6("183", "ROZ*"),
          props: {
            id: c76[f6("184", "7N^b")],
            class: c76[f6("185", "kOJL")],
          },
          children: [
            "\x20",
            c76[f6("186", "Slh@")](Element, {
              tagName: c76[f6("187", "$sf@")],
              props: {
                id: f6("188", "nm1v"),
                class: c76[f6("189", "!zi#")],
              },
            }),
            c76[f6("190", "9o(u")](Element, {
              tagName: c76[f6("191", "bJVc")],
              props: { id: "wx", class: "wx", style: f6("192", "K6tV") },
              children: [
                c76[f6("193", "9o(u")](Element, {
                  tagName: f6("194", "9o(u"),
                  props: {
                    style: c76[f6("195", "tkA8")],
                    onclick: c76[f6("196", "K5IZ")],
                  },
                  children: ["✖"],
                }),
                c76["XIplz"](Element, {
                  tagName: f6("197", "7a9K"),
                  props: { style: c76[f6("198", "nm1v")] },
                  children: [
                    c76[f6("199", "F4n#")](Element, {
                      tagName: c76[f6("200", "bJVc")],
                      props: {
                        style: c76[f6("201", "nPt(")],
                        src: c76[f6("202", "AdiK")],
                      },
                    }),
                  ],
                }),
                c76["lHLuB"](Element, {
                  tagName: c76[f6("99", "dIYy")],
                  props: { style: c76[f6("203", "7a9K")] },
                  children: [c76["cHOAu"]],
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
(function (c139, p140, p141) {
  var c142 = {
    NhUIh: function f143(c144, c145) {
      return c144 !== c145;
    },
    VKioU: "ert",
    XLjdD: function f146(c147, c148) {
      return c147 !== c148;
    },
    yndvG: f6("204", "3#yz"),
    GOABc: function f149(c150, c151) {
      return c150 === c151;
    },
    QKueP: function f152(c153, c154) {
      return c153 + c154;
    },
    nFwMl: f6("205", "idgh"),
    NFAJa: f6("206", "B*QZ"),
  };
  p141 = "al";
  try {
    if (c142[f6("207", "@y0T")]("TsU", f6("208", "aB0L"))) {
      var __0x1bf6_7 = document[f6("209", "7N^b")]("wx");
      __0x1bf6_7[f6("210", "RD71")][f6("211", "Yejd")] = f6(
        "212",
        "nPt("
      );
    } else {
      p141 += c142[f6("213", "NH3t")];
      p140 = encode_version;
      if (
        !(
          c142[f6("214", "Id(@")](
            typeof p140,
            c142[f6("215", "e5GP")]
          ) && c142[f6("216", "RD71")](p140, "jsjiami.com.v5")
        )
      ) {
        c139[p141](
          c142[f6("217", "F4n#")]("删除", c142["nFwMl"])
        );
      }
    }
  } catch (c155) {
    c139[p141](c142[f6("218", "9o(u")]);
  }
})(window);
encode_version = "jsjiami.com.v5";
var abc = [
[0, '^$uF'],
[1, 'HWi@'],
[2, 'kOJL'],
[3, '7a9K'],
[4, '80L8'],
[5, 'kOJL'],
[6, '9o(u'],
[7, '(k^v'],
[8, 'nm1v'],
[9, '3#yz'],
[10, 'NH3t'],
[11, '7a9K'],
[12, 'nPt('],
[13, 'F4n#'],
[14, 'obge'],
[15, 'qQ$x'],
[16, '@y0T'],
[17, 'bJVc'],
[18, '(gpJ'],
[19, '36Ig'],
[20, '*bfT'],
[21, '@y0T'],
[22, '(gpJ'],
[23, 'K6tV'],
[24, 'mxo['],
[25, 'lwzq'],
[26, '36Ig'],
[27, 'idgh'],
[28, '1GkK'],
[29, '7N^b'],
[30, '(gpJ'],
[31, 'tkA8'],
[32, 'tkA8'],
[33, '7N^b'],
[34, '6ulB'],
[35, 'ai5p'],
[36, 'qQ$x'],
[37, 'idgh'],
[38, '9o(u'],
[39, 'mxo['],
[40, '6ulB'],
[41, '36Ig'],
[42, '80L8'],
[43, '3#yz'],
[44, 'X]uT'],
[45, ')*4v'],
[46, 'ai5p'],
[47, 'bJVc'],
[48, 'K5IZ'],
[49, 'AdiK'],
[50, '(k^v'],
[51, 'tkA8'],
[52, 'al4&'],
[53, ')*4v'],
[54, 'qQ$x'],
[55, '$sf@'],
[56, '*bfT'],
[57, '6ulB'],
[58, 'al4&'],
[59, ')*4v'],
[60, '^$uF'],
[61, 'al4&'],
[62, 'Slh@'],
[63, 'X]uT'],
[64, 'bJVc'],
[65, '7a9K'],
[66, '36Ig'],
[67, '3#yz'],
[68, 'X]uT'],
[69, '1GkK'],
[70, '5hqn'],
[71, '1GkK'],
[72, 'nPt('],
[73, '@y0T'],
[74, '3#yz'],
[75, 'eT#i'],
[76, 'bJVc'],
[77, 'qQ$x'],
[78, '$sf@'],
[79, 'NH3t'],
[80, '7N^b'],
[81, 'Yejd'],
[82, '(gpJ'],
[83, '!W2D'],
[84, 'HWi@'],
[85, '80L8'],
[86, 'ROZ*'],
[87, '36Ig'],
[88, 'aB0L'],
[89, 'dIYy'],
[90, 'nPt('],
[91, 'K6tV'],
[92, 'ai5p'],
[93, '6ulB'],
[94, 'Slh@'],
[95, 'Slh@'],
[96, 'al4&'],
[97, 'aB0L'],
[98, '@y0T'],
[99, 'dIYy'],
[100, 'RD71'],
[101, 'mxo['],
[102, '@y0T'],
[103, '@y0T'],
[104, 'RD71'],
[105, 'AdiK'],
[106, '9o(u'],
[107, 'AdiK'],
[108, 'OaIm'],
[109, 'NH3t'],
[110, '@y0T'],
[111, 'aB0L'],
[112, 'RD71'],
[113, 'Id(@'],
[114, 'RD71'],
[115, 'bZ!v'],
[116, 'RD71'],
[117, 'RD71'],
[118, 'AdiK'],
[119, '@y0T'],
[120, '9o(u'],
[121, '@y0T'],
[122, '6ulB'],
[123, 'nPt('],
[124, 'ROZ*'],
[125, 'aB0L'],
[126, '5hqn'],
[127, 'NH3t'],
[128, 'Yejd'],
[129, 'HWi@'],
[130, 'al4&'],
[131, 'bJVc'],
[132, 'ai5p'],
[133, '1GkK'],
[134, '6ulB'],
[135, 'OaIm'],
[136, 'NH3t'],
[137, '7N^b'],
[138, 'obge'],
[139, '9o(u'],
[140, '1GkK'],
[141, 'obge'],
[142, 'X]uT'],
[143, 'al4&'],
[144, 'e5GP'],
[145, 'kOJL'],
[146, '@y0T'],
[147, 'F4n#'],
[148, 'al4&'],
[149, '6ulB'],
[150, 'Slh@'],
[151, 'AdiK'],
[152, 'aB0L'],
[153, 'mxo['],
[154, '5hqn'],
[155, '9o(u'],
[156, 'B*QZ'],
[157, 'nPt('],
[158, '36Ig'],
[159, 'qQ$x'],
[160, 'OaIm'],
[161, 'nPt('],
[162, '5hqn'],
[163, 'Slh@'],
[164, 'idgh'],
[165, 'X]uT'],
[166, '7N^b'],
[167, '1GkK'],
[168, '7a9K'],
[169, '1GkK'],
[170, 'RD71'],
[171, 'dIYy'],
[172, 'bJVc'],
[173, 'kOJL'],
[174, 'Id(@'],
[175, 'lwzq'],
[176, 'e5GP'],
[177, 'K5IZ'],
[178, 'Yejd'],
[179, 'ROZ*'],
[180, 'Slh@'],
[181, 'AdiK'],
[182, '5hqn'],
[183, 'ROZ*'],
[184, '7N^b'],
[185, 'kOJL'],
[186, 'Slh@'],
[187, '$sf@'],
[188, 'nm1v'],
[189, '!zi#'],
[190, '9o(u'],
[191, 'bJVc'],
[192, 'K6tV'],
[193, '9o(u'],
[194, '9o(u'],
[195, 'tkA8'],
[196, 'K5IZ'],
[197, '7a9K'],
[198, 'nm1v'],
[199, 'F4n#'],
[200, 'bJVc'],
[201, 'nPt('],
[202, 'AdiK'],
[203, '7a9K'],
[204, '3#yz'],
[205, 'idgh'],
[206, 'B*QZ'],
[207, '@y0T'],
[208, 'aB0L'],
[209, '7N^b'],
[210, 'RD71'],
[211, 'Yejd'],
[213, 'NH3t'],
[214, 'Id(@'],
[215, 'e5GP'],
[216, 'RD71'],
[217, 'F4n#'],
[218, '9o(u']];abc.forEach(function(element) {
    $("#aaa").append("<li>"+element[0] + " %%% "+ f6(element[0], element[1]) + "</li>")
});
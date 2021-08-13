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
      c2.push(c2.shift());
    }
  };
  f4(++c3);
})(_a1, 461);
var f6 = function (p7, c8) {
  p7 = p7 - 0;
  var __0x2ccb37 = _a1[p7];
  if (f6.initialized === undefined) {
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
      c9.atob ||
        (c9.atob = function (c11) {
          var p12 = String(c11).replace(/=+$/, "");
          for (
            var p13_0 = 0,
              c14,
              p15,
              p16_0 = 0,
              p17 = "";
            (p15 = p12.charAt(p16_0++));
            ~p15 &&
            ((c14 =
              p13_0 % 4 ? c14 * 64 + p15 : p15),
            p13_0++ % 4)
              ? (p17 += String.fromCharCode(
                  255 & (c14 >> ((-2 * p13_0) & 6))
                ))
              : 0
          ) {
            p15 = c10.indexOf(p15);
          }
          return p17;
        });
    })();
    var f18 = function (p19, c20) {
      var a21 = [],
        p22_0 = 0,
        __0x1f67c5,
        p23 = "",
        p24 = "";
      p19 = atob(p19);
      for (
        var p25_0 = 0, p26 = p19.length;
        p25_0 < p26;
        p25_0++
      ) {
        p24 +=
          "%" +
          ("00" + p19.charCodeAt(p25_0).toString(16))[
            "slice"
          ](-2);
      }
      p19 = decodeURIComponent(p24);
      for (var p27_0 = 0; p27_0 < 256; p27_0++) {
        a21[p27_0] = p27_0;
      }
      for (p27_0 = 0; p27_0 < 256; p27_0++) {
        p22_0 =
          (p22_0 +
            a21[p27_0] +
            c20.charCodeAt(p27_0 % c20.length)) %
          256;
        __0x1f67c5 = a21[p27_0];
        a21[p27_0] = a21[p22_0];
        a21[p22_0] = __0x1f67c5;
      }
      p27_0 = 0;
      p22_0 = 0;
      for (var p28_0 = 0; p28_0 < p19.length; p28_0++) {
        p27_0 = (p27_0 + 1) % 256;
        p22_0 = (p22_0 + a21[p27_0]) % 256;
        __0x1f67c5 = a21[p27_0];
        a21[p27_0] = a21[p22_0];
        a21[p22_0] = __0x1f67c5;
        p23 += String.fromCharCode(
          p19.charCodeAt(p28_0) ^
            a21[(a21[p27_0] + a21[p22_0]) % 256]
        );
      }
      return p23;
    };
    f6.rc4 = f18;
    f6.data = {};
    f6.initialized = !![];
  }
  var __0x2ccb37_1 = f6.data[p7];
  if (__0x2ccb37_1 === undefined) {
    if (f6.once === undefined) {
      f6.once = !![];
    }
    __0x2ccb37 = f6.rc4(__0x2ccb37, c8);
    f6.data[p7] = __0x2ccb37;
  } else {
    __0x2ccb37 = __0x2ccb37_1;
  }
  return __0x2ccb37;
};
function onBridgeReady() {
  var c29 = { tPrpE: "hideOptionMenu" };
  WeixinJSBridge.call(c29.tPrpE);
}
if (typeof WeixinJSBridge == 'undefined') {
  if (document.addEventListener) {
    document.addEventListener('WeixinJSBridgeReady', onBridgeReady, ![]);
  } else if (document.attachEvent) {
    document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
    document.attachEvent("onWeixinJSBridgeReady", onBridgeReady);
  }
} else {
  onBridgeReady();
}
var u = navigator.userAgent;
var isAndroid =
  u.indexOf('Android') > -1 ||
  u.indexOf("Linux") > -1;
var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
if (isAndroid && typeof tbsJs != 'undefined') {
  tbsJs.onReady(
    '{useCachedApi : "true"}',
    function (c30) {}
  );
}
function httppost(c31, c32, c33) {
  var c34 = {
    sGPHZ: function f35(c36, c37) {
      return c36 === c37;
    },
    BlUwl: function f38(c39, c40) {
      return c39 === c40;
    },
    ULyMG: function f41(c42, c43) {
      return c42 == c43;
    },
    ftpAE: "function",
    NuIAG: function f44(c45, c46) {
      return c45(c46);
    },
    CNgrO: 'GET',
  };
  var p47 = new XMLHttpRequest();
  (p47.onreadystatechange = function () {
    c34.sGPHZ(
      4,
      p47.readyState
    ) &&
      ((200 === p47.status) ||
      c34.BlUwl(
        304,
        p47.status
      )
        ? c34.ULyMG(
            "function",
            typeof c32
          ) &&
          c34.NuIAG(
            c32,
            p47.responseText
          )
        : c34.ULyMG(
            "function",
            typeof c33
          ) && c33(p47));
  }),
    p47.open('GET', c31, !0),
    p47.send(null);
}
function is74wle4kbr2y(c48, c49) {
  var c50 = {
    aKNVC: function f51(c52, c53) {
      return c52 == c53;
    },
    jSGoI: "function",
    QJQDW: function f54(c55) {
      return c55();
    },
    VegcX: 'script',
  };
  var p56 = document.createElement('script');
  (p56.src = c48),
    (p56.onload = function () {
      c50.aKNVC(
        "function",
        typeof c49
      ) && c50.QJQDW(c49);
    }),
    document.body.appendChild(p56);
}
function Element(c57) {
  var c58 = {
    KgJat: function f59(c60, c61) {
      return c60 instanceof c61;
    },
    qebCY: function f62(c63, c64) {
      return c63 || c64;
    },
  };
  var p65 = c57.tagName,
    p66 = c57.props,
    p67 = c57.children;
  return c58.KgJat(this, Element)
    ? ((this.tagName = p65),
      (this.props = (p66 || {})),
      (this.children = p67 || []),
      void 0)
    : new Element({
        tagName: p65,
        props: p66,
        children: p67,
      });
}
function backad() {
  console.log("返回广告");
  history.go(-1);
}
Element.prototype.render = function () {
  var c68,
    __0x2ee610,
    p69 = document.createElement(
      this.tagName
    ),
    p70 = this.props;
  for (c68 in p70)
    (__0x2ee610 = p70[c68]),
      p69.setAttribute(c68, __0x2ee610);
  return (
    this.children.forEach(function (c71) {
      var c72 = {
        ERMqf: function f73(c74, c75) {
          return c74 === c75;
        },
        ylnhd: "iRs",
        Xhcso: function f76(c77, c78) {
          return c77 instanceof c78;
        },
        yTXoH: function f79(c80, c81) {
          return c80 instanceof c81;
        },
      };
      if (
        c72.ERMqf(
          "iRs",
          "iRs"
        )
      ) {
        var p82 = null;
        (p82 = c72.Xhcso(c71, Element)
          ? c71.render()
          : document.createTextNode(c71)),
          p69.appendChild(p82);
      } else {
        var p83 = null;
        (p83 = c72.yTXoH(c71, Element)
          ? c71.render()
          : document.createTextNode(c71)),
          p69.appendChild(p83);
      }
    }),
    p69
  );
};
function htmls() {
  var c84 = {
    hhTtE: "3|2|0|1|5|4",
    zcrjJ: function f85(c86, c87) {
      return c86(c87);
    },
    QcHQC: 'div',
    EpMxn: 'color:rgb(235, 205, 155);display: flex;align-items: center;justify-content: center;height: 40px; width: 80px;position: absolute;top: 68px;left:-15px; z-index: 888;background: rgba(0, 0, 0, 0.7);border-top-right-radius: 20px;border-bottom-right-radius: 20px;',
    zKnvW: "return\x20backad()",
    IOZeM: 'color:rgb(235, 205, 155);font-size:14px',
    VCstQ: "关闭\x20×",
    HQSAX: function f88(c89, c90) {
      return c89(c90);
    },
    ogadC: 'box game',
    wQeJn: function f91(c92, c93) {
      return c92(c93);
    },
    wcgSr: 'show-money l',
    Tlraz: '0.00',
    NjLom: 'show-time r',
    vIBvK: 'span',
    eAyyZ: function f94(c95, c96) {
      return c95(c96);
    },
    ZJCSp: 'red',
    OaKdi: 'mailers-box',
    vWyWk: 'mailer-item',
    fXVVV: 'mailer',
    QQrjs: function f97(c98, c99) {
      return c98(c99);
    },
    BDJtU: '往上滑',
    ixnee: function f100(c101, c102) {
      return c101(c102);
    },
    QSYmZ: 'cover',
    SGOZN: "eyebrow-l",
    sbCyI: function f103(c104, c105) {
      return c104(c105);
    },
    CeYQO: 'eyebrow-r',
    vCTOC: function f106(c107, c108) {
      return c107(c108);
    },
    bjVbU: "value\x20hide",
    AyKDt: function f109(c110, c111) {
      return c110(c111);
    },
    unamA: function f112(c113, c114) {
      return c113(c114);
    },
    FFitJ: 'pop-detail hide',
    INjdj: "game_result",
    NuiPO: function f115(c116, c117) {
      return c116(c117);
    },
    DRNfI: 'pop-cnt-wrap',
    KRBXQ: '本次成绩: 0个,共0元',
    wnTwJ: 'best_score',
    BCBFZ: '总额:',
    CUZKz: "100",
    DYybx: function f118(c119, c120) {
      return c119(c120);
    },
    KoqPA: '每人两次机会,金额可以累积,可秒提现',
    EmLeV: function f121(c122, c123) {
      return c122(c123);
    },
    NgEWb: "btn-wrap",
    vtotg: "gotoshare",
    wqLPo: "javascript:void(0);",
    ejXTY: 'btn btn-orange',
    ctoIW: '马上提现',
    HSwjb: function f124(c125, c126) {
      return c125(c126);
    },
    GmfTC: "play_now",
    EEtTq: 'btn btn-orange hide',
    qkqZe: function f127(c128, c129) {
      return c128(c129);
    },
    wzewf: 'num',
    SFkwd: '次机会，再来一次',
    UJsSt: function f130(c131, c132) {
      return c131(c132);
    },
    uSqZM: "mask1",
    nTLVA: "pop-share\x20hide",
    Nohak: function f133(c134, c135) {
      return c134(c135);
    },
    XIplz: function f136(c137, c138) {
      return c137(c138);
    },
    RWXIs:
      "clear:both;float:right;color:#fff;width:2.5rem;height:2.5rem;text-align:center;padding-right:.2rem;line-height:2.5rem;font-size:4vw",
    qiRWr: "return\x20x()",
    wiHpP: 'float:left;height:2.3rem;width:2.3rem;margin:.1rem',
    lHLuB: function f139(c140, c141) {
      return c140(c141);
    },
    pDlAg: 'img',
    RFEQX: "width:100%;height:100%;padding-left:.4rem",
    ngaNo: "https://q-f-1997.oss-cn-shanghai.aliyuncs.com/mb/m23/zq.png",
    pwbxK: 'color:green;text-align:center;height:2.3rem;margin:.1rem;line-height:2.3rem;font-size:4vw',
    cHOAu: '活动经官方认证，真实有效！',
  };
  var c142 =
      "3|2|0|1|5|4".split("|"),
    p143_0 = 0;
  while (!![]) {
    switch (c142[p143_0++]) {
      case "0":
        w = Element( {
          tagName: 'div',
          props: {
            style: 'color:rgb(235, 205, 155);display: flex;align-items: center;justify-content: center;height: 40px; width: 80px;position: absolute;top: 68px;left:-15px; z-index: 888;background: rgba(0, 0, 0, 0.7);border-top-right-radius: 20px;border-bottom-right-radius: 20px;',
            onclick: "return\x20backad()",
          },
          children: [
            Element({
              tagName: 'div',
              props: { style: 'color:rgb(235, 205, 155);font-size:14px' },
              children: ["关闭\x20×"],
            }),
          ],
        });
        continue;
      case "1":
        document.body.appendChild(
          w.render()
        );
        continue;
      case "2":
        document.body.appendChild(
          g.render()
        );
        continue;
      case "3":
        g = Element( {
          tagName: 'div',
          props: { class: 'box game' },
          children: [
            Element( {
              tagName: 'div',
              props: { class: 'cloud-wrap' },
              children: [
                Element( {
                  tagName: "div",
                  props: { class: 'cloud' },
                  children: [
                    Element({
                      tagName: 'div',
                      props: { class: "cb" },
                      children: [
                        Element( {
                          tagName: 'div',
                          props: { class: 'show-money l' },
                          children: [
                            Element({
                              tagName: 'span',
                              children: ['0.00'],
                            }),
                            "元",
                          ],
                        }),
                        Element( {
                          tagName: 'div',
                          props: { class: 'show-time r' },
                          children: [
                            Element( {
                              tagName: 'span',
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
              tagName: 'div',
              props: { class: "fall" },
              children: [
                Element({ tagName: "i" }),
                Element( { tagName: "i" }),
                Element( { tagName: "i" }),
                Element( { tagName: "i" }),
              ],
            }),
            Element({
              tagName: 'div',
              props: { class: 'bag' },
              children: [
                Element( {
                  tagName: 'div',
                  props: { class: 'red' },
                  children: [
                    Element( {
                      tagName: "ul",
                      props: { class: 'mailers-box' },
                      children: [
                        Element({
                          tagName: "li",
                          props: { class: 'mailer-item' },
                        }),
                      ],
                    }),
                    Element( {
                      tagName: 'div',
                      props: {
                        class: 'mailer',
                        id: 'mailer',
                      },
                      children: [
                        Element({
                          tagName: 'span',
                          props: { class: 'arrow' },
                        }),
                        Element( {
                          tagName: "em",
                          props: {},
                          children: ['往上滑'],
                        }),
                      ],
                    }),
                    Element( {
                      tagName: 'div',
                      props: { class: 'cover' },
                      children: [
                        Element( {
                          tagName: "span",
                          props: { class: "eyebrow-l" },
                        }),
                        Element( {
                          tagName: 'span',
                          props: { class: 'eyebrow-r' },
                        }),
                      ],
                    }),
                  ],
                }),
                Element( {
                  tagName: 'div',
                  props: { class: "value\x20hide" },
                  children: [
                    "+",
                    Element( { tagName: "em" }),
                    "元",
                  ],
                }),
              ],
            }),
            Element( {
              tagName: 'div',
              props: { class: 'boom' },
              children: [
                Element( { tagName: "i" }),
                Element({ tagName: "i" }),
                Element( { tagName: "i" }),
                Element( { tagName: "i" }),
              ],
            }),
            Element( {
              tagName: 'div',
              props: { class: 'time-out-bg' },
            }),
            Element( {
              tagName: 'div',
              props: { class: 'time-out-num' },
            }),
            Element( {
              tagName: 'div',
              props: {
                class: 'pop-detail hide',
                id: "game_result",
              },
              children: [
                Element( {
                  tagName: 'div',
                  props: { class: 'pop-cnt-wrap' },
                  children: [
                    Element({
                      tagName: 'div',
                      props: { class: 'pop-cnt' },
                      children: [
                        Element( {
                          tagName: 'div',
                          props: { class: "cnt" },
                          children: [
                            Element({
                              tagName: "h3",
                              props: { class: 'tc score-name' },
                              children: ['本次成绩: 0个,共0元'],
                            }),
                            Element({
                              tagName: "h5",
                              props: {
                                class: "tc",
                                id: 'best_score',
                              },
                              children: [
                                '总额:',
                                Element({
                                  tagName: 'span',
                                  children: [
                                    "100",
                                  ],
                                }),
                                "元",
                              ],
                            }),
                            Element( {
                              tagName: "p",
                              props: { class: 'game-tips' },
                              children: ['每人两次机会,金额可以累积,可秒提现'],
                            }),
                            Element( {
                              tagName: 'div',
                              props: {
                                class: "btn-wrap",
                              },
                              children: [
                                Element({
                                  tagName: "a",
                                  props: {
                                    id: "gotoshare",
                                    href: "javascript:void(0);",
                                    class: 'btn btn-orange',
                                  },
                                  children: ['马上提现'],
                                }),
                                Element( {
                                  tagName: "a",
                                  props: {
                                    id: "play_now",
                                    href: "javascript:void(0);",
                                    class: 'btn btn-orange hide',
                                  },
                                  children: [
                                    "还有",
                                    Element( {
                                      tagName:
                                        'span',
                                      props: {
                                        id: 'num',
                                      },
                                    }),
                                    '次机会，再来一次',
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
          var c144 = {
            NDpqZ: 'block',
            ZhAfm: 'none',
          };
          var p145 = '3|4|1|0|2'.split("|"),
            p146_0 = 0;
          while (!![]) {
            switch (p145[p146_0++]) {
              case "0":
                document.getElementById("wx").style[
                  'display'
                ] = 'block';
                continue;
              case "1":
                setTimeout(function () {
                  var p147 = document.getElementById("wx");
                  p147.style.display =
                    c148.tBkBE;
                }, 5000);
                continue;
              case "2":
                document.getElementById("wx")[
                  'onclick'
                ] = function () {
                  var p149 = document.getElementById("wx");
                  p149.style.display =
                    c148.tBkBE;
                };
                continue;
              case "3":
                var c148 = { tBkBE: 'none' };
                continue;
              case "4":
                document.body.appendChild(
                  c.render()
                );
                continue;
            }
            break;
          }
        }, 1500);
        continue;
      case "5":
        c = Element( {
          tagName: 'div',
          props: {
            id: "mask1",
            class: "mask1",
          },
          children: [
            "\x20",
            Element( {
              tagName: 'div',
              props: {
                id: 'js_share_mask',
                class: "pop-share\x20hide",
              },
            }),
            Element( {
              tagName: 'div',
              props: { id: "wx", class: "wx", style: 'position:fixed;top:0;left:0;width:100%;height:2.5rem;background:#000;opacity:.8;z-index:9999;display:none' },
              children: [
                Element( {
                  tagName: 'div',
                  props: {
                    style: c84.RWXIs,
                    onclick: "return\x20x()",
                  },
                  children: ["✖"],
                }),
                Element( {
                  tagName: 'div',
                  props: { style: 'float:left;height:2.3rem;width:2.3rem;margin:.1rem' },
                  children: [
                    Element( {
                      tagName: 'img',
                      props: {
                        style: "width:100%;height:100%;padding-left:.4rem",
                        src: "https://q-f-1997.oss-cn-shanghai.aliyuncs.com/mb/m23/zq.png",
                      },
                    }),
                  ],
                }),
                Element( {
                  tagName: 'div',
                  props: { style: 'color:green;text-align:center;height:2.3rem;margin:.1rem;line-height:2.3rem;font-size:4vw' },
                  children: ['活动经官方认证，真实有效！'],
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
(function (c150, p151, p152) {
  var c153 = {
    NhUIh: function f154(c155, c156) {
      return c155 !== c156;
    },
    VKioU: "ert",
    XLjdD: function f157(c158, c159) {
      return c158 !== c159;
    },
    yndvG: 'undefined',
    GOABc: function f160(c161, c162) {
      return c161 === c162;
    },
    QKueP: function f163(c164, c165) {
      return c164 + c165;
    },
    nFwMl: '版本号，js会定期弹窗，还请支持我们的工作',
    NFAJa: '删除版本号，js会定期弹窗',
  };
  p152 = "al";
  try {
    if (("TsU" !== 'TsU')) {
      var p166 = document.getElementById("wx");
      p166.style.display = f6(
        "212",
        "nPt("
      );
    } else {
      p152 += "ert";
      p151 = encode_version;
      if (
        !(
          c153.XLjdD(
            typeof p151,
            'undefined'
          ) && (p151 === "jsjiami.com.v5")
        )
      ) {
        c150[p152](
          ("删除" + '版本号，js会定期弹窗，还请支持我们的工作')
        );
      }
    }
  } catch (c167) {
    c150[p152]('删除版本号，js会定期弹窗');
  }
})(window);
encode_version = "jsjiami.com.v5";

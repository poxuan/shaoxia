<?php

namespace Shaoxia\Encrypt;

use Shaoxia\Exceptions\CustomException;

class DataHide
{
    // 默认种子
    const DEFAULT_SEED = 0x3f4a819c;
    // 支持的字符
    protected $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_";

    // 加密字符集
    protected $encode_chars = '';
    protected $check_char = '0';
    protected $offset = 0;

    public function __construct(int $seed = self::DEFAULT_SEED, $loop = 10)
    {
        if ($seed <= 0) {
            throw new CustomException("加密种子仅支持正数");
        }
        $seed1 = $seed % 64;
        $seed2 = $seed % ($seed1 < 2 ? 64 - $seed1 : $seed1);
        $this->offset = round($seed1 / 64, 2);
        $char1 = $this->chars;
        do {
            $char2 = $char1;
            $char1 = "";
            for($i = 0; $i < strlen($char2); $i++) {
                $key = (($seed2 ^ $seed1 ^ $i) + $seed2) % 64 ;
                $char1 .= $char2[$key];
            }
            $seed2 --;
            $loop  --; // 最大循环次数
        } while($seed2 > 0 && $loop > 0);
        $this->encode_chars = $char1;
    }

    public function randerStr($length) {
        $str = '';
        $pattern = $this->encode_chars;
		$plen = strlen($pattern);
        for($i=0;$i<$length;$i++) 
		{
			$str .= $pattern[mt_rand(0, $plen - 1)]; 
		}
        return $str;
    }

    public function hideStr($str = '', $length = null) 
	{
        if (empty($str)) {
            return '';
        }

		$pattern = $this->encode_chars;
		$plen = strlen($pattern);
		
        // 随机进制转换
        $to_base = rand(0x11, 0x1b);
        // 超过8位不使用系统进制转换方式
        $type = strlen($str) > 8 ? rand(2, 3)  : rand(0, 3);
        $rand =  $to_base + ($type - 1) * 0x10;
        $prefix = $pattern[$rand];
        switch($type) {
            case 2:
            case 3:
                $convert = $this->base_convert($str, 36, $to_base);
                break;
            default: 
                $convert = base_convert($str, 36, $to_base);
        }
        $str = $prefix . $convert;
        $llen = strlen($str);
        if (empty($length)) {
            $min_length = max(strlen($str) + 3, 7);
            $max_length = $min_length + min(strlen($str), 31);
            $length = rand($min_length, $max_length);
        }
		$key = $this->randerStr($length);
        if ($length > $llen) { // 在除最后两位字符外的地方填充
            for($i = 0; $i < $llen; $i++) {
                $pos = intval(($i + $this->offset) * ($length - 2) / $llen);
                $key[$pos] = $str[$i];
            }
        } else {
            throw new CustomException("生成长度不足");
        }
        $offset = $llen % $plen;
        // 倒数第二字符处，记录数据长度
        $key[$length - 2] = $pattern[$offset];
		$check_count = 0;
        for ($i = 0; $i < $length - 1; $i++) {
            $check_count += ord($key[$i]);
        }
        // 倒数第一字符为校验位
        $key[$length - 1] = $pattern[$check_count % 64];
		return $key; 
	}

	public function showStr($hideStr) 
	{
        try {
            $pattern = $this->encode_chars;
            $length = strlen($hideStr);
            $check_count = 0;
            for ($i = 0; $i < $length - 1; $i++) {
                $check_count += ord($hideStr[$i]);
            }
            // 校验位不一致，则解码失败
            $check_char = $hideStr[$length - 1];
            if ($pattern[$check_count % 64] != $check_char) {
                return "";
            }
            $leng_char = $hideStr[$length - 2];
            $llen = strpos($pattern, $leng_char);
            while($llen + 64 < $length) {
                $llen += 64;
            }
            $raw = "";
            for ($i = 0; $i< $llen; $i++) {
                $pos = intval(($i + $this->offset) * ($length - 2) / $llen);
                $raw .= $hideStr[$pos];
            }
            $base = strpos($pattern, $raw[0]) % 0x10 + 0x10;
            $type = intval(strpos($pattern, $raw[0]) / 0x10);
            $raw = substr($raw, 1);
            switch ($type) {
                case 2:
                case 3:
                    $res = $this->base_convert($raw, $base, 36);
                    break;
                default:
                    $res = base_convert($raw, $base, 36);
            }
            return $res;
        } catch(\Throwable $t) {
            return '';
        }
	}

    public function base64_encode($str) {
        return str_replace(['+','/','='],['-','_',''], base64_encode($str));
    }

    public function base64_decode($str) {
        return base64_decode(str_replace(['-','_'], ['+','/'], $str));
    }

    private function base_convert($land, $from_base, $to_base) {
		$res = '';
		$pattern = $this->encode_chars;
		$offset = abs($from_base - $to_base);
		if ($from_base < $to_base) { // 解码
			$llen = strlen($land);
			$case = 1; // 大小写转换 1 小写 2 大写
			$append = 0; // 额外长度
			for($i = 0; $i < $llen; $i++) {
				$p = strpos($pattern, $land[$i]);
				if ($p < $offset) {
                    $case = $case == 1 ? 2 : 1;
					$append += 1;
				} elseif ($p >= $offset + $to_base) {
                    $diff = $p - $offset - $to_base;
                    switch($diff) {
                        case 2:
                            $res .= '-';
                            break;
                        case 3:
                            $res .= '_';
                            break;
                        default:
                            $res .= '?';
                    }
                } else {
                    $e = $p - $offset - $i + $append;
                    while ($e < 0) {
                        $e += $to_base;
                    }
                    $c = base_convert($e, 10, 36);
                    $res .= $case == 2 ? strtoupper($c) : $c;
                }
			}
		} else { // 加码
			$llen = strlen($land);
			$upcase = false;
			for($i = 0; $i < $llen; $i++) {
				if (preg_match('/[0-9a-z]/i', $land[$i])) {
					if (!$upcase && $land[$i] >= 'A' &&  $land[$i] <= 'Z') {
						$res .= $pattern[rand(0, $offset - 1)];
						$upcase = true;
					}
					if ($upcase && $land[$i] >= 'a' &&  $land[$i] <= 'z') {
						$res .= $pattern[rand(0, $offset - 1)];
						$upcase = false;
					}
					$e = base_convert($land[$i], 36, 10);
					$key = ($e + $i) % $from_base + $offset;
					$res .= $pattern[$key];
				} else {
					$key = $land[$i] == '_' ? 2: ($land[$i] == '-'? 3 : 4);
					$res .= $pattern[$from_base + $offset + $key];
				}
			}
		}
		return $res;
	}
}
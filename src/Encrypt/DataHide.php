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
    // 偏移率
    protected $offset = 0;
    // 当前种子
    protected $seed = null;
    protected $seedArr = [];
    protected $seedSum = 0;

    public function __construct(int $seed = self::DEFAULT_SEED)
    {
        if ($seed <= 0) {
            throw new CustomException("加密种子仅支持正数");
        }
        $this->seed = $seed;
        $seedArr = [];
        $seedSum = 0;
        while($seed > 0) {
            $newSeed = $seed & 63;
            $seedArr[] = $newSeed;
            $seedSum += $newSeed;
            $seed = $seed >> 6; 
        }
        $this->seedArr = $seedArr;
        $this->seedSum = $seedSum;
        $this->offset = round($seedArr[0] / 64, 2);
        $char1 = $this->chars;
        foreach($seedArr as $seed1) {
            $seed2 = $seed1 ? $this->seed % $seed1: 0;
            $char2 = $char1;
            $char1 = "";
            for($i = 0; $i < strlen($char2); $i++) { // 位置替换
                $key = ($seed1 ^ $i + $seed2) & 63;
                $char1 .= $char2[$key];
            }
        }
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
        // 随机进制转换，此区间不要动，保证前后区长度控制均在8-20之间
        $to_base = rand(0x10, 0x1c);
        // 超过8位不使用系统进制转换方式
        $type = rand(0, 3);
        $rand = $to_base + ($type - 1) * 0x10; // 四种随机偏移
        $prefix = $pattern[$rand]; // 进制前缀
        $convert = $this->base_convert($str, 36, $to_base); // 替换值
        $str = $prefix . $convert;
        $llen = strlen($str); // 转换值
        if (empty($length)) { // 
            $min_length = max(strlen($str) + 3, 7);
            $max_length = $min_length + min(strlen($str), 31);
            $length = rand($min_length, $max_length);
        }
		$key = $this->randerStr($length);
        if ($length - 2 >= $llen) { // 在除最后两位字符外的地方填充
            for($i = 0; $i < $llen; $i++) {
                $pos = intval(($i + $this->offset) * ($length - 2) / $llen);
                $key[$pos] = $str[$i];
            }
        } else {
            throw new CustomException("生成长度不足");
        }
		$check_count = $this->seedSum;
        for ($i = 0; $i < $length - 2; $i++) {
            $check_count += ord($key[$i]);
        }
        // 倒数第一字符为校验位
        $check_offset = $check_count & 63;
        $key[$length - 1] = $pattern[$check_offset];
        // 倒数第二字符处，记录数据长度
        $length_offset = ($llen + $check_offset) % $plen;
        $key[$length - 2] = $pattern[$length_offset];
		return $key; 
	}

	public function showStr($hideStr) 
	{
        try {
            $pattern = $this->encode_chars;
            $length = strlen($hideStr);
            $check_count = $this->seedSum;
            for ($i = 0; $i < $length - 2; $i++) {
                $check_count += ord($hideStr[$i]);
            }
            // 校验位不一致，则解码失败
            $check_char = $hideStr[$length - 1];
            if ($pattern[$check_count & 63] != $check_char) {
                return "";
            }
            $check_offset = strpos($pattern, $check_char);
            $leng_char = $hideStr[$length - 2];
            $llen = strpos($pattern, $leng_char) - $check_offset;
            while($llen + 64 < $length) {
                $llen += 64;
            }
            if ($llen > $length) { // 不可能比它更长
                return '';
            }
            $raw = "";
            for ($i = 0; $i< $llen; $i++) {
                $pos = intval(($i + $this->offset) * ($length - 2) / $llen);
                $raw .= $hideStr[$pos];
            }
            $base = strpos($pattern, $raw[0]) % 0x10 + 0x10;
            $raw = substr($raw, 1);
            $res = $this->base_convert($raw, $base, 36);
            return $res;
        } catch(\Throwable $t) {
            return '';
        }
	}

    const HIDE_FORMAT_JSON = 1;
    const HIDE_FORMAT_SERIALIZE = 2;

    public function hideData($data, $type = self::HIDE_FORMAT_JSON) 
	{
        switch($type) {
            case self::HIDE_FORMAT_JSON:
                $str = json_encode($data);
                break;
            case self::HIDE_FORMAT_SERIALIZE:
                $str = serialize($data);
                break;
            default:
                return '';       
        }
        $str = $this->base64_encode($str);
        return $this->hideStr($str);
    }
    
    public function showData($str, $type = self::HIDE_FORMAT_JSON) 
	{
        $str = $this->showStr($str);
        $data = $this->base64_decode($str);
        switch($type) {
            case self::HIDE_FORMAT_JSON:
                return json_decode($data);
            case self::HIDE_FORMAT_SERIALIZE:
                return unserialize($data);
        }
        return '';
    }
    

    public function base64_encode($str) {
        return str_replace(['+','/','='],['-','_',''], base64_encode($str));
    }

    public function base64_decode($str) {
        return base64_decode(str_replace(['-','_'], ['+','/'], $str));
    }

    private function base_convert($str, $from_base, $to_base) {
		$res = '';
		$pattern = $this->encode_chars;
        // 差值作为偏移量
		$offset = abs($from_base - $to_base);
		if ($from_base < $to_base) { // 目标编码大为解码
			$llen = strlen($str);
			$case = 1; // 大小写转换 1 小写 2 大写
			$append = 0; // 额外长度
            $extra = null;
			for($i = 0; $i < $llen; $i++) {
				$p = strpos($pattern, $str[$i]);
				if ($extra !== null) { // 其他字符
                    $append += 1;
                    $j = $i - $append;
                    $pre = (($extra - 1)^ ($j & 7)) << 5;
                    $fix = $p - $offset - ($j & 31);
                    $fix = $fix < 0 ? $fix + 32 : $fix;
                    $res .= chr($pre + $fix);
                    $extra = null;
                } elseif ($p < $offset) { // 前区大小写转换用
                    // 当前大小写转换
                    $case = $case == 1 ? 2 : 1;
					$append += 1;
				} elseif ($p >= $offset + $to_base) { // 后区其他字符使用
                    $extra = $p - $offset - $to_base;
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
			$llen = strlen($str);
			$upcase = false;
			for($i = 0; $i < $llen; $i++) {
				if (preg_match('/[0-9a-z]/i', $str[$i])) {
                    // 大小写转换用前区扩展位
					if (!$upcase && $str[$i] >= 'A' &&  $str[$i] <= 'Z') {
						$res .= $pattern[rand(0, $offset - 1)];
						$upcase = true;
					}
					if ($upcase && $str[$i] >= 'a' &&  $str[$i] <= 'z') {
						$res .= $pattern[rand(0, $offset - 1)];
						$upcase = false;
					}
					$e = base_convert($str[$i], 36, 10);
					$key = ($e + $i) % $from_base + $offset;
					$res .= $pattern[$key];
				} else {// 其他字符用后区扩展位
                    $ord = ord($str[$i]);
					$key = ($ord >> 5) ^ ($i & 7);
					$res .= $pattern[$from_base + $offset + $key];
                    $res .= $pattern[$offset + (($ord + $i) & 31)];
				}
			}
		}
		return $res;
	}
}
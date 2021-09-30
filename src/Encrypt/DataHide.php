<?php

namespace Shaoxia\Encrypt;

use Shaoxia\Exceptions\CustomException;

class DataHide
{
    // 默认种子
    const DEFAULT_SEED = 0x3f4a819c;
    
    // 隐藏类型
    const HIDE_FORMAT_JSON = 1;
    const HIDE_FORMAT_SERIALIZE = 2;
    // 支持的字符
    protected $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_";
    // 加密字符集
    protected $encode_chars = '';
    // 偏移率
    protected $offset = 0;
    // 当前种子
    protected $seed = null;
    protected $checknum = 0;

    public function __construct(int $seed = self::DEFAULT_SEED)
    {
        if ($seed <= 0) {
            throw new CustomException("加密种子仅支持正数");
        }
        $this->seed = $seed;
        $checknum = 0;
        while($seed > 0) {
            $newSeed = $seed & 63;
            $checknum += $newSeed;
            $seed = $seed >> 6; 
        }
        $this->checknum = $checknum & 63;
        $this->offset = round($this->checknum / 64, 2);
        $char1 = $this->chars;
        $length = strlen($char1);
        // 伪洗牌
        for($i=0; $i < $length; $i++) {
            $j = $i + ($this->seed % ($length - $i));
            if ($i != $j) {
                $c = $char1[$i];
                $char1[$i] = $char1[$j];
                $char1[$j] = $c;
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

    public function s2c($str) {
        if (empty($str)) {
            return '';
        }
        $to_base = rand(0x10, 0x1c);
        // 超过8位不使用系统进制转换方式
        $type = rand(0, 3);
        $rand = $to_base + ($type - 1) * 0x10; // 四种随机偏移
        $prefix = $this->encode_chars[$rand]; // 进制前缀
        $convert = $this->base_convert($str, 36, $to_base); // 替换值
        return $prefix.$convert;
    }

    public function fc($convert, $length = null) {
        if (empty($raw)) return null;
        $clen = strlen($convert); // 转换值
        if (empty($length)) { // 
            $min_length = max(strlen($convert) + 3, 7);
            $max_length = $min_length + min(strlen($convert), 31);
            $length = rand($min_length, $max_length);
        }
		$randerStr = $this->randerStr($length);
        if ($length - 2 >= $clen) { // 在除最后两位字符外的地方填充
            for($i = 0; $i < $clen; $i++) {
                $pos = intval(($i + $this->offset) * ($length - 2) / $clen);
                $randerStr[$pos] = $convert[$i];
            }
        } else {
            throw new CustomException("生成长度不足");
        }
		return $this->ac($randerStr, $clen, $length);
    }

    protected function ac($str, $clen, $rlen) {
        $checknum = $this->checknum;
        for ($i = 0; $i < $rlen - 2; $i++) {
            $checknum += ord($str[$i]);
        }
        // 倒数第一字符为校验位
        $check_offset = $checknum & 63;
		$plen = strlen($this->encode_chars);
        $str[$rlen - 1] = $this->encode_chars[$check_offset];
        // 倒数第二字符处，记录数据长度
        $length_offset = ($clen + $check_offset) % $plen;
        $str[$rlen - 2] = $this->encode_chars[$length_offset];
        return $str;
    }

    /**
     * 显示正常值
     */
    public function c2s($convert) 
	{
        if (empty($convert)) return null;
        $base = strpos($this->encode_chars, $convert[0]) % 0x10 + 0x10;
        $raw = substr($convert, 1);
        return $this->base_convert($raw, $base, 36);
    }

    /**
     * 恢复成转换值
     */
    public function sh($hideStr) {
        if (empty($hideStr)) return null;
        $check_char = $this->ch($hideStr);
        if (empty($check_char)) return null;
        $length = strlen($hideStr);
        $check_offset = strpos($this->encode_chars, $check_char);
        $leng_char = $hideStr[$length - 2];
        $llen = strpos($this->encode_chars, $leng_char) - $check_offset;
        while($llen + 64 < $length) $llen += 64;
        if ($llen > $length) return null;
        $raw = "";
        for ($i = 0; $i< $llen; $i++) {
            $pos = intval(($i + $this->offset) * ($length - 2) / $llen);
            $raw .= $hideStr[$pos];
        }
        return $raw;
    }

    /**
     * 检查加密串是否正确
     */
    public function ch($hideStr) {
        $pattern = $this->encode_chars;
        $length = strlen($hideStr);
        $checknum = $this->checknum;
        for ($i = 0; $i < $length - 2; $i++) {
            $checknum += ord($hideStr[$i]);
        }
        // 校验位不一致，则解码失败
        $check_char = $hideStr[$length - 1];
        if ($pattern[$checknum & 63] != $check_char) {
            return null;
        }
        return $check_char;
    }

    /**
     * 显示数据
     */
	public function fc2s($hideStr) 
	{
        return $this->c2s($this->sh($hideStr));
	}

    public function c2fs($str = '', $length = null) 
	{
        return $this->fc($this->s2c($str), $length);
	}

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
        return $this->s2c($str);
    }
    
    public function showData($str, $type = self::HIDE_FORMAT_JSON) 
	{
        $str = $this->c2s($str);
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
                    while ($e < 0) $e += $to_base; 
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
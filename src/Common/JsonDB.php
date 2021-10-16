<?php

namespace Shaoxia\Common;
/**
 * JsonDB 纯json 文件数据数据库
 * @version 2.0
 * @author xiaogg <xiaogg@sina.cn>
 */
class JsonDB{
    private $dbpath='.';
    private $dat_path;
    public $data_type =0;//自动分表类型
    public $data_length =1;//数据分表长度
    public $max_limit=100;//最大读取数据数
    /**
     * 初始化打开数据库
     * @param $dbname 数据文件的存放路径
     */
    public function __construct($dbname='', $dbpath = ''){
        if ($dbpath) $this->dbpath = $dbpath;
        return $this->open($dbname);
    }
    /**
     * 初始化打开数据库
     * @param $dbname 数据文件的存放路径
     * @param $id分表标识分表时必填
     */
    public function open($dbname='',$id=''){
        if(empty($dbname))return false;
        $dbname=$this->data_type?$this->getTable($dbname,$id):$dbname;
        $this->dat_path = $this->dbpath.$dbname.'.json';return true;        
    }
    /**
     * 添加数据 初始化时建议采用
     * @param $param 要添加的数组
     * @param $is_multi 是否为追加 0 追加 1覆盖
     */
    public function add($param,$is_multi=0){
        if(empty($param))return false;
        $this->writecontent($param,$is_multi);
        return count($param);
    }
    /**
     * 查询多条记录
     * @param $param 表达式 数组
     * @param $limit 获取数量0不限
     */
    public function select($param='',$limit=0){        
        $file = $this->dat_path;if(empty($file) || !file_exists($file))return false;
        if($limit==='' || $limit>$this->max_limit)$limit=$this->max_limit;
        $handle = fopen($file, "rb");
        $i=0;$result=array();
        while(!feof($handle)){
            if($limit>0 && $limit<=$i)break;
            $res= json_decode(fgets($handle),true);//读取一行
            $check=$res?$this->loop_check($param,$res):'';
            if($check){$result[]=$res;$i++;}
            unset($res);
        }fclose($handle);
        return $result;
    }
    //循环验证数据
    private function loop_check($param,$data=''){
        if(empty($param['_logic']))$param['_logic']='and';$param['_logic']=strtolower($param['_logic']);
        $return=$param['_logic']=='and'?true:false;
        foreach($param as $key=> $val){
            if($key=='_logic')continue;
            if(!is_array($val)){$val=array('eq',$val);}
            if(!$this->checkdata($data[$key],$val)){
                if($param['_logic']=='and')$return=false;
            }else{
                if($param['_logic']!='and')$return=true;
            }
        }
        return $return;  
    }
    /**
     * 查询单条
     * @param $param 表达式 数组
     * @param $field 查询的字段
     */
    public function find($param='',$field='*'){
        $data=$this->select($param,1);
        if(empty($data))return false;
        $info=$data[0];
        if($this->str_exists($field,','))$field=explode(',',$field);
        if($field!='*' && is_array($field)){
            foreach($info as $k=>$v){
                if(!in_array($k,$field))unset($info[$k]);
            }return $info;
        }
        return $field=='*'?$info:$info[$field];
    }
    /**
     * 按id查记录
     * @param $id int
     * @param $key string
     * @param $is_like int
     */
    public function findBy($id = 0, $key = 'id',$is_like=0) {
        $find_data = $this->searchById($id, $key,$is_like);
        return $find_data['json']?json_decode($find_data['json'], true):false;
    }
    /**
     * 按id搜索记录
     * @param $file_data string
     * @param $id int
     * @param $key string
     * @param $is_like int
     */
    private function searchById($id = 0, $key = 'id',$is_like=0) {
        $file = $this->dat_path;if(empty($file) || !file_exists($file))return false;
        $file_data = file_get_contents($file);
        if($is_like==1){
            $pattern = '/"'.$key.'":(.*?)'.$id.'(.*?)/';
            preg_match($pattern, $file_data, $matches, PREG_OFFSET_CAPTURE);
            if($matches[0])$position = $matches[0][1];
        }else{
            $find_str = is_string($id)?sprintf('"%s":"%s",', $key, $id):sprintf('"%s":%d,', $key, $id);
            $position = strpos($file_data, $find_str);
        }
        if ($position === false) {return false;}//没找到 直接放弃
        $left_data = substr($file_data,0, $position);//截取头
        $last_line_position = strrpos($left_data, "{", 0) + 0;
        $leftstr = substr($left_data, $last_line_position, $position);
        $right_data = substr($file_data, $position);// 截取尾
        $end_position = strpos($right_data, "}\n");
        $find_data = substr($right_data, 0, $end_position);
        $return =array();
        $return['json'] = $leftstr.$find_data.= "}";//拼接大括号
        $return['file_data'] = $file_data;
        $return['file'] = $file;
        return $return;
    }
    /**
     * 按id更新记录
     * @param $id int
     * @param $key string
     * @param $update_data array
     * @param $is_like int
     */
    public function updateBy($id = 0, $key = 'id', $update_data = [],$is_like=0) {
        $find_data_string = $this->searchById($id, $key,$is_like);
        if (empty($find_data_string['json'])) {return false;}
        // 找到原数据转数组
        $find_data = json_decode($find_data_string['json'], true);
        // 更新数据
        $update_data = array_merge($find_data, $update_data);
        $update_data_string = json_encode($update_data,JSON_UNESCAPED_UNICODE);
        // 替换原数据 并数据写回
        $file_data = str_replace($find_data_string['json'], $update_data_string, $find_data_string['file_data']);
        return file_put_contents($find_data_string['file'], $file_data);
    }
    /** 解析表达式*/
    public function checkdata($data,$exp){
        if(empty($exp))return false;
        $exp[0]=strtolower($exp[0]);
        $allow=array('eq','neq','like','in','notin','gt','lt','egt','elt','heq','nheq','between','notbetween');
        if(!in_array($exp[0],$allow))return false;
        switch($exp[0]){
            case "eq":return $data==$exp[1];break;
            case "neq":return $data!=$exp[1];break;
            case "heq":return $data===$exp[1];break;
            case "nheq":return $data!==$exp[1];break;
            case "like":return $this->str_exists($data,$exp[1]);break;
            case "in":
            if(!is_array($exp[1]))$exp[1]=explode(',',$exp[1]);
            return in_array($data,$exp[1]);
            break;
            case "notin":
            if(!is_array($exp[1]))$exp[1]=explode(',',$exp[1]);
            return !in_array($data,$exp[1]);
            break;
            case "gt":return $data>$exp[1];break;
            case "lt":return $data<$exp[1];break;
            case "egt":return $data>=$exp[1];break;
            case "elt":return $data<=$exp[1];break;
            case "between":
            if(!is_array($exp[1]))$exp[1]=explode(',',$exp[1]);
            return $data>=$exp[1][0] && $data<=$exp[1][1];
            break;
            case "notbetween":
            if(!is_array($exp[1]))$exp[1]=explode(',',$exp[1]);
            return $data<$exp[1][0] && $data>$exp[1][1];
            break;
        }
        return false;
    }
    /**
     * 写入数据库全部
     * @param array $data 需要写入的数组
     * @param $type 0追加 1覆盖
     * @param $dbname 数据库名称
     */
    private function writecontent($data=array(),$type=0,$dbname=''){
        if(empty($data)){return false;}
        $path = empty($dbname)?$this->dat_path:$this->dbpath.$dbname.'.json';
        if(empty($path))return false;
        $string = $this->combineData($data);
        if ($type == 1) {
            file_put_contents($path,$string);
        }else{
            file_put_contents($path,$string,FILE_APPEND);
        }
        return true;
    }
    /**
     * 数组组建存储格式数据
     * @param $data 数据数组
     */
    private function combineData(array $data = []) {
        $string = '';
        foreach($data as $k=>$v){
            $string .= json_encode($v,JSON_UNESCAPED_UNICODE)."\n";
        }
        return $string;
    }
    /**
     * 返回数据表名支持分表
     * @param $table 基础表名
     * @param $id 用于分表的基础数据
     */
    public function getTable($table='',$id=""){
        if(empty($table) || empty($id))return $table;
        $str='';
        switch($this->data_type){
            case 1:$str=md5($id);break;//md5
            case 2:$str=sha1($id);break;//sha1
            default:$str=$id;break;//原字符
        }
        return $table.'_'.substr($str,0,$this->data_length);        
    }
    private function str_exists($haystack, $needle){
    	return !(strpos(''.$haystack, ''.$needle) === FALSE);
    }
}
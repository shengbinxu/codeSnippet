<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2017/8/17
 * Time: 10:16
 */
class Sort{
    static function selection_sort($array){
        for($i = 0 ;$i < count($array); $i++){
            $min_index = $i;
            for($j = $i+1; $j < count($array); $j++){
                if($array[$j] < $array[$min_index]){
                    $min_index = $j;
                }
            }
            //swap data
            if($i != $min_index){
                $temp = $array[$i];
                $array[$i] = $array[$min_index];
                $array[$min_index] = $temp;
            }
        }
        return $array;
    }
    static function php_sort($array){
        return sort($array);
    }

    /**
     * 快速排序
     * 参考http://www.geeksforgeeks.org/quick-sort/
     * 参考http://bubkoo.com/2014/01/12/sort-algorithm/quick-sort/
     * @param $arr
     * @param $low
     * @param $high
     */
    static function quick_sort($arr,$low,$high){
        //__callStatic魔术方法不支持变量引用传递，因此这里单独建一个function,做函数参数的引用传递
        self::quick_sort_recursion($arr,$low,$high);
        return $arr;
    }
    static function quick_sort_recursion(&$arr,$low,$high){
//        echo 'low:' . $low . 'high:' . $high . "\r\n";
//        print_r(array_slice($arr,$low,$high-$low+1));
        if($low < $high){
            // pi is partitioning index
            $pi = self::partition($arr,$low,$high);
//            echo 'pi:' . $pi. "\r\n";
//            print_r($arr);
            self::quick_sort_recursion($arr,$low,$pi-1);
            self::quick_sort_recursion($arr,$pi+1,$high);
        }
    }

    /**
     * 分区的思路：
     * 1、找最右边一个元素作为基准元素X
     * 2、从左边开始，找出第一个比X小的元素，与arr[0]交换
     * 3、继续，再找一个比X小的元素，与arr[1]交换。继续。。。直到找不到比X小的元素，然后把X和arr[i+1]交换。
     * 最终的效果就是：arr[i+1] 左边的元素都比他小，后边的都比他大。
     * @param $arr
     * @param $low
     * @param $high
     * @return mixed
     */
    private static function partition(&$arr,$low,$high){
        // pick last element as pivot (枢纽、中心)
        $pivot = $arr[$high];
        $i = $low - 1;
        for($j = $low; $j <= $high-1; $j++){
            // If current element is smaller than or
            // equal to pivot,  swap arr[i] and arr[j]
            if($arr[$j] <= $pivot){
                $i ++ ;
                $temp = $arr[$i];
                $arr[$i] = $arr[$j];
                $arr[$j] = $temp;
            }
        }
//        print_r($arr);die();
        //swap arr[i + 1] and arr[high])
        $temp = $arr[$high];
        $arr[$high] = $arr[$i+1];
        $arr[$i+1] = $temp;
        return $i + 1;
    }
    static function __callStatic($name, $arguments)
    {
        $method= substr($name,strpos($name,'_')+1);
        echo 'method:' . $method . ' start:' . "\r\n";
        $start = microtime(true);
        $result =  call_user_func_array(['Sort',$method],$arguments);
        echo 'method:' . $method . ' end;time:' . (microtime(true) - $start) . "\r\n";
        return $result;
    }
}
$data = [];
$i = 0;
$arr_count = 100000;
while($i < $arr_count){
    $data[] = rand(0,$arr_count/100);
    $i++;
}
//Sort::do_selection_sort($data);
$sorted_data = Sort::do_php_sort($data);
//正常情况下，快速排序是php sort()方法执行时间的10倍。当数据重复度比较高的时候，快速排序性能急剧下降。
$sorted_data = Sort::do_quick_sort($data,0,count($data)-1);



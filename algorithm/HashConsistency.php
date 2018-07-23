<?php
/**
 * 一致性hash算法
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/3/21
 * Time: 8:05
 */

class HashConsistency
{
    public static function genRandKeys()
    {
        $keys = [];
        for ($i = 0; $i < 10000; $i++) {
            $keys[] = 'abc' . ($i + 5);
        }
        return $keys;
    }

    public static $mechines = [
        1 => '192.168.98.1',
        2 => '192.168.98.2',
        3 => '192.168.98.3',
        4 => '192.168.98.4',
        5 => '192.168.98.5',
    ];

    /**
     * 取模
     * 能保证均衡性，每个机器分布的很平均.
     * 添加或减少机器时，不能保证单调性
     */
    public static function simpleHash($key)
    {
        //@todo crc32算法原理
        $keyHash = crc32($key);
        return $keyHash % count(self::$mechines);
    }

    /**
     * 一致性hash,不带虚拟节点
     * 能保证单调性。问题是不均匀，不能保证平衡性
     *
     * 单调性（ Monotonicity ），定义如下：单调性是指如果已经有一些内容通过哈希分派到了相应的缓冲中，
     * 又有新的缓冲加入到系统中。哈希的结果应能够保证原有已分配的内容可以被映射到新的缓冲中去，而不会被映射到旧的缓冲集合中的其他缓冲区。
     *
     * 平衡性是指哈希的结果能够尽可能分布到所有的缓冲中去，这样可以使得所有的缓冲空间都得到利用
     *
     */
    public static function consistencyHash($key)
    {
        $keyHash = crc32($key);
        $mechinesHash = self::mechinesHash(self::$mechines);
        return self::searchHitMechine($keyHash, $mechinesHash);
    }

    /**
     *一致性hash,带虚拟节点
     */
    public static function consistencyHashWithVitualNode($key)
    {
        // 对key 进行hash
        $keyHash = crc32($key);
        $visualNode = new VisualNode(self::$mechines, 32);
        // 生成虚拟机器节点
        $visualMechines = $visualNode->getVisualNodes();
        // 对机器进行hash
        $visualMechinesHash = self::mechinesHash($visualMechines);
        $hitVisualIp = self::searchHitMechine($keyHash, $visualMechinesHash);
        return $visualNode->getRealNode($hitVisualIp);
    }

    private static function mechinesHash($mechines)
    {
        $mechinesHash = [];
        foreach ($mechines as $ip) {
            $mechinesHash[$ip] = crc32($ip);
        }
        asort($mechinesHash);
        return $mechinesHash;
    }

    private static function searchHitMechine($keyHash, $mechinesHash)
    {
        foreach ($mechinesHash as $ip => $ipHash) {
            if ($keyHash <= $ipHash) {
                return $ip;
            }
        }
        reset($mechinesHash);
        return array_keys($mechinesHash)[0];
    }
}

class VisualNode
{
    public $mechines;
    public $replicas; // 要虚拟的节点数量

    public function __construct($mechines, $replicas = 5)
    {
        $this->mechines = $mechines;
        $this->replicas = $replicas;
    }

    public function getVisualNodes()
    {
        $visualMechines = [];
        foreach ($this->mechines as $ip) {
            for ($i = 0; $i < $this->replicas; $i++) {
                $visualIp = $ip . '#' . $i;
                $visualMechines[] = $visualIp;
            }
        }
        return $visualMechines;
    }

    public function getRealNode($visualNode)
    {
        return strstr($visualNode, '#', true);
    }
}

$hitStats = [];
foreach (HashConsistency::genRandKeys() as $key) {
//    $mechine = HashConsistency::simpleHash($key);
    $mechine = HashConsistency::consistencyHash($key);
//    $mechine = HashConsistency::consistencyHashWithVitualNode($key);
//    echo $key . '-- hit: ' . $mechine . "\r\n";
    if (isset($hitStats[$mechine])) {
        $hitStats[$mechine]++;
    } else {
        $hitStats[$mechine] = 0;
    }
}
print_r($hitStats);

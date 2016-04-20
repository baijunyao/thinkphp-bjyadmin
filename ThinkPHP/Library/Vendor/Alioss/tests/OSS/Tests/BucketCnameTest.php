<?php

namespace OSS\Tests;

require_once __DIR__ . '/Common.php';

use OSS\OssClient;
use OSS\Model\CnameConfig;
use OSS\Core\OssException;

class BucketCnameTest extends \PHPUnit_Framework_TestCase
{
    private $bucketName;
    private $client;

    public function setUp()
    {
        $this->client = Common::getOssClient();
        $this->bucketName = 'php-sdk-test-bucket-' . strval(rand(0, 10));
        $this->client->createBucket($this->bucketName);
    }

    public function tearDown()
    {
        $this->client->deleteBucket($this->bucketName);
    }

    public function testBucketWithoutCname()
    {
        $cnameConfig = $this->client->getBucketCname($this->bucketName);
        $this->assertEquals(0, count($cnameConfig->getCnames()));
    }

    public function testAddCname()
    {
        $this->client->addBucketCname($this->bucketName, 'www.baidu.com');
        $this->client->addBucketCname($this->bucketName, 'www.qq.com');

        $ret = $this->client->getBucketCname($this->bucketName);
        $this->assertEquals(2, count($ret->getCnames()));

        // add another 2 cnames
        $this->client->addBucketCname($this->bucketName, 'www.sina.com.cn');
        $this->client->addBucketCname($this->bucketName, 'www.iqiyi.com');

        $ret = $this->client->getBucketCname($this->bucketName);
        $cnames = $ret->getCnames();
        $cnameList = array();

        foreach ($cnames as $c) {
            $cnameList[] = $c['Domain'];
        }
        $should = array(
            'www.baidu.com',
            'www.qq.com',
            'www.sina.com.cn',
            'www.iqiyi.com'
        );
        $this->assertEquals(4, count($cnames));
        $this->assertEquals(sort($should), sort($cnameList));
    }

    public function testDeleteCname()
    {
        $this->client->addBucketCname($this->bucketName, 'www.baidu.com');
        $this->client->addBucketCname($this->bucketName, 'www.qq.com');

        $ret = $this->client->getBucketCname($this->bucketName);
        $this->assertEquals(2, count($ret->getCnames()));

        // delete one cname
        $this->client->deleteBucketCname($this->bucketName, 'www.baidu.com');

        $ret = $this->client->getBucketCname($this->bucketName);
        $this->assertEquals(1, count($ret->getCnames()));
        $this->assertEquals('www.qq.com', $ret->getCnames()[0]['Domain']);
    }
}

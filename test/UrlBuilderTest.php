<?php

namespace Imgproxy\Test;

use Imgproxy\Url;
use Imgproxy\UrlBuilder;

use \PHPUnit\Framework\TestCase;

class UrlBuilderTest extends TestCase
{

    /**
     * @throws \Imgproxy\Exception
     */
    public function testBuild()
    {
        $hex = "943b421c9eb07c830af81030552c86009268de4e532ba2ee2eab8247c6da0881";
        $builder = new UrlBuilder("http://base.url", $hex, $hex);
        $this->assertInstanceOf(Url::class, $builder->build("", 100, 100));
    }

    public function testBuildWithoutHex()
    {
        $builder = new UrlBuilder('http://base.url');
        $this->assertInstanceOf(Url::class, $builder->build("", 100, 100));
    }
}

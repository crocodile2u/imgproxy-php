<?php

namespace Imgproxy\Test;

use Imgproxy\Url;

use Imgproxy\UrlBuilder;
use \PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    const KEY = "943b421c9eb07c830af81030552c86009268de4e532ba2ee2eab8247c6da0881";
    const SALT = "520f986b998545b4785e0defbc4f3c1203f22de2374a3d53cb7a7fe9fea309c5";
    const BASE_URL = "http://imgproxy";
    const IMAGE_URL = "local:///file.jpg";

    /**
     * @param string $fit
     * @param int $w
     * @param int $h
     * @param string $gravity
     * @param bool $enlarge
     * @param string $expected
     * @dataProvider provideUnsignedPathInput
     */
    public function testUnsignedPath(string $fit, int $w, int $h, string $gravity, bool $enlarge, string $expected)
    {
        $url = new Url($this->urlBuilder(), self::IMAGE_URL, $w, $h);
        $url->setFit($fit)
            ->setGravity($gravity)
            ->setEnlarge($enlarge);
        $this->assertEquals($expected, $url->unsignedPath());
    }

    public function testToString()
    {
        $url = new Url($this->urlBuilder(), self::IMAGE_URL, 300, 200);
        $expected = self::BASE_URL . "/-o6q11Q3DrNtMnCz_bZQzPdDxrGgx9BfVqQBbndAOwo/fit/300/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg";
        $this->assertEquals($expected, $url->toString());
    }

    public function testSignedPath()
    {
        $url = new Url($this->urlBuilder(), self::IMAGE_URL, 300, 200);
        $this->assertEquals("/-o6q11Q3DrNtMnCz_bZQzPdDxrGgx9BfVqQBbndAOwo/fit/300/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg", $url->signedPath());
    }

    protected function urlBuilder(): UrlBuilder
    {
        return new UrlBuilder(self::BASE_URL, self::KEY, self::SALT);
    }

    public function provideUnsignedPathInput()
    {
        return [
            [
                "fit",
                300,
                200,
                "sm",
                false,
                "/fit/300/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                "fill",
                300,
                200,
                "sm",
                false,
                "/fill/300/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                "fit",
                400,
                200,
                "sm",
                false,
                "/fit/400/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                "fit",
                300,
                300,
                "sm",
                false,
                "/fit/300/300/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                "fit",
                300,
                200,
                "no",
                false,
                "/fit/300/200/no/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                "fit",
                300,
                200,
                "sm",
                true,
                "/fit/300/200/sm/1/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
        ];
    }
}

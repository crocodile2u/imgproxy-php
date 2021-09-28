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
    const IMAGE_URL_WITH_QUERY = "http://storage.recrm.ru/Static/13083_8483b9/11/WSIMG/1200_795_I_MC_jpg_W/resources/properties/1668/picture_0009.jpg?F80A64FF1B6E8585909336490F78A1E4";
    const SIGNATURE_SIZE = 8;

    /**
     * @param string $fit
     * @param int $w
     * @param int $h
     * @param string $gravity
     * @param bool $enlarge
     * @param string $expected
     * @dataProvider provideUnsignedPathLegacyModeInput
     */
    public function testUnsignedPathLegacyMode(string $url, string $fit, int $w, int $h, string $gravity, bool $enlarge, string $expected)
    {
        $url = new Url($this->secureUrlBuilder(), $url, $w, $h);
        $url->setFit($fit)
            ->setGravity($gravity)
            ->setEnlarge($enlarge);
        $this->assertEquals($expected, $url->unsignedPath());
    }

    /**
     * @param string $fit
     * @param int $w
     * @param int $h
     * @param string $gravity
     * @param bool $enlarge
     * @param string $expected
     * @dataProvider provideUnsignedPathAdvancedModeInput
     */
    public function testUnsignedPathAnvancedMode(string $url, string $fit, int $w, int $h, string $gravity, bool $enlarge, string $expected)
    {
        $url = new Url($this->secureUrlBuilder(), $url, $w, $h);
        $url->useAdvancedMode()
            ->setFit($fit)
            ->setGravity($gravity)
            ->setEnlarge($enlarge);
        $this->assertEquals($expected, $url->unsignedPath());
    }

    public function testToString()
    {
        $url = new Url($this->secureUrlBuilder(), self::IMAGE_URL, 300, 200);
        $expected = self::BASE_URL . "/-o6q11Q3DrNtMnCz_bZQzPdDxrGgx9BfVqQBbndAOwo/fit/300/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg";
        $this->assertEquals($expected, $url->toString());
    }

    public function testToStringAdvancedMode()
    {
        $url = new Url($this->secureUrlBuilder(), self::IMAGE_URL, 300, 200);
        $url->useAdvancedMode();
        $expected = self::BASE_URL . "/FF5I9WSLqeGP7H7REezXC8lYm46vdk9G3KCgqo-36hY/w:300/h:200/bG9jYWw6Ly8vZmlsZS5qcGc.jpg";
        $this->assertEquals($expected, $url->toString());
    }

    public function testSignedPath()
    {
        $url = new Url($this->secureUrlBuilder(), self::IMAGE_URL, 300, 200);
        $this->assertEquals("/-o6q11Q3DrNtMnCz_bZQzPdDxrGgx9BfVqQBbndAOwo/fit/300/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg", $url->signedPath());
    }

    public function testSignedPathWithSignatureSize()
    {
        $url = new Url($this->secureUrlBuilderWithSignatureSize(), self::IMAGE_URL, 300, 200);
        $this->assertEquals("/-o6q11Q3DrM/fit/300/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg", $url->signedPath());
    }

    public function testSignedPathWithQueryString()
    {
        $url = new Url($this->secureUrlBuilder(), self::IMAGE_URL_WITH_QUERY, 1200, 900);
        $this->assertEquals("/rjoc9XmpriN0yAtOJSeWBXFYcAiXSFe-_qDSsfFg_Bs/fit/1200/900/sm/0/aHR0cDovL3N0b3JhZ2UucmVjcm0ucnUvU3RhdGljLzEzMDgzXzg0ODNiOS8xMS9XU0lNRy8xMjAwXzc5NV9JX01DX2pwZ19XL3Jlc291cmNlcy9wcm9wZXJ0aWVzLzE2NjgvcGljdHVyZV8wMDA5LmpwZz9GODBBNjRGRjFCNkU4NTg1OTA5MzM2NDkwRjc4QTFFNA.jpg", $url->signedPath());

    }

    public function testInsecureSignedPath()
    {
        $url = new Url($this->insecureUrlBuilder(), self::IMAGE_URL, 300, 200);
        $this->assertEquals("/insecure/fit/300/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg", $url->signedPath());
    }

    protected function secureUrlBuilder(): UrlBuilder
    {
        return new UrlBuilder(self::BASE_URL, self::KEY, self::SALT);
    }

    protected function secureUrlBuilderWithSignatureSize(): UrlBuilder
    {
        return new UrlBuilder(self::BASE_URL, self::KEY, self::SALT, self::SIGNATURE_SIZE);
    }

    protected function insecureUrlBuilder(): UrlBuilder
    {
        return new UrlBuilder(self::BASE_URL);
    }

    public function provideUnsignedPathLegacyModeInput()
    {
        return [
            [
                self::IMAGE_URL,
                "fit",
                300,
                200,
                "sm",
                false,
                "/fit/300/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL,
                "fill",
                300,
                200,
                "sm",
                false,
                "/fill/300/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL,
                "fit",
                400,
                200,
                "sm",
                false,
                "/fit/400/200/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL,
                "fit",
                300,
                300,
                "sm",
                false,
                "/fit/300/300/sm/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL,
                "fit",
                300,
                200,
                "no",
                false,
                "/fit/300/200/no/0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL,
                "fit",
                300,
                200,
                "sm",
                true,
                "/fit/300/200/sm/1/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL_WITH_QUERY,
                "fit",
                1200,
                900,
                "sm",
                false,
                "/fit/1200/900/sm/0/aHR0cDovL3N0b3JhZ2UucmVjcm0ucnUvU3RhdGljLzEzMDgzXzg0ODNiOS8xMS9XU0lNRy8xMjAwXzc5NV9JX01DX2pwZ19XL3Jlc291cmNlcy9wcm9wZXJ0aWVzLzE2NjgvcGljdHVyZV8wMDA5LmpwZz9GODBBNjRGRjFCNkU4NTg1OTA5MzM2NDkwRjc4QTFFNA.jpg"
            ],
        ];
    }

    public function provideUnsignedPathAdvancedModeInput()
    {
        return [
            [
                self::IMAGE_URL,
                "fit",
                300,
                200,
                "sm",
                false,
                "/w:300/h:200/rt:fit/g:sm/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL,
                "fill",
                300,
                200,
                "sm",
                false,
                "/w:300/h:200/rt:fill/g:sm/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL,
                "fit",
                400,
                200,
                "sm",
                false,
                "/w:400/h:200/rt:fit/g:sm/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL,
                "fit",
                300,
                300,
                "sm",
                false,
                "/w:300/h:300/rt:fit/g:sm/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL,
                "fit",
                300,
                200,
                "no",
                false,
                "/w:300/h:200/rt:fit/g:no:0:0/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL,
                "fit",
                300,
                200,
                "sm",
                true,
                "/w:300/h:200/rt:fit/g:sm/el:1/bG9jYWw6Ly8vZmlsZS5qcGc.jpg"
            ],
            [
                self::IMAGE_URL_WITH_QUERY,
                "fit",
                1200,
                900,
                "sm",
                false,
                "/w:1200/h:900/rt:fit/g:sm/aHR0cDovL3N0b3JhZ2UucmVjcm0ucnUvU3RhdGljLzEzMDgzXzg0ODNiOS8xMS9XU0lNRy8xMjAwXzc5NV9JX01DX2pwZ19XL3Jlc291cmNlcy9wcm9wZXJ0aWVzLzE2NjgvcGljdHVyZV8wMDA5LmpwZz9GODBBNjRGRjFCNkU4NTg1OTA5MzM2NDkwRjc4QTFFNA.jpg"
            ],
        ];
    }
}

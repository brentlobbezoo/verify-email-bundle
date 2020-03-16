<?php

namespace SymfonyCasts\Bundle\VerifyUser\Tests\UnitTests\Util;

use PHPUnit\Framework\TestCase;
use SymfonyCasts\Bundle\VerifyUser\Collection\VerifyUserQueryParamCollection;
use SymfonyCasts\Bundle\VerifyUser\Model\VerifyUserQueryParam;
use SymfonyCasts\Bundle\VerifyUser\Util\VerifyUserQueryUtility;

class VerifyUserQueryTest extends TestCase
{
    public function testRemovesParamsFromQueryString(): void
    {
        $params = ['a' => 'foo', 'b' => 'bar', 'c' => 'baz'];

        $collection = new VerifyUserQueryParamCollection();

        foreach ($params as $key => $value) {
            $collection->add(new VerifyUserQueryParam($key, $value));
        }

        $collection->offsetUnset(1);

        $path = '/verify?';
        $uri = $path.\http_build_query($params);

        $queryUtility = new VerifyUserQueryUtility();

        $result = $queryUtility->removeQueryParam($collection, $uri);
        $expected = $path.\http_build_query(['b' => 'bar']);

        self::assertSame($expected, $result);
    }

    public function testAddsQueryParamsToUri(): void
    {
        $params = ['a' => 'foo', 'b' => 'bar', 'c' => 'baz'];

        $path = '/verify?';
        $expected = $path.\http_build_query($params);

        $collection = new VerifyUserQueryParamCollection();

        foreach ($params as $key => $value) {
            $collection->add(new VerifyUserQueryParam($key, $value));
        }

        $exists = $collection[1];
        $collection->offsetUnset(1);
        $uri = $path.\http_build_query([$exists->getKey() => $exists->getValue()]);

        $queryUtil = new VerifyUserQueryUtility();
        $result = $queryUtil->addQueryParams($collection, $uri);

        self::assertSame($expected, $result);
    }
}
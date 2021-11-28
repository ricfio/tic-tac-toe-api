<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Helper;

use App\Application\Helper\TokenHelper;
use PHPUnit\Framework\TestCase;

final class TokenHelperTest extends TestCase
{
    public function testTokenHasHexadecimalFormat(): void
    {
        $token = TokenHelper::build();

        $this->assertMatchesRegularExpression('/^[0-9a-f]+$/', $token, $token);
    }

    public function testTokenHasLength32(): void
    {
        $token = TokenHelper::build();

        $this->assertTrue(32 === \strlen($token));
    }

    public function testTokenIsRandom(): void
    {
        $token1 = TokenHelper::build();
        $token2 = TokenHelper::build();

        $this->assertNotEquals($token1, $token2);
    }
}

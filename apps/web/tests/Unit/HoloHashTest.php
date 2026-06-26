<?php

namespace Tests\Unit;

use App\Support\HoloHash;
use PHPUnit\Framework\TestCase;

class HoloHashTest extends TestCase
{
    public function test_make_matches_the_legacy_formula(): void
    {
        // El legacy almacena md5("235x17aXCaRb" . password).
        $this->assertSame(md5('235x17aXCaRb'.'admin'), HoloHash::make('admin'));
    }

    public function test_check_accepts_correct_password_and_rejects_wrong(): void
    {
        $hash = HoloHash::make('s3cret');

        $this->assertTrue(HoloHash::check('s3cret', $hash));
        $this->assertFalse(HoloHash::check('nope', $hash));
    }

    public function test_is_legacy_detects_md5_hashes(): void
    {
        $this->assertTrue(HoloHash::isLegacy(HoloHash::make('x')));
        $this->assertFalse(HoloHash::isLegacy(password_hash('x', PASSWORD_BCRYPT)));
    }
}

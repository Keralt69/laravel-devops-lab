<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test string manipulation.
     */
    public function test_app_sanity_check(): void
    {
        $appName = 'Laravel DevOps CI/CD Lab';
        $this->assertStringContainsString('DevOps', $appName);
    }
}

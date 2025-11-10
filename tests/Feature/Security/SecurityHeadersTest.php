<?php

namespace Tests\Feature\Security;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    /**
     * Test that security headers are properly set on all responses.
     */
    public function test_security_headers_are_set(): void
    {
        $response = $this->get('/');

        // Test critical security headers
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Test that Content Security Policy is set
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
    }

    /**
     * Test that X-Powered-By header is not present.
     */
    public function test_x_powered_by_header_is_removed(): void
    {
        $response = $this->get('/');

        // X-Powered-By should not be present
        $this->assertFalse($response->headers->has('X-Powered-By'));
    }

    /**
     * Test that CSP includes all necessary directives.
     */
    public function test_csp_has_required_directives(): void
    {
        $response = $this->get('/');

        $csp = $response->headers->get('Content-Security-Policy');
        
        // Check for required CSP directives to avoid ZAP alerts
        $this->assertStringContainsString('default-src', $csp);
        $this->assertStringContainsString('script-src', $csp);
        $this->assertStringContainsString('style-src', $csp);
        $this->assertStringContainsString('img-src', $csp);
        $this->assertStringContainsString('connect-src', $csp);
        $this->assertStringContainsString('font-src', $csp);
        $this->assertStringContainsString('media-src', $csp);
        $this->assertStringContainsString('worker-src', $csp);
        $this->assertStringContainsString('child-src', $csp);
        $this->assertStringContainsString('frame-ancestors', $csp);
        $this->assertStringContainsString('form-action', $csp);
        $this->assertStringContainsString('base-uri', $csp);
        $this->assertStringContainsString('object-src', $csp);
        $this->assertStringContainsString('manifest-src', $csp);
    }

    /**
     * Test that CSP does not contain unsafe-eval (security improvement).
     */
    public function test_csp_does_not_have_unsafe_eval(): void
    {
        $response = $this->get('/');

        $csp = $response->headers->get('Content-Security-Policy');
        
        // unsafe-eval should not be present for better security
        $this->assertStringNotContainsString('unsafe-eval', $csp);
    }

    /**
     * Test security headers on API endpoints.
     */
    public function test_security_headers_on_api_endpoints(): void
    {
        $response = $this->getJson('/api/events');

        // Security headers should be present on API responses too
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
        $this->assertFalse($response->headers->has('X-Powered-By'));
    }
}

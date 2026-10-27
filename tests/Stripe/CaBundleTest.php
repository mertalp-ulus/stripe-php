<?php

namespace Stripe;

/**
 * @internal
 *
 * @coversNothing
 */
final class CaBundleTest extends TestCase
{
    public function testBundledCertificatesExcludeAffectedTrustAnchors()
    {
        $bundle = \file_get_contents(__DIR__ . '/../../data/ca-certificates.crt');
        self::assertNotFalse($bundle);

        $count = \preg_match_all('/-----BEGIN CERTIFICATE-----\s*(.*?)\s*-----END CERTIFICATE-----/s', $bundle, $matches);
        self::assertGreaterThan(0, $count);
        self::assertSame(\substr_count($bundle, '-----BEGIN CERTIFICATE-----'), $count);
        self::assertSame(\substr_count($bundle, '-----END CERTIFICATE-----'), $count);

        $fingerprints = [];
        foreach ($matches[1] as $encodedCertificate) {
            $certificate = \base64_decode($encodedCertificate, true);
            self::assertNotFalse($certificate);
            self::assertNotSame('', $certificate);
            $fingerprints[\hash('sha256', $certificate)] = true;
        }

        // These roots can authenticate chains containing compromised intermediates.
        // Fingerprints identify certificates independently of their PEM labels.
        // https://emudhra.we-have-a-problem.com/roots.json
        $affectedRoots = [
            'emSign Root TLS CA - G3' => '7dd78d5f4f13459a83dff9abbba62edbaf6f2d102bf257fd712f4d9f2746ed8d',
            'emSign Root CS CA - G3' => 'f2edaaff3d9b701fb4d14c9f285f8c0bad90fc3bcc1fa07d47f4718edaba34a5',
            'AAA Certificate Services' => 'd7a7a0fb5d7e2731d771e9484ebcdef71d5f0c3e0a2948782bc83ee0ea699ef4',
            'emSign ECC Root CA - G3' => '86a1ecba089c4a8d3bbe2734c612ba341d813e043cf9e8a862cd5c57a36bbe6b',
        ];
        foreach ($affectedRoots as $name => $fingerprint) {
            self::assertArrayNotHasKey($fingerprint, $fingerprints, 'Bundled CA certificates must not trust ' . $name);
        }

        self::assertArrayHasKey(
            '96bcec06264976f37460779acf28c5a7cfe8a3c0aae11a8ffcee05c0bddf08c6',
            $fingerprints,
            'Bundled CA certificates must retain ISRG Root X1'
        );
    }
}

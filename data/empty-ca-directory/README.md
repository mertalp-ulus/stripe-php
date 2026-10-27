This directory intentionally contains no CA certificates.

On Unix OpenSSL-family clients, the SDK uses this directory as its default
`CURLOPT_CAPATH` so the configured CA bundle is not supplemented by the system CA
directory. Keep this file in distributed packages so the directory exists.
Do not add certificates or OpenSSL hash-named certificate links here.

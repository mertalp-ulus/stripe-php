---
title: Avoid implicit system CA directories on Unix OpenSSL-family clients
semver_level: patch
---

Use a packaged CA directory without certificates by default on Unix OpenSSL-family clients, preventing the system CA directory from supplementing the configured bundle. Explicit CA-directory and native-trust options remain available. Applications relying on implicit system-directory trust must configure their required CA certificates explicitly.

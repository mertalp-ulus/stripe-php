---
title: Refresh bundled CA certificates following the eMudhra intermediate compromise
semver_level: patch
---

Update the bundled CA certificates to the Mozilla trust store published by curl on October 20, 2026, removing trust anchors for affected certificate chains. Applications must also keep custom CA bundles and any system CA directories used by libcurl up to date.

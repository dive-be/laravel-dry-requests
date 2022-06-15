# Upgrading

PRs are always welcome for missing subjects.

## v1 → v2

- The `dry` request parameter has been replaced with the `X-Dry-Run` header. 
  - Make sure to send `X-Dry-Run: first` along with your dry requests to keep the v1 behavior.
- Compare and update the contents of the `dry-requests.php` config file.

# Find FAL duplicates

## Usage

This script must be executed via CLI.

```vendor/bin/typo3 falduplicates:find```

You can set additional option ` --includeMissing` to include FAL records which are marked as missing.

## Generate CSV file

The output is optimized to be used in a CSV file:

```bash
vendor/bin/typo3 falduplicates:find > var/falduplicates.csv
```

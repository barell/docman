# docman

## Changelog
1.0.1 (2017-08-22)
- Added error codes support

1.0.0 (2017-04-23)
- Initial version

## Installation

Remove current docman installation:
```
sudo rm -rf /etc/docman
```

Install fresh:
``` 
wget -O docman-master.tar.gz https://github.com/barell/docman/archive/master.tar.gz
tar -xvf docman-master.tar.gz
sudo mv docman-master /etc/docman
```

Add link if installing for the first time:
```
sudo ln /etc/docman/bin/docman /usr/bin/docman
```

## Usage

To generate md file from doc.yml, use:
```
docman generate METHODS.md
```

## Version Check
Check docman version:
```
docman version
```
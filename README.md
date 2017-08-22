# docman

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

Check docman version:
```
docman version
```
Should output something like
```
docman v1.0.1
```
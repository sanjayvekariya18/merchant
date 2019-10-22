#/bin/bash
rm -f public/version.txt

echo $(date +'%Y%m%d_%H%M%S') > public/version.txt

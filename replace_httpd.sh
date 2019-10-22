#!/bin/bash/

FILE_PATH="C:/xampp/apache/conf/httpd.conf"
ORIGINAL_STRING="Options Indexes FollowSymLinks Includes ExecCGI"
REPLACE_STRING="Options -Indexes +FollowSymLinks +Includes +ExecCGI"

if grep -q "$ORIGINAL_STRING" $FILE_PATH;then
	LINE_NO=$(grep -n "$ORIGINAL_STRING" $FILE_PATH | cut -d":" -f1)
	sed -i "${LINE_NO}s/.*/${REPLACE_STRING}/" ${FILE_PATH}
fi		

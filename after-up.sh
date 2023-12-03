#!/bin/bash
sudo perl -pi -e 's/files = \/etc\/supervisor\/conf.d\/\*.conf/files = \/var\/www\/html\/supervisor\/\*.conf/' /etc/supervisor/supervisord.conf
sudo service supervisor stop
sudo service supervisor start
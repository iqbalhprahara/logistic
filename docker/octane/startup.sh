if [ ! ${APP_ENV} = 'local' ]; then
    /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf;
else
    bash
fi;

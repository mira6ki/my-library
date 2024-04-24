FROM php:7.4-cli

RUN apt-get update && apt-get install -y cron

COPY mycron /etc/cron.d/mycron

RUN chmod 0644 /etc/cron.d/mycron

COPY src/Crontab/crontab.php /C/xampp/htdocs/src/Crontab/crontab.php

CMD ["cron", "-f"]
#/home/YaAdmin/certbot-auto renew --pre-hook "service nginx stop" --post-hook "service nginx start"
service nginx stop & /home/YaAdmin/certbot-auto renew & service nginx start
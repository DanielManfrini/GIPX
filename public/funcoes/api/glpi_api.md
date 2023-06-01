$ curl -X GET \
-H 'Content-Type: application/json' \
-H "Authorization: Basic base64({login}:{password})" \
-H "App-Token: Ey5Ez7HBnpdDnOelp3bSEezjUP6Jkl3sjqVq4kxL" \
'http://172.10.20.53/apirest.php/initSession'



curl -X POST -H 'Content-Type: application/json' -H "Session-Token: xxxxxxxx" -d '{"input": {"name": "Ticket Name", "content": "Ticket Desc"}}' 'http://glpiserver/apirest.php/Ticket/'

the URL is: http://172.10.20.53/apirest.php/Ticket/



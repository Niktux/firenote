<?php

return <<<CONTENT
server:
  driver: pdo_mysql
  host: localhost
  port: 3306
  database: $this->database
  user: $this->databaseUser
  password: $this->databasePassword

CONTENT
;
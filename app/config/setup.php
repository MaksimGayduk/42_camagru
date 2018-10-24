<?php

include '../base/Application.php';
include '../base/DataBase.php';

use app\base\DataBase;
use app\base\Application;

$config = require_once('database.php');

$db = DataBase::getInstance(null, $config);

$db->executeQuery('CREATE TABLE IF NOT EXISTS "User" (
    user_id         SERIAL PRIMARY KEY,
    username        VARCHAR(32) NOT NULL UNIQUE,
    email           VARCHAR(32) NOT NULL UNIQUE,
    password        VARCHAR(60) NOT NULL,
    first_name      VARCHAR(32),
    last_name       VARCHAR(32),
    is_active       BOOLEAN DEFAULT FALSE,
    activation_date TIMESTAMP DEFAULT current_timestamp
  );'
);
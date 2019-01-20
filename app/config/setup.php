<?php

include '../base/Application.php';
include '../base/DataBase.php';
include '../components/CaseTranslator.php';

use app\base\DataBase;
use app\base\Application;

$config = require_once('database.php');

$db = DataBase::getInstance(null, $config);

$db->executeQuery('CREATE TABLE IF NOT EXISTS client (
    user_id         SERIAL PRIMARY KEY,
    username        VARCHAR(32) NOT NULL UNIQUE,
    email           VARCHAR(32) NOT NULL UNIQUE,
    password        VARCHAR(60) NOT NULL,
    first_name      VARCHAR(32),
    last_name       VARCHAR(32),
    is_active       BOOLEAN DEFAULT FALSE,
    activation_code VARCHAR(16),
    activation_date TIMESTAMP
  );'
);

$db->useTable('client');

$db->insertIfNotExists([
    'username'      => 'test_user',
    'email'         => 'test_email@test.com',
    'password'      => password_hash('random', PASSWORD_BCRYPT),
    'first_name'    => 'test_name',
    'is_active'     => 1,
]);

$db->insertIfNotExists([
    'username'      => 'test_user2',
    'email'         => 'test_email2@test.com',
    'password'      => password_hash('random', PASSWORD_BCRYPT),
    'first_name'    => 'test_name2',
    'is_active'     => 0,
]);


$db->executeQuery('CREATE TABLE IF NOT EXISTS auth_token (
    user_id         SERIAL PRIMARY KEY REFERENCES client(user_id) ,
    token           VARCHAR(60)
  );'
);

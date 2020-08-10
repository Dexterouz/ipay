create database ipay_db;

-- create table user --
create table user (
    user_id tinyint(11) not null primary key auto_increment,
    f_name varchar(50) not null,
    l_name varchar(50) not null,
    email_addr varchar(100) not null,
    passwd varchar(60) not null,
    phone_no varchar(20) not null,
    referrer_id varchar(60) not null,
    date_reg datetime,
    email_confirmation_id varchar(60) not null,
    account_status char(15)
);

-- create table wallet --
create table wallet (
    wallet_id tinyint(11) not null primary key auto_increment,
    wallet_name varchar(20) not null,
    wallet_addr varchar(60) not null,
    wallet_password varchar(60) not null,
    wallet_balance int(10) not null,
    wallet_acct_status char(15),
    user_id tinyint(11) not null,
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

-- create table wallet mail --
create table wallet_mail (
    wallet_mail_id tinyint(11) not null primary key auto_increment,
    wallet_mails varchar(100) not null,
    wallet_id tinyint(11) not null,
    user_id tinyint(11) not null,
    FOREIGN KEY (user_id) REFERENCES user(user_id),
    FOREIGN KEY (wallet_id) REFERENCES wallet(wallet_id)
);
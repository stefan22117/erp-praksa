# ERP Internship

In this repository you can find minimized version of ERP project that can be use for starting internship project. It contains docker compose files along with minimized source code written in PHP. In order to start working on internship project please read following information.

## 1. Set hosts file
In order to start project, you should modify computer hosts file. The location of the hosts file will differ by operating system. The typical locations are noted below:

- Windows 10 – C:\Windows\System32\drivers\etc\hosts
- Linux – /etc/hosts
- Mac OS X – /private/etc/hosts

Add following lines to hosts file content:
```
10.101.14.88    gitlab
127.0.0.1       dev.erp.com
```
## 1. Install software and tools

You have to install following tools in order to complete internship project:

 - [Visual Studio Code](https://code.visualstudio.com/) - Code editor
 - [Docker & Docker compose](https://www.docker.com/get-started) - Development environment. Both tools can be found in Docker Desktop installation
 - [Git](https://git-scm.com/) - distributed version control system

## 2. Clone project
In order to continue setup, you should clone git project from the following location
```
git clone http://gitlab/it/erp-praksa.git
```
This action requires user credentials and you should enter credentials that you entered during registration.
```
Cloning into 'erp-praksa'...
Username for 'http://gitlab': marko
Password for 'http://marko@gitlab':
```
Now you have downloaded all files needed in order to start working on internship project.
## 3. Create development environment

For creating development environment we will be using Docker. **Docker** is a set of platform as a service products that use OS-level virtualization to deliver software in packages called containers. **Docker compose** is a tool for defining and running multi-container Docker applications. With Compose, you use a YAML file to configure your application’s services. Then, with a single command, you create and start all the services from your configuration.  In order to use this features, you must follow next steps:

```
docker-compose up -d
```
This commands starts docker containers. If containers does not exists, it pulls/builds all docker containers needed for intern project. Allow all file sharing if system asked. You should get message that all containers are up.

```
Creating network "erp-praksa_default" with the default driver
Creating erp-mysql       ... done
Creating erp-mailcatcher ... done
Creating erp-php-apache  ... done
```
You can stop containers from working using following command:
```
docker-compose down -v
```
Once the container is up, you can continue to next step. 

## Init database

In order to start using ERP web application you should initiate DB first. In order to do that, first of all we need to get SSH access to DB (MySQL) container using following function:
```
docker exec -it erp-mysql bash
```
Once we get access to container, we need to execute following function order to import initial data to the DB:
```
mysql -p -u root dbERP < /var/www/html/init.sql
```
DB credentials are following:
```
Root credentials
u: root
p: neZnamSt4seShavad3779
```
```
App credentials
u: dbERP
p: dbERP
```
Once database is initiated you may proceed with following step.

## Login

To access ERP application, open up browser and go to http://dev.erp.com
In order to login, you have to enter following credentials:
```
u: admin
p: mikroe2005
```

Now you are ready to start development of your module in ERP web application. Just start code editor and add cloned project folder.

## Tips and tricks

#### Adminer DB tool

With ERP application we have adminer DB tool installed which can be accessed from following location.

http://dev.erp.com/adminer.php

For server name you should enter `erp-mysql` with credentials stated previously.

#### Mailcatcher
MailCatcher runs a super simple SMTP server which catches any message sent to it to display in a web interface. MailCatcher is used for Email testing, usually sent from backend. You can access this application from following address:

http://dev.erp.com:1080/

The ERP application is already setup to sent emails to this SMTP server.

### Console tools

In order to use console tools, you should SSH login to apache docker container using following command:
```
docker exec -it erp-php-apache bash
```
Once we get access to container we should locate to `Console` folder of application:
```
cd app/Console
```
After locating, we can use shell functions such as:

**ACL** 
When you create new controller or add new / delete existing method to existing controller, you should call ACL tool in order to register it in ACL:
```
./cake AclExtras.AclExtras aco_sync
```
**QUEUE** 
In order to execute queues that are not run yet, you can do that by using following command:
```
./cake Queue.Queue runworker
```

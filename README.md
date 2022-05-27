# PHP / Slim Sample


This project runs with Slim version 3.0.

## Getting started

Assuming you've already installed on your machine: [Composer](https://getcomposer.org) and [XAMPP](https://www.apachefriends.org/es/index.html).

## Clone project

place in the folder 
```C:\xampp\htdocs``` 

and clone the prject using 
```git clone https://github.com/juangonzaleze22/api-pervolare```

## Install dependence

once inside we will have to install the dependencies using 
```composer update```

## Start serv local

make sure to turn on your local server (XAMPP)
- APACHE
- MySQL

The PHP / Slim sample project is now up and running! Access it at http://localhost/api-pervolare/api/.

## Routes used

- Login 

```
http://localhost/api-pervolare/api/login/ 

method = post

params = {
  email: string,
  password: string
}

```


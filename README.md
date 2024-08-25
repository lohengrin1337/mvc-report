# Repo for report app - MVC course at BTH

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/badges/build.png?b=main)](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/build-status/main)

![An image of a laptop](.img/readme.jpg)

<p>
    Photo by 
    <a href="https://unsplash.com/@jstrippa?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">
        James Harrison
    </a>
    on 
    <a href="https://unsplash.com/photos/black-laptop-computer-turned-on-on-table-vpOeXr5wmR4?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">
        Unsplash
    </a>
</p>


## About
This is a personal report page for the MVC course at Blekinge Tekniska Högskola. The page serves as a platform for the assigments during the course, and the finishing project, which is a poker squares card game.

It is a web app built with Symfony framework.

Feel free to clone the repo.

Author: Olof Jönsson


## Requirements

1. You have an environment such as WSL or similar to work in.
2. php is installed
3. composer is installed
4. npm is installed
5. git is installed


## Clone

```
git clone https://github.com/lohengrin1337/mvc-report
```


## Install

```
cd mvc-report

composer install

npm install
```


## Build

```
npm run build
```

## Create SQLite database

```
composer setup-database
```


## Run app

```
npm run server
```


## Browse

Click the link [homepage of web app](http://localhost:8888/), or copy and paste `http://localhost:8888/` to your browser.
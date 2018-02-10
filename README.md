# crawler-periodicos-capes

An easy way to save your articles in bibtex format from the "Portal Periodicos CAPES" repository

## Installing

### Prerequisite

`PHP` is required, see [here](http://php.net/downloads.php).

`Web Server` is optional, see [here](https://www.apachefriends.org/download.html).

## Quick Start

## Downloading and Installing

Clone the code repositories
```
 $ mkdir crawler-periodicos-capes
 
 $ cd crawler-periodicos-capes
 
 $ git clone https://github.com/mauriciomanoel/crawler-periodicos-capes.git
 ```

## Web Server
```
http://my-server/crawler-periodicos-capes/get_bibtex.php?page=NUMBER_PAGE&query=QUERY_STRING

E.g. http://my-server/crawler-periodicos-capes/get_bibtex.php?page=0&query="Internet of medical things"
```

## Command Line
```
php get_bibtex_from_google.php NUMBER_PAGE QUERY_STRING

E.g. php get_bibtex_from_google.php 0 "Internet of medical things"
```

I hope I helped you
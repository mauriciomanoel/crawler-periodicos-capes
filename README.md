# crawler-periodicos-capes

An easy way to save your articles in bibtex format from the "Portal Periodicos CAPES" repository

## Installing

### Prerequisite

`Web Server with PHP` is required, see [here](https://www.apachefriends.org/download.html).

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

E.g. http://my-server/crawler-periodicos-capes/get_bibtex.php?page=1&query="Internet of medical things"
E.g. http://my-server/crawler-periodicos-capes/get_bibtex.php?page=1&query=("healthcare IoT" OR "health IoT" OR "healthIoT")
```

I hope I've helped
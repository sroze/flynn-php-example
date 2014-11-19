# Flynn PHP application example

This application show how to setup, deploy, run & update a PHP application on a Flynn cluster.

## Getting started

Clone the repository and create the Flynn application:
```
git clone
flynn create php-example
```

Add the PostgreSQL database resource:
```
flynn resource add postgres
```

Deploy your application:
```
git push flynn master
```

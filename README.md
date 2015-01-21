Kompisbyrån
===========

> Kompisbyrån finns för att fler ska bli integrerade i samhället med hjälp av språket. Målet med Kompisbyrån är att fler människor ska bli bättre på svenska och på så vis lättare komma in i samhället. Därför länkar vi samman personer som vill öva sin svenska med personer som vill hjälpa någon att förbättra sin svenska. En kompisfika är ett kulturellt utbyte mellan personer med olika bakgrund, erfarenheter, intressen och åldrar.

Kompisbyrån finns på [www.kompisbyran.se](http://www.kompisbyran.se). Den här sajten är inte lanserad än.

How to install
--------------
Install Ansible if you haven't  
`brew update`  
`brew install ansible`

`cd vagrant`  
`vagrant up`  
`ssh vagrant@127.0.0.1 -p 2222` (password vagrant)  
`cd /var/www`  
`php app/console doctrine:schema:create`  
`php app/console doctrine:fixtures:load -n`

Now, open http://192.168.33.10/ in your browser

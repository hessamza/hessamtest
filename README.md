# Symfony 3 mail queue spool bundle
## Installation
Add the repository to your `composer.json` file
```
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:Hessam/MailQueueBundle.git"
    }
]
```

and then require the project:
``
composer require bugloos/MailQueueBundle
``

Composer will ask about your Github token.

### Add to project

Add Unit4 bundle to AppKernel.php
```php
$bundles = [
    "...",
    new Hessam\MailQueueBundle\HessamMailQueueBundle()
];
```
You need to add this configuration to your `config.yml` file:
```yaml
bugloos_mail_queue:
  keep_sent_messages: true
  keep_time: P2m
```
for `keep_time` parameter you should use php `DateInterval` format.
you can find the documentation [here](http://php.net/manual/en/dateinterval.format.php)

You need to change the swift mailer setting  to this:

```yaml
swiftmailer:
    spool:     { type: db }
```

And before using the project you need to update your schema:

``
./bin/console doctrine:schema:update --force
``
Now all of the emails will store in database and you can send them with:

``
./bin/console swiftmailer:spool:send --env=dev
``

and for production:

``
./bin/console swiftmailer:spool:send --env=prod
``
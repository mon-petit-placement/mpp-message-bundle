Symfony Bundle to handle message delivery
=========================================

## Installation

To install this bundle, simply run the following command:

```bash
$ composer require mpp/message-bundle
```
Check if this line `Mpp\MessageBundle\MppMessageBundle::class => ['all' => true],` has beend added into `config/bundles.php`
## How to run

First you have to configure your messages config in `config/packages/mpp_message.yaml`:

```yaml
mpp_message:
    messages:
        message_1:
            from: 'no-reply@test.com'
            subject: 'Message subject 1'
            template_txt: 'email/message_1_template.txt.twig'
            template_html: 'email/message_1_template.html.twig'
            attachments: # This parameter is optional
                -
                    file: 'https://fr.wikipedia.org/wiki/GitHub#/media/Fichier:Octicons-mark-github.svg' #You can use path internal or external with url
                    name: 'github.svg'
                    mime_type: 'image/svg+xml'
        
        message_2:
            from: 'no-reply@test.com'
            subject: 'Message subject 2'
            template_txt: 'email/message_2_template.txt.twig'
            template_html: 'email/message_2_template.html.twig'
        
        #...
```

## How to test
Add this config in `config/routes/dev/mpp_message_dev.yaml`

```yaml
mpp_message:
    resource: "@MppMessageBundle/Controller/*.php"
    type: annotation
    prefix: /test
```
Then, create two files `message_1_template.html.twig` and `message_1_template.txt.twig` into `template/email` with this exemple structure :
```twig
Hello {{ fullname|default('John Doe') }} ! =)
```

And test url in only environment `dev`: `http://your-url-project/test/message/send/message_1`
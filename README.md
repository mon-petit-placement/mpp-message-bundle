Symfony Bundle to handle message delivery
=========================================


## Installation

To install this bundle, simply run the following command:
```bash
$ composer require mpp/message-bundle
```
Check if this line `Mpp\MessageBundle\MppMessageBundle::class => ['all' => true],` has beend added into `config/bundles.php`


## How to use

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

Then you can use the `MessageProvider` to send your message like this:
```php
//...
use Mpp\MessageBundle\Provider\MessageProvider;

//...
protected MessageProvider $messageProvider;
//...

public function __construct(MessageProvider $messageProvider)
{
    $this->messageProvider = $messageProvider;
}
//...

    // In a function where you want to send message
    $transportOptions = [];
    $contents = [];
    $this->messageProvider->send('message_1', $transportOptions, $contents);

//...
```


## How to test

Add this config in `config/routes/dev/mpp_message.yaml`
```yaml
mpp_message:
    resource: "@MppMessageBundle/Controller/*.php"
    type: annotation
    prefix: /test
```

Then, if you look at the `config/packages/mpp_message.yaml` file, create two files `message_1_template.html.twig` and `message_1_template.txt.twig` into `template/email` with this structure :
```twig
Hello {{ fullname|default('John Doe') }} ! =)
```

Now, you can test the following url using GET method (works only in `dev` environment): 
`http://your-url-project/test/message/send/message_1`
The last url path part `message_1` is the message identifier that will be used to send the message.

If you would like to test using custom `transportOptions` and `contents` parameters, you can give them using query parameters like this:
`http://your-url-project/test/message/send/message_1?transportOptions[recipientC][]=recipient@demo.fr&contents[fullname]=Test`
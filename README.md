
## Boiler Plate for Messenger Chatbot

Boiler plate for messenger chatbot is written in [Laravel](https://laravel.com) 7.11.0.  It is intented for chatcommerce projects but could be use in different messenger bot projects as well.
#
## Included features out of the box
- Reports - Basic Dashboard reports.
- Catalogue - Allows managing catalogues/products.
- Orders - Order management module.
- Live Chat - Real time send a chat/reply to users messenger.
#
## Software requirements
- PHP7.2 or higher
- MongoDB extension for PHP
- mongodb php module.
#
## Installation
- Clone this repository, after cloning you must delete `.git` directory
- Install MongoDB extension for PHP. Copy code below in your command line.
  - `apt update && apt upgrade -y`
  - `pecl install mongodb-1.9.0`
- Install mongodb extension for php. Replace `x` by the version of php install on your machine.
  - `apt install php7.x-mongodb`
- Install dependencies: `composer install`
- Final step: Copy content of `.env.example` and update values of the following accdg to your mongodb setup:
  - `MONGODB_HOST=mongodb://192.168.1.5/`
  - `MONGODB_NAME=db_name`
  - `MONGODB_USERNAME=root`
  - `MONGODB_PASSWORD=root`
#
## Create CMS user
To create a CMS user, type command below in your terminal:
- ` php artisan messengerbot:admin`
#
## Sample flow config(XML)
```xml
<main>
    <get_started>
        <bot>
            <message>
                <attachment>
                    <type>template</type>
                    <payload>
                        <template_type>button</template_type>
                        <text>Hi {{firstname}}! Welcome. Please choose from the options below:</text>
                        <buttons>
                            <type>web_url</type>
                            <url>https://example.com</url>
                            <title>Visit messenger</title>
                        </buttons>
                        <buttons>
                            <type>web_url</type>
                            <url>https://rockyourraket.nuworks.ph</url>
                            <title>Visit messenger</title>
                        </buttons>
                    </payload>
                </attachment>
            </message>
            <!-- triggers -->
            <next>getEmail</next>
        </bot>
    </get_started>
    <displayProducts>
        <bot>
            <message>
                <text>Do you want to continue?</text>
                <quick_replies>
                    <content_type>text</content_type>
                    <title>Yes</title>
                    <payload>
                        <action>yes_continue</action>
                    </payload>
                </quick_replies>
            </message>
        </bot>
    </displayProducts>
    <yes_continue>
        <bot>
            <message>
                <text>Hi {{Firstname}}, I need to get your address, please type it below:</text>
            </message>
            <next>getEmail</next>
        </bot>
    </yes_continue>
    <getEmail>
        <bot>
            <message>
                <text>We need your email as well :)</text>
            </message>
        </bot>
    </getEmail>
    <getProducts>
        <class>\FbMessengerBot\Flow\Products</class>
    </getProducts>
    <default>
        <bot>
            <message>
                <text>Sorry, I cannot understand your reply</text>
                <quick_replies>
                    <content_type>text</content_type>
                    <title>Yes</title>
                    <payload>
                        <action>yes_continue</action>
                    </payload>
                </quick_replies>
            </message>
        </bot>
    </default>
</main>
```  
## Author
- [Jeff Claud](https://github.com/crazymeeks)
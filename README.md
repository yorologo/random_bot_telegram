# RandomBot for Telegram

## Create bot

Open @botfather bot and follow instructions on how to create a new bot. As a result, you should get a token. In a moment, We’ll use it to communicate with Telegram API.

## Setup Ngrok tunnel

Telegram needs HTTPS endpoint available on the Internet. Whenever a user does something in a bot it sends a request containing user action info to the endpoint.
Ngrok is a good solution because it is easy to set up and it provides a secure endpoint for free. One disadvantage is that when you stop Ngropk tunnel and start again the new URL is generated. So you have to set webhook again.
A paid Ngrok version offers static URL.

**Create a docker network:**

    docker network create bot_ngrok

**Start Ngrok container and leave it working:**

    docker run --rm -p 4040:4040 --net bot_ngrok \
    	    --name telegram_bot_ngrok wernight/ngrok \
    	    ngrok http telegram_bot:80


**Find out your public address:**

    curl $(docker port telegram_bot_ngrok 4040)/api/tunnels

The command should print a JSON data. Find public_url field there. The content could be like this:

> https://f2183d0.ngrok.io.

## Set up telegram webhook

Now, we have a tunnel. Let’s instruct Telegram to send updates to that Ngrok URL. For that, we need to do a bit of coding, create a directory and install formapro/telegram-bot library:

    docker run --rm --interactive --tty --volume $PWD:/$PWD -w $PWD --user $(id -u):$(id -g) composer:1.8 composer req formapro/telegram-bot

**Execute the script:**

    docker run --rm -v $PWD:/var/www/html -w /var/www/html \
    	-e TELEGRAM_TOKEN=telegram_token \
    	formapro/nginx-php-fpm:latest \
    	php SetWebhook.php "https://f2183d0.ngrok.io/get_updates"

**Start the bot server and leave it working:**

    docker run --rm -p 80:80 -v `pwd`:/var/www/html --net bot_ngrok \
    	-e TELEGRAM_TOKEN=telegram_token \
    	--name telegram_bot \
    	formapro/nginx-php-fpm:latest

## Test the bot

Open your bot in Telegram, type `/start` and in return, you should get

> Hi there! What can I do?.

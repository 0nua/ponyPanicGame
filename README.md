***Installation***

- Step 1 `composer install`
- Step 2 `cp .env.example .env`
- Step 3 Get player id from https://ponypanic.io/ and SET to `PLAYER_TOKEN`
- Step 4 Make some noise with commands `composer run-script newStory` or `composer run-script freestyle`

***Description***

This is a console app for solving a test task from https://ponypanic.io/.

Original console command `php application.php play`.

Check arguments and options: `php application.php play -h`

It is possible to play with a custom freestyle map. Map properties are passed as arguments (see -h for details)
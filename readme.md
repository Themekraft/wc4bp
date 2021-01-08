
## WooCommerce for Buddypress (wc4bp-premium) 
Create a seamless customer experience and get more engagement on your site by integrating your WooCommerce store with your BuddyPress community.

# Setup for development
If you want install this plugin in your local for testing or develop. You need to read carefully the next sections.

### Requirements
- PHP 7
- WordPress
- Docker & Docker Composer

### Installation

* Composer
  * `composer install`
* If you need the TK Script submodule
  * `git submodule update --init --recursive`
  
#### Troubleshooting
If you face composer memory problems like in the next line.

> `PHP Fatal error: Allowed memory size of XXXXXX bytes exhausted <...>`

Use the command

> `php -d memory_limit=-1 <composer path> <...>`

Source: [https://getcomposer.org/doc/articles/troubleshooting.md#memory-limit-errors](https://getcomposer.org/doc/articles/troubleshooting.md#memory-limit-errors) 

### Testing
We use [codeception](https://codeception.com/) and webdriver.

Related commands for testing
* Run chromedriver before start executing the test 
    * `vendor/bin/chromedriver --url-base=/wd/hub`
* Generate Class Test file
    * `vendor/bin/codecept g:cest acceptance <testName>`
* To run all the acceptance test from command line with steps
    * `vendor/bin/codecept run tests/acceptance/SiteNameCest.php --steps`
* To run specific file test from command line with steps
    * `vendor/bin/codecept run <path to the file> --steps`

## Contributors
* @themekraft
* @svenl77
* @slava
* @travel-junkie
* @kishores
* @marin250189
* @gfirem
* @garrett-eclipse

## License

This project is licensed under the GPLv2 or later license - see the [license.txt](LICENSE) file for details.
[ ![Codeship Status for Themekraft/wc4bp](https://app.codeship.com/projects/918f5b60-7309-0135-803a-0e96b9c00ff8/status?branch=master)](https://app.codeship.com/projects/243716)

## WooCommerce for Buddypress (wc4bp-premium) 
Contributors: @themekraft, @svenl77, @slava, @travel-junkie, @kishores, @marin250189, @gfirem, @garrett-eclipse

Shop solution for your BuddyPress community. Integrates a WooCommerce installation with a BuddyPress social network.

#### Development Details
1. IDE PhpStorm (latest)
1. Environment (https://github.com/gfirem/themekraft_environment)

#### VCS
1. SourceTree or git with a git flow module
1. Mandatory of use of git flow
1. To create hot-fix use the name convention h-### (help scout number of the ticket) or g-### (github issue number)

### Unit test 
To run unit test local first you need to have all set inside the vagrant machine. Check the next commands.
1. The user to run the commands. 
	1. `CREATE USER user@localhost IDENTIFIED BY 'userpass'; `
1. The database to run the test
	1. `CREATE DATABASE wordpress_test;`
1. Grant permission to connect from the host machine to the VVV to run the test
	1. `GRANT ALL PRIVILEGES ON wordpress_test.* TO user@'%' IDENTIFIED BY 'userpass';`
1. Run the next command local or remote to configure the environment each time the tmp folder is empty
	1. `bash bin/install-wp-tests.sh wordpress_test user 'userpass' kraft.dev latest true`. This is to run from the host machine
	1. `bash bin/install-wp-tests.sh wordpress_test user 'userpass' localhost latest true`. This is to run inside the VVV
1. `composer test`. With the las command is enough to run the test in both environments. 

# About
A signup example to test Stripe.

# Usage
```
- run gulp

./node_modules/.bin/gulp


- deploy example

./deploy.sh your-path-here


- turn off browser caching when developing

cat .htaccess
# DISABLE CACHING
<IfModule mod_headers.c>
        Header set Cache-Control "no-cache, no-store, must-revalidate"
        Header set Pragma "no-cache"
        Header set Expires 0
</IfModule>

SetEnv STRIPE_PRIVATE_KEY "sk_test_your_stripe_private_key"

SetEnv STRIPE_PUBLIC_KEY "pk_test_your_stripe_public_key"


- restart the web server

sudo apachectl stop

sudo apachectl start

```

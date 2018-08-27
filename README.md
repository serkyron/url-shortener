URL SHORTENER APPLICATION

Deployment:
- composer install
- configure application by adjusting settings in .env file

App configuration is located is ###> Aplication settings ### section of .env configuration file.
Example configuration with all the necessary properties can be found in .env.example file.

Properties:
- APP_URL - responsible to providing a correct short URL like: http://domain/someslug
- REQUESTED_URL_MIN_LENGTH - user requested slug has to be of this length at least
- REQUESTED_URL_MAX_LENGTH - the limit of length for user requested slugs
- SHORT_URL_LENGTH - app will generate slugs of this length
- VALID_RESPONSE_CODES - tells the application which http status codes should be considered
valid when validating provided long URLs. 

Usage:

- Open '/' home page
- Fill in the form and press submit

API usage:

URL shortener accepts GET requests to /api/shorten/url
with the following parameters:

- long_url - a valid URL. (required)
- requested - slug, a string containing characters of word group, such as a-z A-Z 0-9 and _. (optional)

Technologies, libs, frameworks used to create this app:

- Symfony 4
- Bootstrap 4
- Vue 2
- jQuery
- Popper.js
- Axios
- Clipboard.js
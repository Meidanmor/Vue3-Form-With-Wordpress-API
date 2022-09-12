# vue-form-for-wordpress
**NOTE!
Before Development You need to do this steps:**
1. go to vue-form-for-wordpress.php, find "send_contact_form" function and add /* */ for the first if statement in order to cancel the nonce verification (you should use it on production in wordpress for security purpose):
```
/* if ( ! wp_verify_nonce( $request['nonce'], 'vue-nonce' ) ) {
return  false;
} */
//The rest of the code...
```
2. Install the plugin on any wordpress website (in order to create the rest endpoint to communicate with it).

3. Open App.vue inside src folder and change the url value from 'vue_main_object.siteurl'+"send-contact-form/v1/contact/" to "https://domain-name.com/wp-json/send-contact-form/v1/contact/" (and don't forget to change domain-name.com to your domain name..).


## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).

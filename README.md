# GetSimple RESTful API Plugin
Allows the admin (and plugin developers) to build RESTful APIs that return data
which can easily be manipulated with JavaScript. Great for implementing features
that require data to be fetched with AJAX.

# Installation

# Usage

# Plugin Development

```php
<?php

// This example uses I18N Search
// It gets all pages with the tag 'item' and orders them by creation date
// showing 5 entries per pages
// e.g. "/?restapi=items&p=2" shows page 2 of the items
add_action('register-rest-api', 'register_rest_api', array(
  // API ID
  'items',

  // API Configuration
  array(
    'where'  => GSBOTH, // Works for both front and back end
    'action' => function() {
      $max = 5;
      $page = $max * (isset($_GET['p']) ? abs($_GET['p']) - 1 : 0);
      $search = return_i18n_search_results($tags = array('item'), null, $page, $max, $order='-credate');

      // Now format the results so that they can be JSONified propertly
      $results = array();

      foreach ($search['results'] as $result) {
        $results[] = array(
          'title' => $result->title,
          'credate' => $result->credate,
          'content' => $result->content
        );
      }

      return $results;
    }
  )
));
```
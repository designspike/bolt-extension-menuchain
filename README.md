The menuchain extension provides two functions for your **twig templates**.

## menuchain_urls(menu_name, url_to_find)

Given a name of a Bolt menu (e.g. 'main'), and a page's url (e.g. '/about'), returns a list of menu items starting with a root menu item, and ending with the given page URL.

Each item in the list is a URL string.

This function is useful for finding if a given menu item (like a navigation link) is in the "current" navigation hierarchy.

Example:

```twig
{% set menuchain = menuchain_urls('footer', '/annual-reports') %}
{{ dump(menuchain) }}
```

Output:

```
array:3 [▼
  0 => "/public-information"
  1 => "/reports"
  2 => "/annual-reports"
]
```

## menuchain_nodes(menu_name, url_to_find)

Given a name of a Bolt menu (e.g. 'main'), and a page's url (e.g. '/about'), returns a list of menu items starting with a root menu item, and ending with the given page URL.

Each item is an array in the format of Bolt's menu system, and has at least its own label and link.

This function is useful for finding the title and link of each item in the "current" navigation hierarchy.

Example:

```twig
{% set menuchain = menuchain_nodes('main', paths.current) %}
{{ dump(menuchain) }}
```

Output:

```
array:3 [▼
  0 => array:4 [▼
    "label" => "Account"
    "path" => "/account"
    "submenu" => array:7 [▶]
    "link" => "/account"
  ]
  1 => array:3 [▼
    "label" => "Pay my bill online"
    "link" => "https://example.com/"
    "submenu" => array:3 [▶]
  ]
  2 => array:2 [▼
    "label" => "Understand my bill"
    "link" => "/understand-my-bill"
  ]
]
```

## Example 1: Using menuchain_urls() to highlight current navigation hierarchy

```twig
{# before we start generating the menu html #}
{% set menuchain = menuchain_urls('main', paths.current) %}

[...]

{# imagine we are anywhere, like level 1 or 4 of the navigation #}
{% for item in submenu %}
    <a class="{% if item.link in menuchain %}active{% endif %}">{{ item.label }}</a>
{% endfor %}
```


## Example 2: Using menuchain_nodes() to create breadcrumbs

```twig
{# find the path from the root of the navigation, to this page #}
{% set menuchain = menuchain_nodes('main', paths.current) %}

{# display breadcrumbs, like:  Home > Categories > Vegetables > Pears #}
{% if menuchain %}
    <p class="breadcrumbs">
        <a href="/">Home</a> &gt;
        {% for node in menuchain %}
            {% if loop.last %}
                {# don't show a link for current page #}
                {{ node.label }}
            {% else %}
                <a href="{{ node.link }}">{{ node.label }}</a> &gt;
            {% endif %}
        {% endfor %}
    </p>
{% endif %}
```

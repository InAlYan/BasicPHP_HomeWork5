<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/src/css/main.css">
        <title>{{ title }}</title>
    </head>
    <body>
        <p> {{ content_template_cur_time }} </p>
        {% include content_template_header %}
        {% include content_template_name %}
        {% include content_template_sidebar %}
        {% include content_template_footer %}
    </body>
</html>
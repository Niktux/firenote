<?php

return <<<CONTENT
{% extends 'layouts/login.twig' %}

{% block content %}
<div id="login-box" class="login-box visible widget-box no-border">
    <div class="widget-body">
	    <div class="widget-main">
	        <a href="/admin" class="btn btn-danger btn-medium">
	            <i class="icon-desktop" />
	            Back office
	        </a>
        </div>
    </div>
</div>
{% endblock %}
CONTENT
;
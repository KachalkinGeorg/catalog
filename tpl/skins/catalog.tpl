<link href="{{ css }}/skins/catalog.css" type="text/css" rel="stylesheet"/>
<div class="row">  

<div id="symbol" align="center">
<ul>{{catalog_ru}}</ul><br>
<ul>{{catalog_en}}</ul><br>
<ul>{{catalog_kod}}</ul></div>
<br>
<div style="display: inline-block;padding: 10px;color: #87ceeb;">Выбраны новости по символьному коду: <b style="font-size: 20px;">{{symbol}}</b></div>
<br>
<div style="display: inline-block;">{{entries}}</div>

{% if (pages.true) %}
<center><div class="dpad basenavi">
	<div class="bnnavi">
		<div class="navigation">
{% if (prevlink.true) %}
{{ prevlink.link }}
{% endif %}

{{ pages.print }}

{% if (nextlink.true) %}
{{ nextlink.link }}
{% endif %}
        </div>
	</div>
</div></center>
{% endif %}
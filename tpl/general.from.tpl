<form method="post" action="">
	<div class="panel-body" style="font-family: Franklin Gothic Medium;text-transform: uppercase;color: #9f9f9f;">Настройки плагина</div>
	<div class="table-responsive">
	<table class="table table-striped">
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Выберите каталог из которого плагин будет брать шаблоны для отображения</h6>
		  <span class="text-muted text-size-small hidden-xs"><b>Шаблон сайта</b> - плагин будет пытаться взять шаблоны из общего шаблона сайта; в случае недоступности - шаблоны будут взяты из собственного каталога плагина<br /><b>Плагин</b> - шаблоны будут браться из собственного каталога плагина</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
		  {{ localsource }}
        </td>
      </tr>
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Новостей на странице:</h6>
		  <span class="text-muted text-size-small hidden-xs">Укажите количество новостей, выводимых на страницу (по умолчанию 5)</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
			<input name="limit_page" type="number" size="4" value="{{ limit_page }}" />
        </td>
      </tr>
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Символ разделитель:</h6>
		  <span class="text-muted text-size-small hidden-xs">Вы можете указать символ разделитель между буквами и цифрами алфавитного каталога (по умолчанию "|")</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
			<input name="separator" type="text" size="4" maxlength="1" value="{{ separator }}" style="max-width: 40px;text-align: center;"/>
        </td>
      </tr>
	</table>
	</div>
	<div class="panel-footer" align="center">
		<button type="submit" name="submit" class="btn btn-outline-primary">Сохранить</button>
	</div>
</form>
<?php


namespace app\core\helper;


use app\core\Model;


class HTML
{
    protected static $iconsMapping = [
        'view'     => 'eye',
        'edit'     => 'pencil',
        'remove'   => 'trash',
        'delete'   => 'X',
        'add'      => 'plus',
        'cancel'   => 'ban',
        'schedule' => 'calendar',
        'approve'  => 'check',
        'reject'   => 'eject',
    ];

    public static function beginForm(string $action, string $method, string $id = null): void
    {
        echo sprintf('<form action="%s" method="%s" id="%s"s>', $action, $method, $id ?? '');
    }

    public static function endForm(): void
    {
        echo '</form>';
    }

    public static function submit($name = 'Submit', array $htmlOptions = []): void
    {
        echo sprintf('<button type="submit" class="%s">%s</button>', $htmlOptions['class'] ?? 'btn btn-primary', $name);
    }

    public static function link(string $type, string $name, string $destination, array $htmlOption = []): void
    {
        echo sprintf('<a type="%s" href="%s" class="%s" style="%s">%s</a>',
            $type, $destination, $htmlOption['class'] ?? 'btn btn-primary', $htmlOption['style'] ?? '', $name
        );
    }

    public static function inputText(Model $model, string $attributeName, array $options = [])
    {
        return self::inputField('text', $model, $attributeName, $options);
    }

    public static function inputEmail(Model $model, string $attributeName, array $options = [])
    {
        return self::inputField('email', $model, $attributeName, $options);
    }

    public static function inputPassword(Model $model, string $attributeName, array $options = [])
    {
        return self::inputField('password', $model, $attributeName, $options);
    }

    public static function inputNumber(Model $model, string $attributeName, array $options = [])
    {
        return self::inputField('number', $model, $attributeName, $options);
    }

    public static function inputDate(Model $model, string $attributeName, array $options = [])
    {
        return self::inputField('data', $model, $attributeName, $options);
    }

    public static function inputFile(Model $model, string $attributeName, array $options = [])
    {
        return self::inputField('file', $model, $attributeName, $options);
    }

    public static function inputHidden(Model $model, string $attributeName, array $options = [])
    {
        return self::inputField('hidden', $model, $attributeName, $options);
    }

    public static function inputField(string $type, Model $model, string $attributeName, array $options = []): void
    {
        $label = $type != 'hidden' ? sprintf('<label>%s</label>', self::getLabel($attributeName, $options)): '';

        echo $label . sprintf(
            '
              <input placeholder="%s" type="%s" id="%s" name="%s" value="%s" %s
                class="form-control%s">
              <div class="invalid-feedback">%s</div>
            ',
            $options['placeholder'] ?? '',
            $type,
            $attributeName,
            $attributeName,
            $model->{$attributeName} ?? ($options['customValue'] ?? ''),
            isset($options['html']) ? implode(' ', $options['html']) : '',
            $model->getErrors($attributeName) ? ' is-invalid' : '',
            $model->getErrors($attributeName)[0] ?? null
        );
    }

    public static function inputTextarea(Model $model, string $attributeName, array $options = []): void
    {
        echo sprintf(
            '
            <div class="form-group">
              <label>%s</label>
              <textarea placeholder="%s" name="%s" id="%s" class="form-control%s" %s>%s</textarea>
              <div class="invalid-feedback">%s</div>
            </div> 
            ',
            self::getLabel($attributeName, $options),
            $options['placeholder'] ?? '',
            $attributeName,
            $attributeName,
            $model->getErrors($attributeName) ? ' is-invalid' : '',
            isset($options['html']) ? implode(' ', $options['html']) : '',
            $model->{$attributeName},
            $model->getErrors($attributeName)[0] ?? null
        );
    }

    public static function inputSelect(string $attributeName, array $options, array $filter = [], array $htmlOptions = []): void
    {
        $select = '';
        if (!empty($htmlOptions['label'])) {
            $select .= sprintf('<label>%s</label>', self::getLabel($attributeName, $htmlOptions));
        }
        $select .= sprintf(
            '<select id="%s" name="%s" class="%s" style="%s" id="%s">
                <option value="">%s</option>',
            $attributeName,
            $attributeName,
            $htmlOptions['class'] ?? '',
            $htmlOptions['selectStyle'] ?? '',
            $htmlOptions['id'] ?? '',
            $htmlOptions['default'] ?? ''
        );
        foreach ($options as $key => $value) {
            if ($key == 0) {
                continue;
            }
            $select .= sprintf(
                '
                <option value="%s"%s>%s</option>',
                $key,
                (isset($filter[$attributeName]) && $filter[$attributeName] == $key) ? ' selected="selected"' : '',
                $value
            );
        }
        $select .= '</select>';

        echo $select;
    }

    public static function getLabel(string $attributeName, array $options): string
    {
        return $options['label'] ?? self::createLabel($attributeName);
    }

    public static function createLabel(string $attribute): string
    {
        $splitArray = (preg_split('/(?=[A-Z])/', ucfirst($attribute)));
        return implode(' ', $splitArray);
    }

    public static function actionButton(string $action, $destination = null, array $htmlOptions = [])
    {
        $onclick = $htmlOptions['onclick'] ?? '';
        $destination = is_array($destination) ? "$destination[0]/$action?$destination[1]" :
            ($destination ?? '#') ;

        $button = sprintf('<a%s rel="tooltip" href="%s" title="%s">',
                          $onclick, $destination, $htmlOptions['title'] ?? ucfirst($action));

        $button .= sprintf('<img class="icon" src="/images/open-iconic-master/svg/%s.svg"></a>',
            self::$iconsMapping[$action]);

        echo $button;
    }

    public static function url(string $destination, array $arguments = [])
    {
        if (!empty($arguments)) {
            $parameters = [];
            foreach ($arguments as $parameter => $value) {
                $parameters[] = "$parameter=$value";
            }

            $destination .= '?' . implode('&', $parameters);
        }

        echo $destination;
        return $destination;
    }
}

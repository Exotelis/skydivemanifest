<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Das Feld :attribute muss akzeptiert werden.',
    'active_url'           => 'Das Feld :attribute ist keine korrekte URL.',
    'after'                => 'Das Feld :attribute muss ein Datum nach dem :date sein.',
    'after_or_equal'       => 'Das Feld :attribute muss ein Datum nach dem oder am :date sein.',
    'alpha'                => 'Das Feld :attribute darf nur Buchstaben enthalten.',
    'alpha_dash'           => 'Das Feld :attribute darf nur Buchstaben, Zahlen und Bindestriche enthalten.',
    'alpha_num'            => 'Das Feld :attribute darf nur Buchstaben und Zahlen enthalten.',
    'array'                => 'Das Feld :attribute muss eine Liste sein.',
    'before'               => 'Das Feld :attribute muss ein Datum vor dem :date sein.',
    'before_or_equal'      => 'Das Feld :attribute muss ein Datum vor dem oder am :date sein.',
    'between'              => [
        'numeric' => 'Das Feld :attribute muss zwischen :min und :max sein.',
        'file'    => 'Das Feld :attribute muss zwischen :min und :max Kilobytes sein.',
        'string'  => 'Das Feld :attribute muss zwischen :min und :max Zeichen sein.',
        'array'   => 'Das Feld :attribute muss zwischen :min und :max Einträge haben.',
    ],
    'boolean'              => ':attribute muss wahr oder falsch sein.',
    'confirmed'            => 'Die :attribute-Bestätigung stimmt nicht überein.',
    'date'                 => 'Das Feld :attribute ist kein gültiges Datum.',
    'date_equals'          => 'Das Feld :attribute muss das Datum :date sein.',
    'date_format'          => 'Das Feld :attribute entspricht nicht dem Format: :format.',
    'different'            => 'Das Feld :attribute und :other müssen verschieden sein.',
    'digits'               => 'Das Feld :attribute muss :digits Ziffern lang sein.',
    'digits_between'       => 'Das Feld :attribute muss zwischen :min und :max Ziffern lang sein.',
    'dimensions'           => 'Das Feld :attribute hat inkorrekte Bild-Dimensionen.',
    'distinct'             => 'Das Feld :attribute hat einen doppelten Wert.',
    'email'                => 'Das Feld :attribute muss eine korrekte E-Mail-Adresse sein.',
    'ends_with'            => 'Das Feld :attribute muss mit dem folgenden Wert enden: :values.',
    'exists'               => 'Ausgewählte(s) :attribute ist inkorrekt.',
    'file'                 => 'Das Feld :attribute muss eine Datei sein.',
    'filled'               => 'Das Feld :attribute muss ausgefüllt werden.',
    'gt'                   => [
        'numeric' => 'Das Feld :attribute muss größer als :value sein.',
        'file'    => 'Das Feld :attribute muss größer als :value Kilobytes sein.',
        'string'  => 'Das Feld :attribute muss mehr als :value Zeichen lang sein.',
        'array'   => 'Das Feld :attribute muss mehr als :value Einträge besitzen.',
    ],
    'gte'                  => [
        'numeric' => 'Das Feld :attribute muss größer als oder gleich :value sein.',
        'file'    => 'Das Feld :attribute muss größer als oder gleich :value Kilobytes sein.',
        'string'  => 'Das Feld :attribute muss mehr als oder :value Zeichen lang sein.',
        'array'   => 'Das Feld :attribute muss mehr als oder :value Einträge besitzen.',
    ],
    'image'                => 'Das Feld :attribute muss ein Bild sein.',
    'in'                   => 'Ausgewählte(s) :attribute ist inkorrekt.',
    'in_array'             => 'Das Feld :attribute existiert nicht in :other.',
    'integer'              => 'Das Feld :attribute muss eine Ganzzahl sein.',
    'ip'                   => 'Das Feld :attribute muss eine korrekte IP-Adresse sein.',
    'ipv4'                 => 'Das Feld :attribute muss eine korrekte IPv4-Adresse sein.',
    'ipv6'                 => 'Das Feld :attribute muss eine korrekte IPv6-Adresse sein.',
    'json'                 => 'Das Feld :attribute muss ein korrekter JSON-String sein.',
    'lt'                   => [
        'numeric' => 'Das Feld :attribute muss kleiner als :value sein.',
        'file'    => 'Das Feld :attribute muss kleiner als :value Kilobytes sein.',
        'string'  => 'Das Feld :attribute muss weniger als :value Zeichen lang sein.',
        'array'   => 'Das Feld :attribute muss weniger als :value Einträge besitzen.',
    ],
    'lte' => [
        'numeric' => 'Das Feld :attribute muss kleiner als oder gleich :value sein.',
        'file'    => 'Das Feld :attribute muss kleiner als oder gleich :value Kilobytes sein.',
        'string'  => 'Das Feld :attribute muss weniger als oder :value Zeichen lang sein.',
        'array'   => 'Das Feld :attribute muss weniger aös oder :value Einträge besitzen.',
    ],
    'max' => [
        'numeric' => 'Das Feld :attribute darf nicht größer als :max sein.',
        'file'    => 'Das Feld :attribute darf nicht größer als :max Kilobytes sein.',
        'string'  => 'Das Feld :attribute darf nicht länger als :max Zeichen sein.',
        'array'   => 'Das Feld :attribute darf nicht mehr als :max Einträge enthalten.',
    ],
    'mimes'                => 'Das Feld :attribute muss eine Datei in folgendem Format sein: :values.',
    'mimetypes'            => 'Das Feld :attribute muss eine Datei in folgendem Format sein: :values.',
    'min'                  => [
        'numeric' => 'Das Feld :attribute muss mindestens :min sein.',
        'file'    => 'Das Feld :attribute muss mindestens :min Kilobytes groß sein.',
        'string'  => 'Das Feld :attribute muss mindestens :min Zeichen lang sein.',
        'array'   => 'Das Feld :attribute muss mindestens :min Einträge haben.',
    ],
    'not_admin'            => 'Ist ein Administrator.',
    'not_current_user'     => 'Ist der aktuelle Benutzer.',
    'not_in'               => 'Ausgewählte(s) :attribute ist inkorrekt.',
    'not_regex'            => 'Das :attribute-Format ist inkorrekt.',
    'numeric'              => 'Das Feld :attribute muss eine Zahl sein.',
    'password'             => 'Das Passwort ist inkorrekt.',
    'present'              => 'Das Feld :attribute muss vorhanden sein.',
    'regex'                => 'Das :attribute-Format ist inkorrekt.',
    'regexMediumPassword'  => 'Das :attribute muss mindestens ein Groß- und Kleinbuchstaben sowie eine Zahl enthalten. Es muss außerdem mindestens 6 Zeichen lang sein.',
    'regexStrongPassword'  => 'Das :attribute muss mindestens ein Groß- und Kleinbuchstaben, eine Zahl sowie ein Sonderzeichen enthalten. Es muss außerdem mindestens 8 Zeichen lang sein.',
    'regexWeakPassword'    => 'Das :attribute muss mindestens 6 Zeichen lang sein.',
    'required'             => 'Das Feld :attribute wird benötigt.',
    'required_if'          => 'Das Feld :attribute wird benötigt wenn :other einen Wert von :value hat.',
    'required_unless'      => 'Das Feld :attribute wird benötigt außer :other ist in den Werten :values enthalten.',
    'required_with'        => 'Das Feld :attribute wird benötigt wenn :values vorhanden ist.',
    'required_with_all'    => 'Das Feld :attribute wird benötigt wenn :values vorhanden ist.',
    'required_without'     => 'Das Feld :attribute wird benötigt wenn :values nicht vorhanden ist.',
    'required_without_all' => 'Das Feld :attribute wird benötigt wenn keine der Werte :values vorhanden ist.',
    'same'                 => 'Das Feld :attribute und :other müssen gleich sein.',
    'size'                 => [
        'numeric' => 'Das Feld :attribute muss :size groß sein.',
        'file'    => 'Das Feld :attribute muss :size Kilobytes groß sein.',
        'string'  => 'Das Feld :attribute muss :size Zeichen lang sein.',
        'array'   => 'Das Feld :attribute muss :size Einträge enthalten.',
    ],
    'starts_with'          => 'Das Feld :attribute muss mit einem der folgenden Werte starten: :values.',
    'string'               => 'Das Feld :attribute muss Text sein.',
    'timezone'             => 'Das Feld :attribute muss eine korrekte Zeitzone sein.',
    'unique'               => 'Das Feld :attribute wurde bereits verwendet.',
    'uploaded'             => 'Der Upload von :attribute schlug fehl.',
    'url'                  => 'Das :attribute-Format ist inkorrekt.',
    'uuid'                 => 'Das Feld :attribute muss eine gültige UUID sein.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'email' => [
            'unique' => 'Ein Benutzerkonto mit dieser E-Mail-Adresse existiert bereits.',
        ],
        'gender' => [
            'in' => 'Ihr :attribute muss eines der folgenden sein: :values',
        ],
        'is_active' => [
            'boolean' => 'Ihr Benutzerkonto muss entweder aktiviert oder deaktiviert sein.',
        ],
        'locale' => [
            'in' => 'Die :attribute muss eine der folgenden sein: :values',
        ],
        'role' => [
            'exists' => 'Die ausgewählte Rolle existiert nicht.',
            'in'     => 'Sie können dem Benutzer diese Rolle nicht zuweisen. Gültige Rollen sind: :values',
            'unique' => 'Eine Rolle mit diesem Namen existiert bereits.',
        ],
        'tos' => [
            'accepted' => 'Sie müssen den Nutzungsbedingungen zustimmen.',
        ],
        'username' => [
            'unique' => 'Ein Benutzerkonto mit diesem Benutzernamen existiert bereits.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'city'              => 'Stadt',
        'company'           => 'Firma',
        'country_id'        => 'Land',
        'default_invoice'   => 'Standardrechnungsadresse',
        'default_shipping'  => 'Standardversandadresse',
        'dob'               => 'Geburtsdatum',
        'email'             => 'E-Mail-Adresse',
        'email_verified_at' => 'E-Mail-Adresse',
        'firstname'         => 'Vorname',
        'gender'            => 'Geschlecht',
        'is_active'         => 'Benutzerkonto aktiviert',
        'lastname'          => 'Nachname',
        'locale'            => 'Bevorzugte Sprache',
        'middlename'        => 'Zweitname',
        'mobile'            => 'Mobiltelefon',
        'new_password'      => 'Neues Passwort',
        'password'          => 'Passwort',
        'password_reset'    => 'Password zurücksetzen',
        'phone'             => 'Telefon',
        'postal'            => 'Postleitzahl',
        'region_id'         => 'Region',
        'role'              => 'Benutzerrolle',
        'street'            => 'Straße',
        'username'          => 'Benutzername',
        'without_account'   => 'kein Benutzerkonto erstellen'
    ],

];

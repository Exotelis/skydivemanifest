<?php

return [
    '400'                            => 'Die Anfrage war fehlerhaft aufgebaut.',
    '401'                            => 'Sie sind nicht angemeldet.',
    '403'                            => 'Sie haben keine Berechtigungen.',
    '404'                            => 'Die angeforderte Ressource wurde nicht gefunden.',
    '500'                            => 'Ein unerwarteter Fehler ist aufgetreten.',
    'access_denied'                  => 'Sie haben nicht die erforderlichen Berechtigungen, um diese Seite zu besuchen.',
    'account_disabled'               => 'Ihr Benutzerkonto ist deaktiviert.',
    'account_cannot_change_own_role' => 'Sie können Ihre eigene Benutzerrolle nicht ändern.',
    'account_cannot_disable'         => 'Sie können Ihr eigenes Benutzerkonto nicht deaktivieren.',
    'change_password'                => 'Sie müssen ein neues Passwort vergeben!',
    'could_not_sign_out'             => 'Benutzer konnte nicht ausgeloggt werden. Benutzen Sie ein Token, welches nicht an Benutzer gebunden ist?',
    'email_not_verified'             => 'Sie haben Ihre E-Mail-Adresse noch nicht bestätigt.',
    'email_token_expired'            => 'Das Token zum Bestätigen der E-Mail-Adresse ist abgelaufen.',
    'email_token_invalid'            => 'Das Token zum Bestätigen der E-Mail-Adresse ist ungültig.',
    'email_token_not_found'          => 'Sie haben keine Änderung Ihrer E-Mail-Adresse angefordert.',
    'email_token_throttled'          => 'Bitte warten Sie, bevor Sie es erneut versuchen. Sie können nur einmal pro :time eine neue Bestätigungsmail anfordern.',
    'role_in_use'                    => 'Die Benutzerrolle kann nicht gelöscht werden, da mindestens ein Benutzer dieser Benutzerrolle zugewiesen ist. Entfernen Sie zuerst alle Benutzer aus der Benutzerrolle, um diese löschen zu können.',
    'roles_in_use'                   => 'Der Benutzerrolle mit der ID :id ist mindestens ein Benutzer zugewiesen. Entfernen Sie zuerst alle Benutzer aus der Benutzerrolle, um diese löschen zu können.',
    'role_not_deletable'             => 'Die Benutzerrolle ist schreibgeschützt und kann nicht gelöscht werden.',
    'role_not_deletable_id'          => 'Die Benutzerrolle mit der ID :id ist schreibgeschützt.',
    'role_not_editable'              => 'Die Benutzerrolle ist schreibgeschützt, die Berechtigungen dieser Benutzerrolle können nicht geändert werden.',
    'temporarily_locked'             => 'Sie haben Ihre Benutzerdaten zu häufig falsch eingegeben, daher wurde Ihr Benutzerkonto vorübergehend gesperrt. Es wird automatisch in :expires freigeschaltet.',
    'too_many_attempts'              => 'Zu viele Anfragen. Bitte warten Sie, bevor Sie eine neue Anfrage absenden.',
    'tos_not_accepted'               => 'Sie haben die Nutzungsbedingungen noch nicht akzeptiert.',
    'user_not_deletable_last_admin'  => 'Der einzige Benutzer mit Administratorrechten kann nicht gelöscht werden.',
    'validation_error'               => 'Die Überprüfung der gesendeten Daten ist fehlgeschlagen.',
];

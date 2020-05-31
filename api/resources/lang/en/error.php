<?php

return [
    '400'                   => 'Bad Request.',
    '401'                   => 'You are not signed in.',
    '403'                   => 'You don\'t have permissions.',
    '500'                   => 'An unexpected error has occurred.',
    'account_disabled'      => 'Your account is disabled.',
    'change_password'       => 'You must set a new password!',
    'could_not_sign_out'    => 'Could not log the user out. Are you using a non user token?',
    'email_not_verified'    => 'Your email address is not verified.',
    'email_token_expired'   => 'This email change token has expired.',
    'email_token_invalid'   => 'This email change token is invalid.',
    'email_token_not_found' => 'You haven\'t requested an email address change.',
    'email_token_throttled' => 'Please wait before retrying. You can resend the verification email once every :time.',
    'temporarily_locked'    => 'Your account has been locked temporarily, because you entered incorrect user credentials too often. It will be unlocked in :expires.',
];

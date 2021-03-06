import { AxiosError } from 'axios';

export default {
  all (params?: any): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (params && params.limit !== 999) {
        resolve({
          data: {
            current_page: 2,
            data: [
              { 'id': 1, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 2, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 3, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 4, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 5, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 6, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 7, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 8, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 9, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 10, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 11, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 12, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 13, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 14, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 15, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 16, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 17, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 18, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 19, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 20, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 21, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 22, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 23, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 24, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 25, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 26, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 27, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 28, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 29, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
              { 'id': 30, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': 'm', 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } }
            ],
            from: 11,
            last_page: 3,
            per_page: 10,
            to: 20,
            total: 30
          },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'The request failed.' },
        status: 400,
        statusText: 'Bad Request',
        headers: {},
        config: {}
      };
      reject(e);
    });
  },

  bulkDelete (ids: Array<number>): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (ids[0] !== 999) {
        resolve({
          data: {
            count: ids.length,
            message: ids.length === 1 ? 'One user has been deleted.' : ids.length + ' users have been deleted.'
          },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: {
          message: 'Users could not be deleted.'
        },
        status: 422,
        statusText: 'Unprocessable Entity',
        headers: {},
        config: {}
      };
      reject(e);
    });
  },

  deletePermanently (ids: Array<number>): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (ids[0] !== 999) {
        resolve({
          data: {
            count: ids.length,
            message: ids.length === 1
              ? 'One user has been deleted permanently.'
              : ids.length + ' users have been deleted permanently.'
          },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: {
          message: 'Users could not be deleted.'
        },
        status: 422,
        statusText: 'Unprocessable Entity',
        headers: {},
        config: {}
      };
      reject(e);
    });
  },

  restore (ids: Array<number>): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (ids[0] !== 999) {
        resolve({
          data: {
            count: ids.length,
            message: ids.length === 1 ? 'One user has been restored.' : ids.length + ' users have been restored.'
          },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: {
          message: 'The given data was invalid.',
          errors: {
            'ids.0': [ 'Is the current user.' ]
          }
        },
        status: 422,
        statusText: 'Unprocessable Entity',
        headers: {},
        config: {}
      };
      reject(e);
    });
  }
};

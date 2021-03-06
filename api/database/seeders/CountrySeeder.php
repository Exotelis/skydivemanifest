<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

/**
 * Class CountrySeeder
 */
class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::insert($this->getCountries());
    }

    /**
     * Return list of countries.
     *
     * @return array
     */
    protected function getCountries()
    {
        return [
            ['id' => 1, 'country' => 'Andorra', 'code' => 'ad',],
            ['id' => 2, 'country' => 'United Arab Emirates', 'code' => 'ae',],
            ['id' => 3, 'country' => 'Afghanistan', 'code' => 'af',],
            ['id' => 4, 'country' => 'Antigua and Barbuda', 'code' => 'ag',],
            ['id' => 5, 'country' => 'Anguilla', 'code' => 'ai',],
            ['id' => 6, 'country' => 'Albania', 'code' => 'al',],
            ['id' => 7, 'country' => 'Armenia', 'code' => 'am',],
            ['id' => 8, 'country' => 'Netherlands Antilles', 'code' => 'an',],
            ['id' => 9, 'country' => 'Angola', 'code' => 'ao',],
            ['id' => 10, 'country' => 'Argentina', 'code' => 'ar',],
            ['id' => 11, 'country' => 'Austria', 'code' => 'at',],
            ['id' => 12, 'country' => 'Australia', 'code' => 'au',],
            ['id' => 13, 'country' => 'Aruba', 'code' => 'aw',],
            ['id' => 14, 'country' => 'Azerbaijan', 'code' => 'az',],
            ['id' => 15, 'country' => 'Bosnia and Herzegovina', 'code' => 'ba',],
            ['id' => 16, 'country' => 'Barbados', 'code' => 'bb',],
            ['id' => 17, 'country' => 'Bangladesh', 'code' => 'bd',],
            ['id' => 18, 'country' => 'Belgium', 'code' => 'be',],
            ['id' => 19, 'country' => 'Burkina Faso', 'code' => 'bf',],
            ['id' => 20, 'country' => 'Bulgaria', 'code' => 'bg',],
            ['id' => 21, 'country' => 'Bahrain', 'code' => 'bh',],
            ['id' => 22, 'country' => 'Burundi', 'code' => 'bi',],
            ['id' => 23, 'country' => 'Benin', 'code' => 'bj',],
            ['id' => 24, 'country' => 'Bermuda', 'code' => 'bm',],
            ['id' => 25, 'country' => 'Brunei Darussalam', 'code' => 'bn',],
            ['id' => 26, 'country' => 'Bolivia', 'code' => 'bo',],
            ['id' => 27, 'country' => 'Brazil', 'code' => 'br',],
            ['id' => 28, 'country' => 'Bahamas', 'code' => 'bs',],
            ['id' => 29, 'country' => 'Bhutan', 'code' => 'bt',],
            ['id' => 30, 'country' => 'Botswana', 'code' => 'bw',],
            ['id' => 31, 'country' => 'Belarus', 'code' => 'by',],
            ['id' => 32, 'country' => 'Belize', 'code' => 'bz',],
            ['id' => 33, 'country' => 'Canada', 'code' => 'ca',],
            ['id' => 34, 'country' => 'Cocos (Keeling) Islands', 'code' => 'cc',],
            ['id' => 35, 'country' => 'Democratic Republic of the Congo', 'code' => 'cd',],
            ['id' => 36, 'country' => 'Central African Republic', 'code' => 'cf',],
            ['id' => 37, 'country' => 'Congo', 'code' => 'cg',],
            ['id' => 38, 'country' => 'Switzerland', 'code' => 'ch',],
            ['id' => 39, 'country' => 'Cote D\'Ivoire (Ivory Coast)', 'code' => 'ci',],
            ['id' => 40, 'country' => 'Cook Islands', 'code' => 'ck',],
            ['id' => 41, 'country' => 'Chile', 'code' => 'cl',],
            ['id' => 42, 'country' => 'Cameroon', 'code' => 'cm',],
            ['id' => 43, 'country' => 'China', 'code' => 'cn',],
            ['id' => 44, 'country' => 'Colombia', 'code' => 'co',],
            ['id' => 45, 'country' => 'Costa Rica', 'code' => 'cr',],
            ['id' => 46, 'country' => 'Cuba', 'code' => 'cu',],
            ['id' => 47, 'country' => 'Cape Verde', 'code' => 'cv',],
            ['id' => 48, 'country' => 'Christmas Island', 'code' => 'cx',],
            ['id' => 49, 'country' => 'Cyprus', 'code' => 'cy',],
            ['id' => 50, 'country' => 'Czech Republic', 'code' => 'cz',],
            ['id' => 51, 'country' => 'Germany', 'code' => 'de',],
            ['id' => 52, 'country' => 'Djibouti', 'code' => 'dj',],
            ['id' => 53, 'country' => 'Denmark', 'code' => 'dk',],
            ['id' => 54, 'country' => 'Dominica', 'code' => 'dm',],
            ['id' => 55, 'country' => 'Dominican Republic', 'code' => 'do',],
            ['id' => 56, 'country' => 'Algeria', 'code' => 'dz',],
            ['id' => 57, 'country' => 'Ecuador', 'code' => 'ec',],
            ['id' => 58, 'country' => 'Estonia', 'code' => 'ee',],
            ['id' => 59, 'country' => 'Egypt', 'code' => 'eg',],
            ['id' => 60, 'country' => 'Western Sahara', 'code' => 'eh',],
            ['id' => 61, 'country' => 'Eritrea', 'code' => 'er',],
            ['id' => 62, 'country' => 'Spain', 'code' => 'es',],
            ['id' => 63, 'country' => 'Ethiopia', 'code' => 'et',],
            ['id' => 64, 'country' => 'Finland', 'code' => 'fi',],
            ['id' => 65, 'country' => 'Fiji', 'code' => 'fj',],
            ['id' => 66, 'country' => 'Falkland Islands (Malvinas)', 'code' => 'fk',],
            ['id' => 67, 'country' => 'Federated States of Micronesia', 'code' => 'fm',],
            ['id' => 68, 'country' => 'Faroe Islands', 'code' => 'fo',],
            ['id' => 69, 'country' => 'France', 'code' => 'fr',],
            ['id' => 70, 'country' => 'Gabon', 'code' => 'ga',],
            ['id' => 71, 'country' => 'Great Britain (UK)', 'code' => 'gb',],
            ['id' => 72, 'country' => 'Grenada', 'code' => 'gd',],
            ['id' => 73, 'country' => 'Georgia', 'code' => 'ge',],
            ['id' => 74, 'country' => 'French Guiana', 'code' => 'gf',],
            ['id' => 75, 'country' => 'NULL', 'code' => 'gg',],
            ['id' => 76, 'country' => 'Ghana', 'code' => 'gh',],
            ['id' => 77, 'country' => 'Gibraltar', 'code' => 'gi',],
            ['id' => 78, 'country' => 'Greenland', 'code' => 'gl',],
            ['id' => 79, 'country' => 'Gambia', 'code' => 'gm',],
            ['id' => 80, 'country' => 'Guinea', 'code' => 'gn',],
            ['id' => 81, 'country' => 'Guadeloupe', 'code' => 'gp',],
            ['id' => 82, 'country' => 'Equatorial Guinea', 'code' => 'gq',],
            ['id' => 83, 'country' => 'Greece', 'code' => 'gr',],
            ['id' => 84, 'country' => 'S. Georgia and S. Sandwich Islands', 'code' => 'gs',],
            ['id' => 85, 'country' => 'Guatemala', 'code' => 'gt',],
            ['id' => 86, 'country' => 'Guinea-Bissau', 'code' => 'gw',],
            ['id' => 87, 'country' => 'Guyana', 'code' => 'gy',],
            ['id' => 88, 'country' => 'Hong Kong', 'code' => 'hk',],
            ['id' => 89, 'country' => 'Honduras', 'code' => 'hn',],
            ['id' => 90, 'country' => 'Croatia (Hrvatska)', 'code' => 'hr',],
            ['id' => 91, 'country' => 'Haiti', 'code' => 'ht',],
            ['id' => 92, 'country' => 'Hungary', 'code' => 'hu',],
            ['id' => 93, 'country' => 'Indonesia', 'code' => 'id',],
            ['id' => 94, 'country' => 'Ireland', 'code' => 'ie',],
            ['id' => 95, 'country' => 'Israel', 'code' => 'il',],
            ['id' => 96, 'country' => 'India', 'code' => 'in',],
            ['id' => 97, 'country' => 'Iraq', 'code' => 'iq',],
            ['id' => 98, 'country' => 'Iran', 'code' => 'ir',],
            ['id' => 99, 'country' => 'Iceland', 'code' => 'is',],
            ['id' => 100, 'country' => 'Italy', 'code' => 'it',],
            ['id' => 101, 'country' => 'Jamaica', 'code' => 'jm',],
            ['id' => 102, 'country' => 'Jordan', 'code' => 'jo',],
            ['id' => 103, 'country' => 'Japan', 'code' => 'jp',],
            ['id' => 104, 'country' => 'Kenya', 'code' => 'ke',],
            ['id' => 105, 'country' => 'Kyrgyzstan', 'code' => 'kg',],
            ['id' => 106, 'country' => 'Cambodia', 'code' => 'kh',],
            ['id' => 107, 'country' => 'Kiribati', 'code' => 'ki',],
            ['id' => 108, 'country' => 'Comoros', 'code' => 'km',],
            ['id' => 109, 'country' => 'Saint Kitts and Nevis', 'code' => 'kn',],
            ['id' => 110, 'country' => 'Korea (North)', 'code' => 'kp',],
            ['id' => 111, 'country' => 'Korea (South)', 'code' => 'kr',],
            ['id' => 112, 'country' => 'Kuwait', 'code' => 'kw',],
            ['id' => 113, 'country' => 'Cayman Islands', 'code' => 'ky',],
            ['id' => 114, 'country' => 'Kazakhstan', 'code' => 'kz',],
            ['id' => 115, 'country' => 'Laos', 'code' => 'la',],
            ['id' => 116, 'country' => 'Lebanon', 'code' => 'lb',],
            ['id' => 117, 'country' => 'Saint Lucia', 'code' => 'lc',],
            ['id' => 118, 'country' => 'Liechtenstein', 'code' => 'li',],
            ['id' => 119, 'country' => 'Sri Lanka', 'code' => 'lk',],
            ['id' => 120, 'country' => 'Liberia', 'code' => 'lr',],
            ['id' => 121, 'country' => 'Lesotho', 'code' => 'ls',],
            ['id' => 122, 'country' => 'Lithuania', 'code' => 'lt',],
            ['id' => 123, 'country' => 'Luxembourg', 'code' => 'lu',],
            ['id' => 124, 'country' => 'Latvia', 'code' => 'lv',],
            ['id' => 125, 'country' => 'Libya', 'code' => 'ly',],
            ['id' => 126, 'country' => 'Morocco', 'code' => 'ma',],
            ['id' => 127, 'country' => 'Monaco', 'code' => 'mc',],
            ['id' => 128, 'country' => 'Moldova', 'code' => 'md',],
            ['id' => 129, 'country' => 'Madagascar', 'code' => 'mg',],
            ['id' => 130, 'country' => 'Marshall Islands', 'code' => 'mh',],
            ['id' => 131, 'country' => 'Macedonia', 'code' => 'mk',],
            ['id' => 132, 'country' => 'Mali', 'code' => 'ml',],
            ['id' => 133, 'country' => 'Myanmar', 'code' => 'mm',],
            ['id' => 134, 'country' => 'Mongolia', 'code' => 'mn',],
            ['id' => 135, 'country' => 'Macao', 'code' => 'mo',],
            ['id' => 136, 'country' => 'Northern Mariana Islands', 'code' => 'mp',],
            ['id' => 137, 'country' => 'Martinique', 'code' => 'mq',],
            ['id' => 138, 'country' => 'Mauritania', 'code' => 'mr',],
            ['id' => 139, 'country' => 'Montserrat', 'code' => 'ms',],
            ['id' => 140, 'country' => 'Malta', 'code' => 'mt',],
            ['id' => 141, 'country' => 'Mauritius', 'code' => 'mu',],
            ['id' => 142, 'country' => 'Maldives', 'code' => 'mv',],
            ['id' => 143, 'country' => 'Malawi', 'code' => 'mw',],
            ['id' => 144, 'country' => 'Mexico', 'code' => 'mx',],
            ['id' => 145, 'country' => 'Malaysia', 'code' => 'my',],
            ['id' => 146, 'country' => 'Mozambique', 'code' => 'mz',],
            ['id' => 147, 'country' => 'Namibia', 'code' => 'na',],
            ['id' => 148, 'country' => 'New Caledonia', 'code' => 'nc',],
            ['id' => 149, 'country' => 'Niger', 'code' => 'ne',],
            ['id' => 150, 'country' => 'Norfolk Island', 'code' => 'nf',],
            ['id' => 151, 'country' => 'Nigeria', 'code' => 'ng',],
            ['id' => 152, 'country' => 'Nicaragua', 'code' => 'ni',],
            ['id' => 153, 'country' => 'Netherlands', 'code' => 'nl',],
            ['id' => 154, 'country' => 'Norway', 'code' => 'no',],
            ['id' => 155, 'country' => 'Nepal', 'code' => 'np',],
            ['id' => 156, 'country' => 'Nauru', 'code' => 'nr',],
            ['id' => 157, 'country' => 'Niue', 'code' => 'nu',],
            ['id' => 158, 'country' => 'New Zealand (Aotearoa)', 'code' => 'nz',],
            ['id' => 159, 'country' => 'Oman', 'code' => 'om',],
            ['id' => 160, 'country' => 'Panama', 'code' => 'pa',],
            ['id' => 161, 'country' => 'Peru', 'code' => 'pe',],
            ['id' => 162, 'country' => 'French Polynesia', 'code' => 'pf',],
            ['id' => 163, 'country' => 'Papua New Guinea', 'code' => 'pg',],
            ['id' => 164, 'country' => 'Philippines', 'code' => 'ph',],
            ['id' => 165, 'country' => 'Pakistan', 'code' => 'pk',],
            ['id' => 166, 'country' => 'Poland', 'code' => 'pl',],
            ['id' => 167, 'country' => 'Saint Pierre and Miquelon', 'code' => 'pm',],
            ['id' => 168, 'country' => 'Pitcairn', 'code' => 'pn',],
            ['id' => 169, 'country' => 'Palestinian Territory', 'code' => 'ps',],
            ['id' => 170, 'country' => 'Portugal', 'code' => 'pt',],
            ['id' => 171, 'country' => 'Palau', 'code' => 'pw',],
            ['id' => 172, 'country' => 'Paraguay', 'code' => 'py',],
            ['id' => 173, 'country' => 'Qatar', 'code' => 'qa',],
            ['id' => 174, 'country' => 'Reunion', 'code' => 're',],
            ['id' => 175, 'country' => 'Romania', 'code' => 'ro',],
            ['id' => 176, 'country' => 'Russian Federation', 'code' => 'ru',],
            ['id' => 177, 'country' => 'Rwanda', 'code' => 'rw',],
            ['id' => 178, 'country' => 'Saudi Arabia', 'code' => 'sa',],
            ['id' => 179, 'country' => 'Solomon Islands', 'code' => 'sb',],
            ['id' => 180, 'country' => 'Seychelles', 'code' => 'sc',],
            ['id' => 181, 'country' => 'Sudan', 'code' => 'sd',],
            ['id' => 182, 'country' => 'Sweden', 'code' => 'se',],
            ['id' => 183, 'country' => 'Singapore', 'code' => 'sg',],
            ['id' => 184, 'country' => 'Saint Helena', 'code' => 'sh',],
            ['id' => 185, 'country' => 'Slovenia', 'code' => 'si',],
            ['id' => 186, 'country' => 'Svalbard and Jan Mayen', 'code' => 'sj',],
            ['id' => 187, 'country' => 'Slovakia', 'code' => 'sk',],
            ['id' => 188, 'country' => 'Sierra Leone', 'code' => 'sl',],
            ['id' => 189, 'country' => 'San Marino', 'code' => 'sm',],
            ['id' => 190, 'country' => 'Senegal', 'code' => 'sn',],
            ['id' => 191, 'country' => 'Somalia', 'code' => 'so',],
            ['id' => 192, 'country' => 'Suriname', 'code' => 'sr',],
            ['id' => 193, 'country' => 'Sao Tome and Principe', 'code' => 'st',],
            ['id' => 194, 'country' => 'El Salvador', 'code' => 'sv',],
            ['id' => 195, 'country' => 'Syria', 'code' => 'sy',],
            ['id' => 196, 'country' => 'Swaziland', 'code' => 'sz',],
            ['id' => 197, 'country' => 'Turks and Caicos Islands', 'code' => 'tc',],
            ['id' => 198, 'country' => 'Chad', 'code' => 'td',],
            ['id' => 199, 'country' => 'French Southern Territories', 'code' => 'tf',],
            ['id' => 200, 'country' => 'Togo', 'code' => 'tg',],
            ['id' => 201, 'country' => 'Thailand', 'code' => 'th',],
            ['id' => 202, 'country' => 'Tajikistan', 'code' => 'tj',],
            ['id' => 203, 'country' => 'Tokelau', 'code' => 'tk',],
            ['id' => 204, 'country' => 'Turkmenistan', 'code' => 'tm',],
            ['id' => 205, 'country' => 'Tunisia', 'code' => 'tn',],
            ['id' => 206, 'country' => 'Tonga', 'code' => 'to',],
            ['id' => 207, 'country' => 'Turkey', 'code' => 'tr',],
            ['id' => 208, 'country' => 'Trinidad and Tobago', 'code' => 'tt',],
            ['id' => 209, 'country' => 'Tuvalu', 'code' => 'tv',],
            ['id' => 210, 'country' => 'Taiwan', 'code' => 'tw',],
            ['id' => 211, 'country' => 'Tanzania', 'code' => 'tz',],
            ['id' => 212, 'country' => 'Ukraine', 'code' => 'ua',],
            ['id' => 213, 'country' => 'Uganda', 'code' => 'ug',],
            ['id' => 214, 'country' => 'Uruguay', 'code' => 'uy',],
            ['id' => 215, 'country' => 'Uzbekistan', 'code' => 'uz',],
            ['id' => 216, 'country' => 'Saint Vincent and the Grenadines', 'code' => 'vc',],
            ['id' => 217, 'country' => 'Venezuela', 'code' => 've',],
            ['id' => 218, 'country' => 'Virgin Islands (British)', 'code' => 'vg',],
            ['id' => 219, 'country' => 'Virgin Islands (U.S.)', 'code' => 'vi',],
            ['id' => 220, 'country' => 'Viet Nam', 'code' => 'vn',],
            ['id' => 221, 'country' => 'Vanuatu', 'code' => 'vu',],
            ['id' => 222, 'country' => 'Wallis and Futuna', 'code' => 'wf',],
            ['id' => 223, 'country' => 'Samoa', 'code' => 'ws',],
            ['id' => 224, 'country' => 'Yemen', 'code' => 'ye',],
            ['id' => 225, 'country' => 'Mayotte', 'code' => 'yt',],
            ['id' => 226, 'country' => 'South Africa', 'code' => 'za',],
            ['id' => 227, 'country' => 'Zambia', 'code' => 'zm',],
            ['id' => 228, 'country' => 'Zaire (former)', 'code' => 'zr',],
            ['id' => 229, 'country' => 'Zimbabwe', 'code' => 'zw',],
            ['id' => 230, 'country' => 'United States of America', 'code' => 'us',],
        ];
    }
}

<?php
/**
 * @copyright Copyright (c) 2024 Camila Ayres <hello@camilasan.com>
 *
 * @author Camila Ayres <hello@camilasan.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);

return [
	'routes' => [
		['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],

    // profiles
        ['name' => 'profile#index', 'url' => '/profiles', 'verb' => 'GET'],
        ['name' => 'profile#create', 'url' => '/profiles', 'verb' => 'POST'],
        ['name' => 'profile#show', 'url' => '/profiles/{profileId}', 'verb' => 'GET'],
        ['name' => 'profile#update', 'url' => '/profiles/{profileId}', 'verb' => 'PUT'],
        ['name' => 'profile#delete', 'url' => '/profiles/{profileId}', 'verb' => 'DELETE'],

    // calendar
        ['name' => 'calendar#index', 'url' => '/calendars', 'verb' => 'GET'],
        ['name' => 'calendar#create', 'url' => '/calendars', 'verb' => 'POST'],
        ['name' => 'calendar#show', 'url' => '/calendars/{calendarId}', 'verb' => 'GET'],
        ['name' => 'calendar#update', 'url' => '/calendars/{calendarId}', 'verb' => 'PUT'],
        ['name' => 'calendar#delete', 'url' => '/calendars/{calendarId}', 'verb' => 'DELETE'],

    // symptom
        ['name' => 'symptom#index', 'url' => '/symptoms', 'verb' => 'GET'],
        ['name' => 'symptom#create', 'url' => '/symptoms', 'verb' => 'POST'],
        ['name' => 'symptom#show', 'url' => '/symptoms/{symptomId}', 'verb' => 'GET'],
        ['name' => 'symptom#update', 'url' => '/symptoms/{symptomId}', 'verb' => 'PUT'],
        ['name' => 'symptom#delete', 'url' => '/symptoms/{symptomId}', 'verb' => 'DELETE'],

    // category
        ['name' => 'category#index', 'url' => '/categories', 'verb' => 'GET'],
        ['name' => 'category#create', 'url' => '/categories', 'verb' => 'POST'],
        ['name' => 'category#show', 'url' => '/categories/{categoryId}', 'verb' => 'GET'],
        ['name' => 'category#update', 'url' => '/categories/{categoryId}', 'verb' => 'PUT'],
        ['name' => 'category#delete', 'url' => '/categories/{categoryId}', 'verb' => 'DELETE'],
	]
];

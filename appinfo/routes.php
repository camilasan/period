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
	]
];

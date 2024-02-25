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

namespace OCA\Period\Tests\Integration\Controller;

use OCA\Period\Controller\ProfileController;
use OCA\Period\Db\Profile;
use OCA\Period\Db\ProfileMapper;

use OCP\AppFramework\App;
use OCP\IRequest;
use PHPUnit\Framework\TestCase;

class ProfileIntegrationTest extends TestCase {
	private ProfileController $controller;
	private QBMapper $mapper;
	private string $userId = 'john';

	public function setUp(): void {
		$app = new App('period');
		$container = $app->getContainer();

		// only replace the user id
		$container->registerService('userId', function () {
			return $this->userId;
		});

		// we do not care about the request but the controller needs it
		$container->registerService(IRequest::class, function () {
			return $this->createMock(IRequest::class);
		});

		$this->controller = $container->get(ProfileController::class);
		$this->mapper = $container->get(ProfileMapper::class);
	}

	public function testUpdate(): void {
		// create a new note that should be updated
		$profile = new Profile();
        $profile->setName('pregnant');
        $profile->setAge(32);
        $profile->setContraceptive('none');
        $profile->setUserId($this->userId);

		$id = $this->mapper->insert($profile)->getId();

		// fromRow does not set the fields as updated
		$updatedProfile = Profile::fromRow([
			'id' => $id,
			'user_id' => $this->userId
		]);
        $updatedProfile->setName('after pregnancy');
        $updatedProfile->setAge(33);
        $updatedProfile->setContraceptive('pill');

		$result = $this->controller->update($id, 'after pregnancy', 33, 'pill');

		$this->assertEquals($updatedProfile, $result->getData());

		// clean up
		$this->mapper->delete($result->getData());
	}
}

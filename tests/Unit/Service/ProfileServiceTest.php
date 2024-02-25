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

namespace OCA\Period\Tests\Unit\Service;

use OCA\Period\Db\Profile;
use OCA\Period\Db\ProfileMapper;

use OCA\Period\Service\ProfileNotFound;

use OCA\Period\Service\ProfileService;
use OCP\AppFramework\Db\DoesNotExistException;
use PHPUnit\Framework\TestCase;

use OCP\ILogger;

class ProfileServiceTest extends TestCase {
	private ProfileService $service;
	private string $userId = 'maria';
	private $mapper;

    private iLogger $logger;

	public function setUp(): void {
		$this->mapper = $this->getMockBuilder(ProfileMapper::class)
			->disableOriginalConstructor()
			->getMock();
		$this->service = new ProfileService($this->mapper, $this->logger);
	}

	public function testUpdate(): void {
		// the existing note
		$profile = Profile::fromRow([
			'id' => 3,
			'name' => 'before pregnancy',
			'age' => 34,
            'contraceptive' => 'pill'
		]);
		$this->mapper->expects($this->once())
			->method('find')
			->with($this->equalTo(3))
			->will($this->returnValue($profile));

		// the note when updated
		$updatedProfile = Profile::fromRow(['id' => 3]);
        $updatedProfile->setName('before pregnancy');
        $updatedProfile->setAge(34);
        $updatedProfile->setContraceptive('pill');
		$this->mapper->expects($this->once())
			->method('update')
			->with($this->equalTo($updatedProfile))
			->will($this->returnValue($updatedProfile));

		$result = $this->service->update(3, 'before pregnancy', 34, 'pill', $this->userId);

		$this->assertEquals($updatedProfile, $result);
	}

    public function testDelete(): void {
        // the existing note
        $profile = Profile::fromRow([
            'id' => 3,
            'name' => 'before pregnancy',
            'age' => 34,
            'contraceptive' => 'pill'
        ]);
        $this->mapper->expects($this->once())
            ->method('find')
            ->with($this->equalTo(3))
            ->will($this->returnValue($profile));

        // the note when updated
        $deletedProfile = Profile::fromRow(['id' => 3]);
        $this->mapper->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($deletedProfile))
            ->will($this->returnValue($deletedProfile));

        $result = $this->service->delete(3, $this->userId);

        $this->assertEquals($deletedProfile, $result);
    }

	public function testUpdateNotFound(): void {
		$this->expectException(ProfileNotFound::class);
		// test the correct status code if no note is found
		$this->mapper->expects($this->once())
			->method('find')
			->with($this->equalTo(3))
			->will($this->throwException(new DoesNotExistException('')));

		$this->service->update(3, 'before pregnancy', 34, 'pill', $this->userId);
	}
}

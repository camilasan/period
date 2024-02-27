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

namespace OCA\Period\Tests\Unit\Controller;

use OCA\Period\Controller\ProfileController;

use OCA\Period\Service\ExceptionNotFound;
use OCA\Period\Service\ProfileService;

use OCP\AppFramework\Http;
use OCP\IRequest;
use PHPUnit\Framework\TestCase;
use OCP\ILogger;

class ProfileControllerTest extends TestCase {
	protected ProfileController $controller;
	protected string $userId = 'maria';
	protected $service;
	protected $request;
    protected Ilogger $logger;

	public function setUp(): void {
		$this->request = $this->getMockBuilder(IRequest::class)->getMock();
		$this->service = $this->getMockBuilder(ProfileService::class)
			->disableOriginalConstructor()
			->getMock();
		$this->controller = new ProfileController($this->request, $this->service, $this->userId, $this->logger);
	}

	public function testUpdate(): void {
		$profile = 'just check if this value is returned correctly';
		$this->service->expects($this->once())
			->method('update')
			->with($this->equalTo(3),
                $this->equalTo('before pregnancy'),
				$this->equalTo(34),
				$this->equalTo('pill'),
				$this->equalTo($this->userId))
			->will($this->returnValue($profile));

		$result = $this->controller->update(3, 'before pregnancy', 34, 'pill');

		$this->assertEquals($profile, $result->getData());
	}

    public function testDelete(): void {
        $profile = 'just check if this value is returned correctly';
        $this->service->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(3),
                $this->equalTo('before pregnancy'),
                $this->equalTo(34),
                $this->equalTo('pill'),
                $this->equalTo($this->userId))
            ->will($this->returnValue($profile));

        $result = $this->controller->delete(3);

        $this->assertEquals($profile, $result->getData());
    }

	public function testUpdateNotFound(): void {
		// test the correct status code if no note is found
		$this->service->expects($this->once())
			->method('update')
			->will($this->throwException(new ExceptionNotFound()));

		$result = $this->controller->update(3, 'title', 42, 'pill');

		$this->assertEquals(Http::STATUS_NOT_FOUND, $result->getStatus());
	}
}

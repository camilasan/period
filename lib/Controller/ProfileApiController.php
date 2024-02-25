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

namespace OCA\Period\Controller;

use OCA\Period\AppInfo\Application;
use OCA\Period\Service\ProfileService;
use OCP\AppFramework\ApiController;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;
use OCP\ILogger;

class ProfileApiController extends ApiController {
    protected ProfileService $service;
    protected ?string $userId;

    private ILogger $logger;

    use Errors;

    public function __construct(IRequest       $request,
                                ProfileService $service,
                                ?string        $userId,
                                ILogger        $logger) {
        parent::__construct(Application::APP_ID, $request);
        $this->service = $service;
        $this->userId = $userId;
        $this->logger = $logger;
    }

    /**
     * @CORS
     * @NoCSRFRequired
     * @NoAdminRequired
     */
    public function index(): DataResponse {
        return new DataResponse($this->service->findAll($this->userId));
    }

    /**
     * @CORS
     * @NoCSRFRequired
     * @NoAdminRequired
     */
    public function show(int $profileId): DataResponse {
        return $this->handleNotFound(function () use ($profileId) {
            return $this->service->find($profileId, $this->userId);
        });
    }

    /**
     * @CORS
     * @NoCSRFRequired
     * @NoAdminRequired
     */
    public function create(string $name, int $age, string $contraceptive): DataResponse {
        return new DataResponse($this->service->create($name, $age, $contraceptive,
            $this->userId));
    }

    /**
     * @CORS
     * @NoCSRFRequired
     * @NoAdminRequired
     */
    public function update(int $profileId, string $name, int $age,
                           string $contraceptive): DataResponse {
        return $this->handleNotFound(function () use ($profileId, $name, $age, $contraceptive) {
            return $this->service->update($profileId, $name, $age, $contraceptive, $this->userId);
        });
    }

    /**
     * @CORS
     * @NoCSRFRequired
     * @NoAdminRequired
     */
    public function delete(int $profileId): DataResponse {
        $this->logger->debug('Trying to delete profile id: '.$profileId);
        $this->logger->debug('b user id: '.$this->userId);
        return $this->handleNotFound(function () use ($profileId) {
            return $this->service->delete($profileId, $this->userId);
        });
    }
}

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
use OCA\Period\Service\ContraceptiveService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;
use OCP\ILogger;

class ContraceptiveController extends Controller {
    private ContraceptiveService $service;
    private ?string $userId;

    use Errors;

    private ILogger $logger;

    public function __construct(IRequest       $request,
                                ContraceptiveService $service,
                                ?string        $userId,
                                ILogger        $logger) {
        parent::__construct(Application::APP_ID, $request);
        $this->service = $service;
        $this->userId = $userId;
        $this->logger = $logger;
    }

    /**
     * @NoAdminRequired
     */
    public function index(): DataResponse {
        return new DataResponse($this->service->findAll($this->userId));
    }

    /**
     * @NoAdminRequired
     */
    public function show(int $contraceptiveId): DataResponse {
        return $this->handleNotFound(function () use ($contraceptiveId) {
            return $this->service->find($contraceptiveId, $this->userId);
        });
    }

    /**
     * @NoAdminRequired
     */
    public function create(string $name, string $description, string $sideEffects): DataResponse {
        return new DataResponse($this->service->create($name, $description, $sideEffects, $this->userId));
    }

    /**
     * @NoAdminRequired
     */
    public function update(int $contraceptiveId, string $name,
                           string $description, string $sideEffects): DataResponse {
        return $this->handleNotFound(function () use ($contraceptiveId, $name, $description, $sideEffects) {
            return $this->service->update($contraceptiveId, $name, $description, $sideEffects, $this->userId);
        });
    }

    /**
     * @NoAdminRequired
     */
    public function delete(int $contraceptiveID): DataResponse {
        $this->logger->debug('Trying to delete contraceptive id: ' . $contraceptiveID);
        $this->logger->debug('by user id: ' . $this->userId);
        return $this->handleNotFound(function () use ($contraceptiveID) {
            return $this->service->delete($contraceptiveID, $this->userId);
        });
    }
}
